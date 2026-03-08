<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PhaseModulePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage phase modules',
            'manage phase dashboard',
            'manage phase land',
            'manage phase parties',
            'manage phase sales',
            'manage phase finance',
            'manage phase operations',
            'manage phase communications',
            'manage phase reports',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $superAdminRole = Role::where('name', 'super admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        $ownerRoles = Role::where('name', 'owner')->get();
        foreach ($ownerRoles as $ownerRole) {
            $ownerRole->givePermissionTo($permissions);
        }

        $managerRoles = Role::where('name', 'manager')->get();
        foreach ($managerRoles as $managerRole) {
            $managerRole->givePermissionTo($permissions);
        }

        $firstOwner = User::where('type', 'owner')->first();
        if ($firstOwner) {
            $accountantRole = Role::firstOrCreate(
                ['name' => 'accountant', 'parent_id' => $firstOwner->id],
                ['guard_name' => 'web']
            );
            $salesAgentRole = Role::firstOrCreate(
                ['name' => 'sales agent', 'parent_id' => $firstOwner->id],
                ['guard_name' => 'web']
            );
            $viewerRole = Role::firstOrCreate(
                ['name' => 'viewer', 'parent_id' => $firstOwner->id],
                ['guard_name' => 'web']
            );

            $accountantRole->givePermissionTo([
                'manage phase modules',
                'manage phase dashboard',
                'manage phase finance',
                'manage phase reports',
                'manage phase operations',
            ]);

            $salesAgentRole->givePermissionTo([
                'manage phase modules',
                'manage phase dashboard',
                'manage phase land',
                'manage phase parties',
                'manage phase sales',
                'manage phase communications',
                'manage phase reports',
            ]);

            $viewerRole->givePermissionTo([
                'manage phase modules',
                'manage phase dashboard',
                'manage phase reports',
            ]);
        }
    }
}
