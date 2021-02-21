<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UsersRole;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = $user_roles = [];
        $user[0]['name'] = 'User 1';
        $user[1]['name'] = 'User 2';
        
        User::insert($user);
        
        $user_roles[0]['user_id'] = 1;
        $user_roles[0]['role_id'] = 1;
        $user_roles[1]['user_id'] = 2;
        $user_roles[1]['role_id'] = 1;
        $user_roles[2]['user_id'] = 2;
        $user_roles[2]['role_id'] = 2;
        
        UsersRole::insert($user_roles);
    }
}
