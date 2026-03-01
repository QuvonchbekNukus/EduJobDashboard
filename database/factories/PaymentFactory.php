<?php

namespace Database\Factories;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Payment>
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => function (): int {
                $plan = Plan::query()
                    ->where('type', Plan::TYPE_SUBSCRIPTION)
                    ->first();

                if (! $plan) {
                    $plan = Plan::query()->firstOrCreate(
                        [
                            'name' => 'Factory Monthly',
                            'type' => Plan::TYPE_SUBSCRIPTION,
                        ],
                        [
                            'price' => 300000,
                            'duration_days' => 30,
                            'is_active' => true,
                        ]
                    );
                }

                return (int) $plan->id;
            },
            'vacancy_id' => null,
            'provider' => PaymentProvider::PAYME->value,
            'amount' => fn (array $attributes): int => (int) (Plan::query()->find($attributes['plan_id'])?->price ?? 100000),
            'status' => PaymentStatus::PENDING->value,
            'provider_invoice_id' => fake()->unique()->bothify('inv_##??##??'),
            'paid_at' => null,
            'meta' => null,
        ];
    }
}
