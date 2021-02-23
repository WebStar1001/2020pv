<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [
            
            ['id' => Role::getSuperAdminId(), 'slug' => 'super_admin', 'title' => 'Super Administrator',],
            ['id' => Role::getAdminId(), 'slug' => 'admin', 'title' => 'Admin',],
            ['id' => Role::getCustomerId(), 'slug' => 'customer', 'title' => 'Customer',],
            ['id' => Role::getAdvisorId(), 'slug' => 'advisor', 'title' => 'Advisor',],

        ];

        foreach ($items as $item) {
            \App\Role::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
