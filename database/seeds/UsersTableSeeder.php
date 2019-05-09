<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	if(DB::table('users')->get()->count() == 0){
    		DB::table('users')->insert([
    				'id' => 1,
    				'name' => 'Admin',
    				'email' => 'admin@admin.com',
    				'password' => Hash::make('admin123'),
    				'email_verified_at' => Carbon\Carbon::now(),
    				'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
    		]);
    		DB::table('model_has_roles')->insert([
    				'role_id' => 1,
    				'model_id' => 1,
    				'model_type' => 'App\User'
    		]);
    	}else {
    		$users =DB::table('users')->get();
    		foreach ($users AS $user){
    			if($user->id==1){
    				DB::table('model_has_roles')->insert([
    						'role_id' => 1,
    						'model_id' => $user->id,
    						'model_type' => 'App\User'
    				]);
    			}else {
    				DB::table('model_has_roles')->insert([
    						'role_id' => 2,
    						'model_id' => $user->id,
    						'model_type' => 'App\User'
    				]);
    			}
    		}
    	}
    }
}
