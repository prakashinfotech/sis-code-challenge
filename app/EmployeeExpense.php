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
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
			'expense_date',
	];
	
	/**
	 * Override parent boot and Call deleting
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();
	}
	
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
