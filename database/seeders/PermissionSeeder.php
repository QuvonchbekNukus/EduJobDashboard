<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's permissions.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guardName = config('auth.defaults.guard', 'web');

        $permissions = [
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'channels.view',
            'channels.create',
            'channels.update',
            'channels.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $role = Role::where('name', 'superadmin')->first();
        if ($role) {
            $role->syncPermissions(Permission::query()->where('guard_name', $guardName)->get());
        }
    }
}