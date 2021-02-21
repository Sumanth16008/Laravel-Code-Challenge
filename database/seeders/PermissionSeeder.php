<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'Permission1']);
        Permission::firstOrCreate(['name' => 'Permission2']);
        Permission::firstOrCreate(['name' => 'Permission3']);
        Permission::firstOrCreate(['name' => 'Permission4']);
    }
}
