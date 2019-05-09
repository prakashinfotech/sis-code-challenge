<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$categories= [
    			'Travel',
    			'Meals and Entertainment',
    			'Computer - Hardware',
    			'Computer - Software',
    			'Office Supplies'
    	];
    	foreach ($categories AS $category){
    		Category::create([
    				'title' => $category,
    		]);
    		
    	}
    }
}
