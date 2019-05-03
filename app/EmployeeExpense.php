<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeExpense extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'expense_date',
			'category_id',
			'user_id',
			'expense_description',
			'pre_tax_amount',
			'tax_amount'
	];
	
	/**
	 * Get the category that owns the employeeExpense.
	 */
	public function category()
	{
		return $this->belongsTo(Category::class,'category_id','id');
	}
	
	/**
	 * Get the employee that owns the employeeExpense.
	 */
	public function employee()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}
}