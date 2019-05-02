<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	User::create([
    			'name' => 'Admin',
    			'email' => 'admin@admin.de',
    			'password' => Hash::make('admin123'),
    			'email_verified_at' => Carbon\Carbon::now(),
    	]);
    }
}
