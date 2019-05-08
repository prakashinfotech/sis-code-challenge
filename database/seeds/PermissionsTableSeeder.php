<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('permissions')->insert ( array (
				array (
						'name' => 'Administer roles & permissions',
						'guard_name' => 'web',
						'created_at' => date ( 'Y-m-d H:i:s', strtotime ( "now" ) ),
						'updated_at' => date ( 'Y-m-d H:i:s', strtotime ( "now" ) ) 
				) 
		) );
    }
}
