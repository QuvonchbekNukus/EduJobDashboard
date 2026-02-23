<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RegionSeeder::class,
            SeekersTypeSeeder::class,
            SubjectSeeder::class,
            FakeProfilesSeeder::class,
        ]);

        $role = Role::where('name', 'superadmin')->first();

        $user = User::updateOrCreate(
            ['username' => 'mrcoder'],
            [
                'name' => 'Mr Coder',
                'lastname' => 'Admin',
                'telegram_id' => 971052304,
                'phone' => '+998900000000',
                'role_id' => $role?->id ?? 1,
                'password' => 'password',
            ]
        );

        if ($role) {
            $user->assignRole($role);
        }
    }
}
