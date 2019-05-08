<?php

namespace App\Http\Controllers;

use Auth;
use App\Category;
use App\EmployeeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EmployeeExpenseStoreRequest;
use App\Http\Requests\EmployeeExpenseAddStoreRequest;
use App\User;
use Carbon\Carbon;

class EmployeeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request)
    {
    	$userId = Auth::user()->id;
    	if (request()->ajax()) {
    
    		$expense = new EmployeeExpense();
    		$totalData = $expense->count();
    		$totalFiltered = $totalData;
    		
    		$limit = $request->length; 
    		$start =  $request->start; 
    		$order=	$request->columns[$request->order[0]['column']]['name'];
    		$dir =$request->order[0]['dir'];
    		$search =$request->search['value'];
    		
    		if(!empty($search))
    		{
    			$expense= $expense->whereHas('category',function ($query) use ($search){
    				$query->where(function ($q) use ($search) {
    					$q->where('title', 'LIKE', '%'.$search.'%');
    				});
    			})->orWhere('expense_description', 'LIKE', '%'.$search.'%')
    			->orWhere('pre_tax_amount', 'LIKE', '%'.$search.'%')
    			->orWhere('tax_amount', 'LIKE', '%'.$search.'%');
    		}
    		if(isset($order) && $order!= ''){
    			$expense= $expense->orderBy($order, $dir);
    		}
    		$expense = $expense->offset($start)->limit($limit)->get();
    		$data = array();
    		if(!empty($expense))
    		{
    			foreach ($expense as $expenseDetails)
    			{
    				$expenseData=array();
    				$expenseData[0] = $expenseDetails->id;
    				$expenseData[1] = date('m/d/Y', strtotime($expenseDetails->expense_date));
    				$expenseData[2] =  $expenseDetails->category->title;
    				$expenseData[3] = $expenseDetails->expense_description;
    				$expenseData[4] = $expenseDetails->pre_tax_amount;
    				$expenseData[5] = $expenseDetails->tax_amount;
    				$data[] = $expenseData;
    			}
    		}
    		$json_data = array(
    				"draw"            => intval($_GET['draw']),
    				"recordsTotal"    => intval($totalData),
    				"recordsFiltered" => intval($totalFiltered),
    				"data"            => $data,
    				"expense"            => $expense
    		);
    	  return response()->json($json_data);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $categories = Category::orderBy('title')->pluck('title','id')->prepend('-- Select Category --',0);
        return view('employees_expense.add', compact('categories'));
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
    	$filename = $request->file('import_file');
    	$header = null;
    	$delimiter = '|';
    	$data = array();
    	if (($handle = fopen($filename, 'r')) !== false){
    		while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
    			if (!$header){
    				$header = $row;
    			} else{
    				try {
    					$data[] = array_combine($header, $row);
    				} catch (\Exception $e) {
    					$request->session()->flash('alert-danger', 'invalid file provided');
    					return redirect()->route('employees_expense.create');
    				}
    			}
    		}
    		fclose($handle);
    	}
    	
    	if(count($data)>0){
    		foreach ($data AS $value){
    			$expenseData=array(
    				'expense_date'=>Carbon::parse($value['date'])->format('Y-m-d'),
    				'category_id'=>Category::loadCategory($value['category']),
    				'user_id'=>User::loadEmployee($value['employee_name'], $value['employee_address']),
    				'expense_description'=>$value['expense_description'],
    				'pre_tax_amount'=>$value['pre_tax_amount'],
    				'tax_amount'=>$value['tax_amount'],
    				'created_at'=>date('Y-m-d H:i:s'),
    				'updated_at'=>date('Y-m-d H:i:s')    				
    			);
    			try {
    				$expenseDetails= EmployeeExpense::create($expenseData);
    			} catch (\Exception $e) {
    				$request->session()->flash('alert-danger', 'invalid file provided');
    				return redirect()->route('employees_expense.create');
    			}
    		}
    		$request->session()->flash('alert-success', 'successfully expense data import');
    		return redirect()->route('employees_expense.index');
    	}
    }
    
    /**
     * Store a newly created resource in storage.
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
}
