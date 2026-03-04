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

        $sharedPermissions = [
            'vacancies.view',
            'employers.public.view',
            'plans.view',
            'channels.view',
            'stats.vacancies_total.view',
            'stats.employers_total.view',
            'stats.seekers_total.view',
        ];

        $profilePermissions = [
            'profile.view_own',
            'profile.update_own',
            'profile.delete_own',
        ];

        $userPermissions = [
            'user.account_type.choose_once',
        ];

        $seekerPermissions = [
            'seeker.profile.view_own',
            'seeker.profile.update_own',
            'seeker.profile.delete_own',
            'seeker.applications.view_own',
            'seeker.applications.create',
            'seeker.notifications.application_response',
        ];

        $employerPermissions = [
            'employer.profile.view_own',
            'employer.profile.update_own',
            'employer.profile.delete_own',
            'employer.vacancies.view_own',
            'employer.vacancies.create_own',
            'employer.vacancies.update_own',
            'employer.vacancies.delete_own',
            'employer.applications.view_own_vacancies',
            'employer.subscriptions.view_own',
            'employer.payments.history.view_own',
            'employer.payments.next_due.view_own',
            'employer.notifications.application_new',
            'employer.notifications.payment_due',
        ];

        $superadminExclusivePermissions = [
            'superadmin.analytics.revenue_monthly.view',
            'superadmin.payments.unpaid.view',
        ];

        $adminManagementPermissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'regions.view',
            'regions.create',
            'regions.update',
            'regions.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'channels.create',
            'channels.update',
            'channels.delete',
            'seekers.view',
            'seekers.create',
            'seekers.update',
            'seekers.delete',
            'employers.view',
            'employers.create',
            'employers.update',
            'employers.delete',
            'subjects.view',
            'subjects.create',
            'subjects.update',
            'subjects.delete',
            'seekers_types.view',
            'seekers_types.create',
            'seekers_types.update',
            'seekers_types.delete',
            'plans.manage.view',
            'plans.manage.create',
            'plans.manage.update',
            'plans.manage.delete',
            'payments.manage.view',
            'payments.manage.create',
            'payments.manage.update',
            'payments.manage.delete',
            'vacancies.manage.view',
            'vacancies.manage.create',
            'vacancies.manage.update',
            'vacancies.manage.delete',
        ];

        $allPermissions = collect([
            $sharedPermissions,
            $profilePermissions,
            $userPermissions,
            $seekerPermissions,
            $employerPermissions,
            $superadminExclusivePermissions,
            $adminManagementPermissions,
        ])->flatten()->unique()->values();

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $roles = Role::query()
            ->where('guard_name', $guardName)
            ->whereIn('name', ['superadmin', 'user', 'seeker', 'employer'])
            ->get()
            ->keyBy('name');

        $permissionModels = Permission::query()
            ->where('guard_name', $guardName)
            ->whereIn('name', $allPermissions->all())
            ->get()
            ->keyBy('name');

        if ($roles->has('user')) {
            $roles->get('user')->syncPermissions(
                collect(array_merge($sharedPermissions, $profilePermissions, $userPermissions))
                    ->map(fn (string $name) => $permissionModels->get($name))
                    ->filter()
                    ->values()
            );
        }

        if ($roles->has('seeker')) {
            $roles->get('seeker')->syncPermissions(
                collect(array_merge($sharedPermissions, $profilePermissions, $seekerPermissions))
                    ->map(fn (string $name) => $permissionModels->get($name))
                    ->filter()
                    ->values()
            );
        }

        if ($roles->has('employer')) {
            $roles->get('employer')->syncPermissions(
                collect(array_merge($sharedPermissions, $profilePermissions, $employerPermissions))
                    ->map(fn (string $name) => $permissionModels->get($name))
                    ->filter()
                    ->values()
            );
        }

        if ($roles->has('superadmin')) {
            $roles->get('superadmin')->syncPermissions(
                Permission::query()->where('guard_name', $guardName)->get()
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
