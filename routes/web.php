<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('home');
Auth::routes();
Auth::routes(['verify' => true]);
Route::middleware('verified')->group(function() {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('employees-expense', 'EmployeeExpenseController')->names('employees_expense');
	//Route::resource('employees-expense', 'EmployeeExpenseController')->only(['index', 'create','store'])->name('employees_expense');
	//Route::resource('employees-expense', 'EmployeeExpenseController')->except(['show','edit','update', 'destroy'])->name('employees_expense');
});