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
	Route::get('dashboard', 'HomeController@index')->name('dashboard');
	Route::resource('employees-expense', 'EmployeeExpenseController')->names('employees_expense');
	Route::get('/employees-expense-add', 'EmployeeExpenseController@add')->name('employees-expense-add');
	Route::post('/employees-expense-addstore', 'EmployeeExpenseController@addstore')->name('employees-expense-addstore');
});