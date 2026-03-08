<?php

namespace Database\Seeders;

use App\Models\Maintainer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DefaultDataUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default All Permission
        $allPermission = [
            [
                'name' => 'manage user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete user',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete role',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete contact',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage support',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create support',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit support',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete support',
                'guard_name' => 'web',
            ],
            [
                'name' => 'reply support',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete note',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'delete logged history',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'buy pricing packages',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage coupon history',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete coupon history',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage pricing transation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage account settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage password settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage general settings',
                'guard_name' => 'web',

            ],
            [
                'name' => 'manage company settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage email settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage payment settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage seo settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage google recaptcha settings',
                'guard_name' => 'web',
            ],


            [
                'name' => 'manage property',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create property',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit property',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete property',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show property',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage unit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create unit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit unit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete unit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage tenant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create tenant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit tenant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete tenant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show tenant',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage contract',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create contract',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit contract',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete contract',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show contract',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show invoice',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage maintainer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create maintainer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit maintainer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete maintainer',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage maintenance request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create maintenance request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit maintenance request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete maintenance request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show maintenance request',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete invoice type',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create invoice payment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete invoice payment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage expense',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create expense',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit expense',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete expense',
                'guard_name' => 'web',
            ],
            [
                'name' => 'show expense',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage types',
                'guard_name' => 'web',
            ],
            [
                'name' => 'create types',
                'guard_name' => 'web',
            ],
            [
                'name' => 'edit types',
                'guard_name' => 'web',
            ],
            [
                'name' => 'delete types',
                'guard_name' => 'web',
            ],

        ];
        Permission::insert($allPermission);

        // Default Super Admin Role
        $superAdminRoleData=  [
            'name' => 'super admin',
            'parent_id' => 0,
        ];
        $systemSuperAdminRole = Role::create($superAdminRoleData);
        $systemSuperAdminPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage support'],
            ['name' => 'create support'],
            ['name' => 'edit support'],
            ['name' => 'delete support'],
            ['name' => 'reply support'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage pricing packages'],
            ['name' => 'create pricing packages'],
            ['name' => 'edit pricing packages'],
            ['name' => 'delete pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage coupon'],
            ['name' => 'create coupon'],
            ['name' => 'edit coupon'],
            ['name' => 'delete coupon'],
            ['name' => 'manage coupon history'],
            ['name' => 'delete coupon history'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage email settings'],
            ['name' => 'manage payment settings'],
            ['name' => 'manage seo settings'],
            ['name' => 'manage google recaptcha settings'],


        ];
        $systemSuperAdminRole->givePermissionTo($systemSuperAdminPermission);
        // Default Super Admin
        $superAdminData=     [
            'first_name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'super admin',
            'lang' => 'english',
            'profile' => 'avatar.png',
        ];
        $systemSuperAdmin = User::create($superAdminData);
        $systemSuperAdmin->assignRole($systemSuperAdminRole);

        // Default Owner Role
        $ownerRoleData= [
            'name' => 'owner',
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwnerRole = Role::create($ownerRoleData);

        // Default Owner All Permissions
        $systemOwnerPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage role'],
            ['name' => 'create role'],
            ['name' => 'edit role'],
            ['name' => 'delete role'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage support'],
            ['name' => 'create support'],
            ['name' => 'edit support'],
            ['name' => 'delete support'],
            ['name' => 'reply support'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],
            ['name' => 'manage logged history'],
            ['name' => 'delete logged history'],
            ['name' => 'manage pricing packages'],
            ['name' => 'buy pricing packages'],
            ['name' => 'manage pricing transation'],
            ['name' => 'manage email settings'],
            ['name' => 'manage account settings'],
            ['name' => 'manage password settings'],
            ['name' => 'manage general settings'],
            ['name' => 'manage company settings'],
            ['name' => 'manage payment settings'],
            ['name' => 'manage property'],
            ['name' => 'create property'],
            ['name' => 'edit property'],
            ['name' => 'delete property'],
            ['name' => 'show property'],
            ['name' => 'manage unit'],
            ['name' => 'create unit'],
            ['name' => 'edit unit'],
            ['name' => 'delete unit'],
            ['name' => 'manage tenant'],
            ['name' => 'create tenant'],
            ['name' => 'edit tenant'],
            ['name' => 'delete tenant'],
            ['name' => 'show tenant'],
            ['name' => 'manage contract'],
            ['name' => 'create contract'],
            ['name' => 'edit contract'],
            ['name' => 'delete contract'],
            ['name' => 'show contract'],
            ['name' => 'manage invoice'],
            ['name' => 'create invoice'],
            ['name' => 'edit invoice'],
            ['name' => 'delete invoice'],
            ['name' => 'show invoice'],
            ['name' => 'manage maintainer'],
            ['name' => 'create maintainer'],
            ['name' => 'edit maintainer'],
            ['name' => 'delete maintainer'],
            ['name' => 'manage maintenance request'],
            ['name' => 'create maintenance request'],
            ['name' => 'edit maintenance request'],
            ['name' => 'delete maintenance request'],
            ['name' => 'show maintenance request'],
            ['name' => 'delete invoice type'],
            ['name' => 'create invoice payment'],
            ['name' => 'delete invoice payment'],
            ['name' => 'manage expense'],
            ['name' => 'create expense'],
            ['name' => 'edit expense'],
            ['name' => 'delete expense'],
            ['name' => 'show expense'],
            ['name' => 'manage types'],
            ['name' => 'create types'],
            ['name' => 'edit types'],
            ['name' => 'delete types'],

        ];
        $systemOwnerRole->givePermissionTo($systemOwnerPermission);

        // Default Owner Create
        $ownerData=    [
            'first_name' => 'Owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'owner',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 1,
            'parent_id' => $systemSuperAdmin->id,
        ];
        $systemOwner = User::create($ownerData);
        // Default Owner Role Assign
        $systemOwner->assignRole($systemOwnerRole);


        // Default Owner Role
        $managerRoleData=  [
            'name' => 'manager',
            'parent_id' => $systemOwner->id,
        ];
        $systemManagerRole = Role::create($managerRoleData);
        // Default Manager All Permissions
        $systemManagerPermission = [
            ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage contact'],
            ['name' => 'create contact'],
            ['name' => 'edit contact'],
            ['name' => 'delete contact'],
            ['name' => 'manage support'],
            ['name' => 'create support'],
            ['name' => 'edit support'],
            ['name' => 'delete support'],
            ['name' => 'reply support'],
            ['name' => 'manage note'],
            ['name' => 'create note'],
            ['name' => 'edit note'],
            ['name' => 'delete note'],

            ['name' => 'manage property'],
            ['name' => 'create property'],
            ['name' => 'edit property'],
            ['name' => 'delete property'],
            ['name' => 'show property'],
            ['name' => 'manage unit'],
            ['name' => 'create unit'],
            ['name' => 'edit unit'],
            ['name' => 'delete unit'],
            ['name' => 'manage tenant'],
            ['name' => 'create tenant'],
            ['name' => 'edit tenant'],
            ['name' => 'delete tenant'],
            ['name' => 'show tenant'],
            ['name' => 'manage contract'],
            ['name' => 'create contract'],
            ['name' => 'edit contract'],
            ['name' => 'delete contract'],
            ['name' => 'show contract'],
            ['name' => 'manage invoice'],
            ['name' => 'create invoice'],
            ['name' => 'edit invoice'],
            ['name' => 'delete invoice'],
            ['name' => 'show invoice'],
            ['name' => 'manage maintainer'],
            ['name' => 'create maintainer'],
            ['name' => 'edit maintainer'],
            ['name' => 'delete maintainer'],
            ['name' => 'manage maintenance request'],
            ['name' => 'create maintenance request'],
            ['name' => 'edit maintenance request'],
            ['name' => 'delete maintenance request'],
            ['name' => 'show maintenance request'],
            ['name' => 'delete invoice type'],
            ['name' => 'create invoice payment'],
            ['name' => 'delete invoice payment'],
            ['name' => 'manage expense'],
            ['name' => 'create expense'],
            ['name' => 'edit expense'],
            ['name' => 'delete expense'],
            ['name' => 'show expense'],
            ['name' => 'manage types'],
            ['name' => 'create types'],
            ['name' => 'edit types'],
            ['name' => 'delete types'],
        ];
        $systemManagerRole->givePermissionTo($systemManagerPermission);
        // Default Manager Create
        $managerData=   [
            'first_name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'manager',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'subscription' => 0,
            'parent_id' => $systemOwner->id,
        ];
        $systemManager = User::create($managerData);
        // Default Manager Role Assign
        $systemManager->assignRole($systemManagerRole);


        // Default Tenant role
        $systemTenantRole=defaultTenantCreate($systemOwner->id);
        // Default Tenant
        $tenantData=  [
            'first_name' => 'Tenant',
            'email' => 'tenant@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'tenant',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'parent_id' => $systemOwner->id,
        ];
        $systemTenant = User::create($tenantData);
        $systemTenantDetail = new Tenant();
        $systemTenantDetail->user_id = $systemTenant->id;
        $systemTenantDetail->parent_id = $systemOwner->id;
        $systemTenantDetail->save();
        // Default tenant role assign
        $systemTenant->assignRole($systemTenantRole);

        // Default Maintainer role
        $systemTenantRole=defaultMaintainerCreate($systemOwner->id);
        // Default admin
        $maintainerData=[
            'first_name' => 'Maintainer',
            'email' => 'maintainer@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'maintainer',
            'lang' => 'english',
            'profile' => 'avatar.png',
            'parent_id' => $systemOwner->id,
        ];
        $systemMaintainer = User::create($maintainerData);
        $systemMaintainerDetail = new Maintainer();
        $systemMaintainerDetail->user_id = $systemMaintainer->id;
        $systemMaintainerDetail->parent_id = $systemOwner->id;
        $systemMaintainerDetail->save();

        // Default admin role assign
        $systemMaintainer->assignRole($systemTenantRole);


        // Subscription default data
        $subscriptionData=[
            'title' => 'Basic',
            'package_amount' => 0,
            'interval' => 'Unlimited',
            'user_limit' => 10,
            'property_limit' => 10,
            'tenant_limit' =>1000,
            'enabled_logged_history' => 1 ,
        ];
        \App\Models\Subscription::create($subscriptionData );
    }
}
