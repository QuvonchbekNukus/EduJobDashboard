<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'type' => Plan::TYPE_PER_POST,
                'price' => 20000,
                'duration_days' => null,
                'is_active' => true,
            ],
            [
                'name' => 'VIP',
                'type' => Plan::TYPE_VIP,
                'price' => 50000,
                'duration_days' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Monthly',
                'type' => Plan::TYPE_SUBSCRIPTION,
                'price' => 300000,
                'duration_days' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                [
                    'name' => $plan['name'],
                    'type' => $plan['type'],
                ],
                [
                    'price' => $plan['price'],
                    'duration_days' => $plan['duration_days'],
                    'is_active' => $plan['is_active'],
                ]
            );
        }
    }
}
