<?php

namespace App\Http\Controllers;

use Auth;
use App\Category;
use App\EmployeeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EmployeeExpenseStoreRequest;
use Illuminate\Support\Facades\Validator;

class EmployeeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$userId = Auth::user()->id;
    	if (request()->ajax()) {
    		$data = array();
    		//$expense = EmployeeExpense::where('user_id',$userId)->orderBy('id', 'asc')->get();
    		
    		$expense = new EmployeeExpense();
    		$per_page = 10;
    		if(isset($request->per_page) && $request->per_page != ''){
    			$per_page = $request->per_page;
    		}
    		$expenseList = $expense->paginate($per_page);
    		if(!empty($expenseList)){
    			foreach ($expenseList AS $expenseDetails){
    				$expenseData=array();
    				$expenseData[0] =$expenseDetails->id;
    				$expenseData[1] = date('m/d/Y', strtotime($expenseDetails->expense_date));
    				$expenseData[2] = $expenseDetails->category->title;
    				$expenseData[3] = $expenseDetails->expense_description;
    				$expenseData[4] = $expenseDetails->pre_tax_amount;
    				$expenseData[5] = $expenseDetails->tax_amount;
    				
    				$data['data'][] = $expenseData;
    			}
    		}
    		
    		$data['iTotalRecords'] = $expenseList->total();
    		$data['iTotalDisplayRecords'] = 10;
    		
    		return response()->json($data);
    	}
    	return view('employees_expense.index',compact('expense'));
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
     * Store a newly created resource in storage.
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
    	
    	$import_file= $request->file('import_file');
    	$recode = $this->employeeExpensesImport($import_file);

    	$request->session()->flash('alert-success', 'successfully expense data import');
    	return redirect()->route('home');
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
    
    static function employeeExpensesImport($filename){
    	
    	$userId = Auth::user()->id;
    	ini_set('auto_detect_line_endings', true);
    	
    	$fileD = fopen($filename,"r");
    	$column=fgetcsv($fileD);
    	$i=0;
    	while(!feof($fileD)){ $i++;
    		$line_of_text = fgets($fileD);
    		$parts = explode('|', $line_of_text);
    	
    		if(count($parts)==7){
    			if(date('Y', strtotime($parts[0])) > date("Y")){
    				$message = "invalid date on row no ".($i+1)." please correct it and try again";
    				return $message;
    			}
    			$validatedData= array('expense_date'=>date('Y-m-d', strtotime($parts[0])),
    					'category'=>$parts[1],
    					'employee_name'=>$parts[2],
    					'employee_address'=>$parts[3],
    					'expense_description'=>$parts[4],
    					'pre_tax_amount'=>$parts[5],
    					'tax_amount'=>$parts[6]
    			);
    			$rowData[]=$validatedData;
    		}else {
    			$message = "invalid data format on row no ".($i+1)." please correct it and try again";
    			return $message;
    		}
    	}
    	
    	foreach ($rowData as $key => $value) {
    		//'employee_name'=>$value[2],
    		//'employee_address'=>$value[3],
    		$expenseData=array(
    				'expense_date'=>$value['expense_date'],
    				'category_id'=>Category::loadCategory($value['category']),
    				'user_id'=>$userId,
    				'expense_description'=>$value['expense_description'],
    				'pre_tax_amount'=>$value['pre_tax_amount'],
    				'tax_amount'=>$value['tax_amount'],
    				'created_at'=>date('Y-m-d H:i:s'),
    				'updated_at'=>date('Y-m-d H:i:s'),
    			);
    		try {
    			$expenseDetails= EmployeeExpense::create($expenseData);
    			
    		} catch (\Exception $e) {
    			return $e->getMessage();
    		}
    		
    	}
        
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorData(array $data)
    {
    	return Validator::make($data, [
    			'expense_date' => 'required|string|max:50',
    			'category' => 'required|string|max:50',
    			'employee_name' => 'required|string|max:50',
    			'employee_address' => 'required|string|max:100',
    			'expense_description' => 'required|string|max:255',
    			'pre_tax_amount' => 'required|regex:/^\d*(\.\d{1,2})?$/',
    			'tax_amount' => 'required|regex:/^\d*(\.\d{1,2})?$/'
    	]);
    }
}
