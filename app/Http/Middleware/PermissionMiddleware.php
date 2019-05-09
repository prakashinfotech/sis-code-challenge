<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {        
    	if (Auth::user ()->hasRole ('Admin')) {
    	//validate incoming requests against permissions for that role and throw 401 error if not allowed
   		if ($request->is('dashboard') || $request->is('employees-expense') || $request->is('employees-expense/*') || $request->is('monthly-expense-report') || $request->is('employees-expense-list') || $request->is('upload-version-report/*'))
 		{
    			return $next($request);
    		}else {
    			abort('401');
    		}
    	}elseif (Auth::user ()->hasRole ( 'Employee' )) {
    		if ($request->is('dashboard') || $request->is('employees-expense') || $request->is('employees-expense-add') || $request->is('employees-expense-addstore') || $request->is('employees-expense-list'))
    		{
    			return $next($request);
    		}else {
    			abort('401');
    		}
    	}else {
    		return $next($request);
    	}
    }
}