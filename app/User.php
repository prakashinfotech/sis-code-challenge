<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'address' ,'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get all of the employeeExpense for the employee.
     */
    public function employeeExpense()
    {
    	return $this->hasMany('App\EmployeeExpense','user_id','id');
    }
    public static function loadEmployee($name, $address)
    {
    	$user = User::where('name', $name)->where('address',$address)->first();
    	if (!empty($user)) {
    		return $user->id;
    	}
    	return false;
    }
}
