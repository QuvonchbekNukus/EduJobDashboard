<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $guardName = config('auth.defaults.guard', 'web');

        $roles = [
            'superadmin' => 'Full access to all modules and analytics.',
            'seeker' => 'Can manage own seeker profile and applications.',
            'employer' => 'Can manage own employer profile, vacancies and applications.',
            'user' => 'Basic user role before selecting seeker or employer.',
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(
                [
                    'name' => $name,
                    'guard_name' => $guardName,
                ],
                [
                    'description' => $description,
                ]
            );
        }
    }
}
