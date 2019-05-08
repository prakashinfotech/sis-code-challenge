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
    		if ($request->is('dashboard') || $request->is('employees-expense') || $request->is('employees-expense/*'))
    		{
    			return $next($request);
    		}else {
    			abort('401');
    		}
    	}elseif (Auth::user ()->hasRole ( 'Employee' )) {
    		if ($request->is('dashboard') || $request->is('employees-expense') || $request->is('employees-expense-add') || $request->is('employees-expense-addstore'))
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