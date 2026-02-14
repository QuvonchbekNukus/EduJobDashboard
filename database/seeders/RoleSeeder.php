

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
        $roles = [
            1 => ['name' => 'superadmin', 'description' => 'Full access', 'guard_name' => 'web'],
            2 => ['name' => 'seeker', 'description' => 'Job seeker role', 'guard_name' => 'web'],
            3 => ['name' => 'employer', 'description' => 'Employer role', 'guard_name' => 'web'],
            4 => ['name' => 'user', 'description' => 'Default role', 'guard_name' => 'web'],
        ];

        foreach ($roles as $id => $data) {
            Role::updateOrCreate(
                ['id' => $id],
                $data
            );
        }
    }
}
