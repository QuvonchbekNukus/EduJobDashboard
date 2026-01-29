<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $regions = [
            ['name' => 'Andijon', 'slug' => 'andijon'],
            ['name' => 'Buxoro', 'slug' => 'buxoro'],
            ['name' => 'Farg\'ona', 'slug' => 'fargona'],
            ['name' => 'Jizzax', 'slug' => 'jizzax'],
            ['name' => 'Xorazm', 'slug' => 'xorazm'],
            ['name' => 'Namangan', 'slug' => 'namangan'],
            ['name' => 'Navoiy', 'slug' => 'navoiy'],
            ['name' => 'Qashqadaryo', 'slug' => 'qashqadaryo'],
            ['name' => 'Samarqand', 'slug' => 'samarqand'],
            ['name' => 'Sirdaryo', 'slug' => 'sirdaryo'],
            ['name' => 'Surxondaryo', 'slug' => 'surxondaryo'],
            ['name' => 'Toshkent', 'slug' => 'toshkent'],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['slug' => $region['slug']],
                [
                    'name' => $region['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
