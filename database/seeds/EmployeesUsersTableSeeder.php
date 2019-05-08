<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class EmployeesUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$employees= [
    			[
    					'name' => 'Steve Rogers',
    					'address' => '783 Park Ave New York NY 10021',
    					'email' => 'SteveRogers@email.com'
    			],
    			[
    					'name' => 'Tony Stark',
    					'address' => '1 Infinite Loop Cupertino CA 95014',
    					'email' => 'TonyStark@email.com'
    			],
    			[
    					'name' => 'Bruce Banner',
    					'address' => '1 Infinite Loop Cupertino CA 95014',
    					'email' => 'BruceBanner@email.com'
    			],
    			[
    					'name' => 'Nick Fury',
    					'address' => '1 Infinite Loop Cupertino CA 95014',
    					'email' => 'NickFury@email.com'
    			],
    			[
    					'name' => 'Natasha Romanoff',
    					'address' => '1600 Amphitheatre Parkway Mountain View CA 94043',
    					'email' => 'NatashaRomanoff@email.com'
    			],
    			[
    					'name' => 'Carol Danvers',
    					'address' => '1600 Amphitheatre Parkway Mountain View CA 94043',
    					'email' => 'CarolDanvers@email.com'
    			],
    			[
    					'name' => 'Stephen Strange',
    					'address' => '1600 Amphitheatre Parkway Mountain View CA 94043',
    					'email' => 'StephenStrange@email.com'
    			]
    	];
    	
    	foreach ($employees AS $employee){
    		$user = User::create([
    				'name' => $employee['name'],
    				'email' => $employee['email'],
    				'address' => $employee['address'],
    				'password' =>Hash::make('123456')
    				//'email_verified_at' => null,
    			]);
    	}
    	
    }
}
