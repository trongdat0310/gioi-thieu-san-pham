<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\SupperUser;
use App\Models\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $start_date = Carbon::now();
        $end_date = $start_date->copy()->addYears();
        $password = Hash::make('12345678');

        $admin = User::create([
            'user_name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '0336864520',
            'password' => $password,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        SupperUser::create([
            'user_id' => $admin->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $organization = Organization::create([
            'org_code' => 'VNT',
            'org_name' => 'VNEXT',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $admin->id,
            'last_updated_by' => $admin->id,
        ]);

        $vnext = User::create([
            'org_id' => $organization->id,
            'user_name' => 'vnext',
            'email' => 'vnext@gmail.com',
            'phone' => '0336864520',
            'password' => $password,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $admin->id,
            'last_updated_by' => $admin->id,
        ]);

        $role = Role::create([
            'org_id' => $organization->id,
            'role_code' => 'ADM',
            'role_name' => 'Admin',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $vnext->id,
            'last_updated_by' => $vnext->id,
        ]);

        UserRole::create([
            'org_id' => $organization->id,
            'user_id' => $vnext->id,
            'role_id' => $role->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $vnext->id,
            'last_updated_by' => $vnext->id,
        ]);

        $permission = Permission::create([
            'org_id' => $organization->id,
            'permission_code' => 'PMS',
            'permission_name' => 'Permission',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $vnext->id,
            'last_updated_by' => $vnext->id,
        ]);

        RolePermission::create([
            'org_id' => $organization->id,
            'role_id' => $role->id,
            'permission_id' => $permission->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_by' => $vnext->id,
            'last_updated_by' => $vnext->id,
        ]);
    }
}
