<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_id = Role::getSuperAdminId();
        $items = [
            [
            	'id' => 1,
	            'display_name' => 'Admin',
	            'email' => 'admin@admin.com',
	            'payment_email' => 'admin@admin.com',
	            'password' => Hash::make('password'),
	            'api_token' => Str::random(60),
	            'role_id' => $role_id,
	            'remember_token' => '',
	            'approved' => 1
            ],
        ];

        foreach ($items as $item) {
            \App\User::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
