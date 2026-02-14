<?php

namespace Database\Seeders;

use App\Models\SeekersType;
use Illuminate\Database\Seeder;

class SeekersTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'teacher', 'label' => 'Teacher', 'is_active' => true],
            ['name' => 'admin', 'label' => 'Admin', 'is_active' => true],
        ];

        foreach ($types as $type) {
            SeekersType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'label' => $type['label'],
                    'is_active' => $type['is_active'],
                ]
            );
        }
    }
}
