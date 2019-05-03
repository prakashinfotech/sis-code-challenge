<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'title'
	];
	
	/**
	 * Get all of the employeeExpense for the category.
	 */	
	public function employeeExpense()
	{
		return $this->hasMany('App\EmployeeExpense','category_id','id');
	}
}
