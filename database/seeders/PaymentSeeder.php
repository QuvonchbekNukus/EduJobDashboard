<?php

namespace Database\Seeders;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->first();
        if (! $user) {
            return;
        }

        $subscriptionPlan = Plan::query()->where('type', Plan::TYPE_SUBSCRIPTION)->first();
        $vipPlan = Plan::query()->where('type', Plan::TYPE_VIP)->first();
        $perPostPlan = Plan::query()->where('type', Plan::TYPE_PER_POST)->first();
        $vacancy = Vacancy::query()->first();

        if ($subscriptionPlan) {
            Payment::updateOrCreate(
                [
                    'provider' => PaymentProvider::P2P->value,
                    'provider_invoice_id' => 'seed-subscription-001',
                ],
                [
                    'user_id' => $user->id,
                    'plan_id' => $subscriptionPlan->id,
                    'vacancy_id' => null,
                    'amount' => (int) $subscriptionPlan->price,
                    'status' => PaymentStatus::PENDING->value,
                    'paid_at' => null,
                    'meta' => ['seed' => true, 'kind' => 'subscription'],
                ]
            );
        }

        if ($vipPlan) {
            Payment::updateOrCreate(
                [
                    'provider' => PaymentProvider::PAYME->value,
                    'provider_invoice_id' => 'seed-vip-001',
                ],
                [
                    'user_id' => $user->id,
                    'plan_id' => $vipPlan->id,
                    'vacancy_id' => null,
                    'amount' => (int) $vipPlan->price,
                    'status' => PaymentStatus::PAID->value,
                    'paid_at' => now(),
                    'meta' => ['seed' => true, 'kind' => 'vip'],
                ]
            );
        }

        if ($perPostPlan && $vacancy) {
            Payment::updateOrCreate(
                [
                    'provider' => PaymentProvider::CLICK->value,
                    'provider_invoice_id' => 'seed-per-post-001',
                ],
                [
                    'user_id' => $user->id,
                    'plan_id' => $perPostPlan->id,
                    'vacancy_id' => $vacancy->id,
                    'amount' => (int) $perPostPlan->price,
                    'status' => PaymentStatus::PENDING->value,
                    'paid_at' => null,
                    'meta' => ['seed' => true, 'kind' => 'per_post'],
                ]
            );
        }
    }
}
