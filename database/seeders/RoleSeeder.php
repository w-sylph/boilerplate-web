<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Users\Admin;
use App\Models\Roles\Role;
use App\Models\Permissions\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
        ];

        foreach ($roles as $role) {
            $role['guard_name'] = 'admin';
            $role = Role::updateOrCreate($role);
            $role->syncPermissions(Permission::all());
        }

        $admin = Admin::first();

        $admin->assignRole(Role::first());
    }
}
