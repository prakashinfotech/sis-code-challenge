<?php

namespace App\Http\Controllers;

use Auth;
use App\Category;
use App\EmployeeExpense;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeExpenseStoreRequest;
use App\Http\Requests\EmployeeExpenseAddStoreRequest;
use App\User;
use Carbon\Carbon;

class EmployeeExpenseController extends Controller
{
    /**
     * Display a listing of the expenses.
     * To admin it will show all expenses
     * To employee it will show only own expenses
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
    {
    	$currentYear = date('Y');
    	$currentMonth = date('m');
    	$years = array();
    	for($i=10; $i>=0; $i--) {
    		$years[$currentYear - $i] = $currentYear - $i;
    	}
    	$months = array();
    	for($m=1; $m<=12; ++$m){
    		$months[date('m', mktime(0, 0, 0, $m, 1))] = date('F', mktime(0, 0, 0, $m, 1));
    	}
    	
    	$categories = Category::orderBy('title')->pluck('title','id')->prepend('-- Select Category --',0);
    	if (Auth::user ()->hasRole ('Admin')) {
    		return view('employees_expense.index',compact('years', 'currentYear','months','currentMonth','categories'));
    	}else {
    		return view('employees_expense.my-expense',compact('years', 'currentYear','months','currentMonth','categories'));
    	}
    	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('employees_expense.import');
    }
    
    /**
     * Show the form for creating a new expense.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $categories = Category::orderBy('title')->pluck('title','id')->prepend('-- Select Category --',0);
        return view('employees_expense.add', compact('categories'));
    }

    /**
     * Import employee expense from file upload(pipe separated values in file).
     * 
     * @param  App\Http\Requests\EmployeeExpenseStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeExpenseStoreRequest $request)
    {
    	$validatedData= $request->validated();
    	if(!($validatedData)){
    		return redirect('employees-expense/create')->withErrors($validatedData)->withInput();
    	}
    	$fileName = $request->file('import_file')->getRealPath();
    	$header = null;
    	$delimiter = '|';
    	$expenseRecords = array();
    	//check if we can read file or not
    	if (($handle = fopen($fileName, 'r')) !== false){
    		//loop through file records line by line and make one array of all lines
    		while (($employeeExpense = fgetcsv($handle, 1000, $delimiter)) !== false){
    			if (!$header){
    				$header = $employeeExpense;
    			} else{
    				try {
    					$expenseRecords[] = array_combine($header, $employeeExpense);
    				} catch (\Exception $e) {
    					$request->session()->flash('alert-danger', 'invalid file provided');
    					return redirect()->route('employees_expense.create');
    				}
    			}
    		}
    		fclose($handle);
    	}
    	
    	if(count($expenseRecords)>0){
    		//use current timestamp for versioning of uploads to show aggregated report after upload grouped by this timestamp
    		$currentTimestamp = Carbon::now()->timestamp; 
    		foreach ($expenseRecords AS $expenseRow){
    			$expenseData=array(
    				'expense_date'=>Carbon::parse($expenseRow['date'])->format('Y-m-d'),
    				'category_id'=>Category::loadCategory($expenseRow['category']),
    				'user_id'=>User::loadEmployee($expenseRow['employee_name'], $expenseRow['employee_address']),
    				'expense_description'=>$expenseRow['expense_description'],
    				'pre_tax_amount'=>$expenseRow['pre_tax_amount'],
    				'tax_amount'=>$expenseRow['tax_amount'],
    				'upload_version'=>$currentTimestamp,
    				'created_at'=>date('Y-m-d H:i:s'),
    				'updated_at'=>date('Y-m-d H:i:s')    				
    			);
    			try {
    				EmployeeExpense::create($expenseData);
    			} catch (\Exception $e) {
    				$request->session()->flash('alert-danger', 'invalid file provided');
    				return redirect()->route('employees_expense.create');
    			}
    		}
    		$request->session()->flash('alert-success', 'successfully expense data import');
    		return redirect()->route('upload-version-report', ['id' => $current_timestamp]);
    	}
    }
    
    /**
     * Store a newly created expense data in storage.
     *
     * @param  App\Http\Requests\EmployeeExpenseAddStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function addstore(EmployeeExpenseAddStoreRequest $request)
    {
        $validatedData = $request->validated();
        if(!$validatedData){
            return redirect('employees-expense-add')->withErrors($validatedData)->withInput();
        }
        
        if(isset($validatedData['expense_date']) && $validatedData['expense_date'] != ''){
            $validatedData['expense_date'] = Carbon::parse($validatedData['expense_date'])->format('Y-m-d');
        }
        $validatedData['user_id'] = Auth::user()->id;
        
        EmployeeExpense::create($validatedData);
        $request->session()->flash('alert-success', 'Expense has been created successfully');
        return redirect()->route('employees_expense.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Report: Monthly Expense Summary for Admin
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monthlyExpenseReport(Request $request)
    {
        if (request()->ajax()) {
            $expense = new EmployeeExpense();
            //get all expenses occured in said year grouped by month 
            $expenseData = $expense
            ->selectRaw("
                MONTH(expense_date) AS expense_month,
            	SUM(pre_tax_amount) AS total_pre_tax_amount,
            	SUM(tax_amount) AS total_tax_amount,
            	SUM(pre_tax_amount + tax_amount) AS total
            ")
            ->whereYear('expense_date', $request->year)
            ->groupBy('expense_month')
            ->orderBy('expense_month', 'ASC')
            ->get();
            
            $monthWiseYealyExpense = array();
            if(!empty($expenseData)){
                foreach ($expenseData as $monthlyExpense){
                    $monthWiseYealyExpense[] = array(
                        date('F', mktime(0,0,0,$monthlyExpense['expense_month'])),
                        $monthlyExpense['total_pre_tax_amount'],
                        $monthlyExpense['total_tax_amount'],
                        $monthlyExpense['total']
                    );
                }
            }
            
            $json_data = array("data" => $monthWiseYealyExpense);                
            return response()->json($json_data);
        }
        
        $currentYear = date('Y');        
        $years = array();        
        for($i=10; $i>=0; $i--) {
            $years[$currentYear - $i] = $currentYear - $i;
        }
        
        return view('employees_expense.monthly-expense-report', compact('years', 'currentYear'));
    }
    
    /**
     * get employees expenses
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employeesExpenseList(Request $request){
    	if (request()->ajax()) {
    		$expense = EmployeeExpense::with('category','employee');
    		
    		//show only own records in case of logged in user role is Employee
    		if (Auth::user ()->hasRole ('Employee')) {
    			$expense->where('user_id',Auth::user ()->id);
    		}
    		// SET CATEGORY FILTER
    		if(isset($request->categoryList) && $request->categoryList!= 0){
    			$category_id= $request->categoryList;
    			$expense= $expense->whereHas('category',function ($q) use ($category_id){
    				$q->where('id', $category_id);
    				});
    		}
    		// SET YEAR FILTER 
    		if(isset($request->year) && $request->year!= ''){
    			$expense= $expense->whereYear('expense_date', $request->year);
    		}
    		//  SET MONTH FILTER
    		if(isset($request->month) && $request->month!= ''){
    			$expense= $expense->whereMonth('expense_date', $request->month);
    		}
    		
    		$order=	$request->columns[$request->order[0]['column']]['name'];
    		$dir =$request->order[0]['dir'];
    		if(isset($order) && $order!= ''){
    			$expense= $expense->orderBy($order, $dir);
    		}
    		
    		$expenseData = $expense->groupBy('employee_expenses.id')->get();
    		$employeeExpense = array();
    		if(!empty($expenseData)){
    			foreach ($expenseData as $expenseRow){
    				$tempData = array();
    				$tempData[] = $expenseRow['id'];
    				
    				if(Auth::user ()->hasRole ('Admin')){
    					$tempData[] = $expenseRow['employee']['name'];
    				}
    				
    				$tempData[] = date('m/d/Y', strtotime($expenseRow['expense_date']));
    				$tempData[] = $expenseRow['category']['title'];
    				$tempData[] = $expenseRow['expense_description'];
    				$tempData[] = $expenseRow['pre_tax_amount'];
    				$tempData[] = $expenseRow['tax_amount'];
    				$tempData[] = number_format(($expenseRow['pre_tax_amount'] + $expenseRow['tax_amount']),2);
    				
    				$employeeExpense[] = $tempData;
    			}
    		}
    		$json_data = array(
    				"draw"            => intval($request['draw']),
    				"data"            => $employeeExpense
    		);
            return response()->json($json_data);
    	}
    }
	/**
     * Report: Uploaded data summary for Admin by uploadVersionId
     * 
     */
    public function uploadVersionReport(Request $request)
    {
        $versionId = $request->versionId;
        
        //get employees expense aggregated data by uploadVersion
        $expense = new EmployeeExpense();
        $expenseData = $expense
        ->selectRaw("
            YEAR(expense_date) AS expense_year, 
        	MONTH(expense_date) AS expense_month, 
        	SUM(pre_tax_amount) AS total_pre_tax_amount, 
        	SUM(tax_amount) AS total_tax_amount, 
        	SUM(pre_tax_amount + tax_amount) AS total
        ")
        ->where('upload_version', $versionId)
        ->groupBy(['expense_year', 'expense_month'])
        ->orderBy('expense_year', 'ASC')
        ->orderBy('expense_month', 'ASC')
        ->get();
        
        $uploadedData = array();
        if(!empty($expenseData)){
            foreach ($expenseData as $employeeExpense){
                $uploadedData[] = array(
                    'expense_year' => $employeeExpense['expense_year'],
                    'expense_month' => date('F', mktime(0,0,0,$employeeExpense['expense_month'])),
                    'total_pre_tax_amount' => $employeeExpense['total_pre_tax_amount'],
                    'total_tax_amount' => $employeeExpense['total_tax_amount'],
                    'total' => $employeeExpense['total']
                );
            }
        }
        return view('employees_expense.upload-version-report', compact('uploadedData'));
    }
}
