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
    	$this->call([
    			RolesTableSeeder::class,
    			PermissionsTableSeeder::class,
    			UsersTableSeeder::class,
    			EmployeesUsersTableSeeder::class,
    			CategoriesTableSeeder::class
    	]);
    }
}
