<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'english', 'label' => 'English', 'is_active' => true],
            ['name' => 'math', 'label' => 'Math', 'is_active' => true],
            ['name' => 'it', 'label' => 'IT', 'is_active' => true],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['name' => $subject['name']],
                [
                    'label' => $subject['label'],
                    'is_active' => $subject['is_active'],
                ]
            );
        }
    }
}
