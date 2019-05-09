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
		return $this->belongsTo(EmployeeExpense::class,'category_id','id');
	}
	
	public static function loadCategory($category)
    {
		$cat = Category::where('title', $category)->first();
		if (empty($cat)) {
			$cat= Category::create(['title' => $category]);
		}
		return $cat->id;
	}
}
