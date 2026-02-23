<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Region;
use App\Models\Role;
use App\Models\Seeker;
use App\Models\SeekersType;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class FakeProfilesSeeder extends Seeder
{
    /**
     * Seed fake users, employers and seekers.
     */
    public function run(): void
    {
        $faker = fake();

        $regionIds = Region::query()->pluck('id')->values();
        $seekersTypeIds = SeekersType::query()->pluck('id')->values();
        $subjectIds = Subject::query()->pluck('id')->values();

        if ($regionIds->isEmpty() || $seekersTypeIds->isEmpty() || $subjectIds->isEmpty()) {
            return;
        }

        $employerRole = Role::query()->where('name', 'employer')->first();
        $seekerRole = Role::query()->where('name', 'seeker')->first();
        $fallbackRoleId = (int) (Role::query()->value('id') ?? 1);

        $orgTypes = ['learning_center', 'school', 'kindergarden'];
        $workFormats = ['online', 'offline', 'gibrid'];

        for ($i = 1; $i <= 15; $i++) {
            $employerUser = User::updateOrCreate(
                ['username' => "employer_fake_{$i}"],
                [
                    'name' => $faker->firstName(),
                    'lastname' => $faker->lastName(),
                    'telegram_id' => 9100000000 + $i,
                    'phone' => '+99890'.str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                    'role_id' => $employerRole?->id ?? $fallbackRoleId,
                    'password' => 'password',
                ]
            );

            if ($employerRole) {
                $employerUser->syncRoles([$employerRole]);
            }

            Employer::updateOrCreate(
                ['user_id' => $employerUser->id],
                [
                    'org_name' => $faker->company().' Academy',
                    'org_type' => $faker->randomElement($orgTypes),
                    'region_id' => (int) $regionIds->random(),
                    'city' => $faker->city(),
                    'district' => $faker->streetName(),
                    'adress' => $faker->address(),
                    'org_contact' => $faker->phoneNumber(),
                    'is_verified' => $faker->boolean(70),
                    'is_active' => true,
                ]
            );
        }

        for ($i = 1; $i <= 15; $i++) {
            $seekerUser = User::updateOrCreate(
                ['username' => "seeker_fake_{$i}"],
                [
                    'name' => $faker->firstName(),
                    'lastname' => $faker->lastName(),
                    'telegram_id' => 9200000000 + $i,
                    'phone' => '+99891'.str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                    'role_id' => $seekerRole?->id ?? $fallbackRoleId,
                    'password' => 'password',
                ]
            );

            if ($seekerRole) {
                $seekerUser->syncRoles([$seekerRole]);
            }

            Seeker::updateOrCreate(
                ['user_id' => $seekerUser->id],
                [
                    'region_id' => (int) $regionIds->random(),
                    'seekertype_id' => (int) $seekersTypeIds->random(),
                    'subject_id' => (int) $subjectIds->random(),
                    'experience' => $faker->numberBetween(0, 10).' yil',
                    'salary_min' => $faker->numberBetween(2_000_000, 12_000_000),
                    'work_format' => $faker->randomElement($workFormats),
                    'about_me' => $faker->sentence(12),
                    'cv_file_path' => null,
                ]
            );
        }
    }
}
