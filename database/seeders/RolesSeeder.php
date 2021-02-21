<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::firstOrCreate(['name' => 'Role1']);
        
        $permissions = Permission::get();
        if(!empty($permissions[0]))
            $role1->givePermissionTo($permissions[0]);
        
        $role2 = Role::firstOrCreate(['name' => 'Role2']);
        
        if(!empty($permissions[1]))
            $role2->givePermissionTo($permissions[1]);
        
        if(!empty($permissions[2]))
            $role2->givePermissionTo($permissions[2]);
        
        $role3 = Role::firstOrCreate(['name' => 'Role3']);
        if(!empty($permissions[3]))
            $role3->givePermissionTo($permissions[3]);
        
        $role3 = Role::firstOrCreate(['name' => 'Role4']);
            
    }
}

