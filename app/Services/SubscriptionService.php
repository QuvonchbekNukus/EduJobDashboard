<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionService
{
    /**
     * Start a new subscription for the user and close any previous active one.
     *
     * @throws \RuntimeException
     */
    public function startSubscription(User $user, Plan $plan, ?Payment $payment = null): Subscription
    {
        if ($plan->type !== Plan::TYPE_SUBSCRIPTION) {
            throw new RuntimeException('Only subscription plans can create subscriptions.');
        }

        $durationDays = (int) ($plan->duration_days ?? 0);
        if ($durationDays <= 0) {
            throw new RuntimeException('Subscription plan must define a positive duration_days value.');
        }

        return DB::transaction(function () use ($user, $plan, $payment, $durationDays): Subscription {
            $activeSubscriptions = Subscription::query()
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get();

            foreach ($activeSubscriptions as $subscription) {
                $subscription->deactivate();
            }

            $startAt = now();

            return Subscription::query()->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'start_at' => $startAt,
                'end_at' => $startAt->copy()->addDays($durationDays),
                'is_active' => true,
                'canceled_at' => null,
                'created_from_payment_id' => $payment?->id,
            ]);
        });
    }

    public function deactivate(User $user, ?string $reason = null): void
    {
        DB::transaction(function () use ($user): void {
            Subscription::query()
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->each
                ->deactivate();
        });

        // Optional: persist $reason to audit log if needed in future.
        if ($reason !== null && $reason !== '') {
            // no-op for now
        }
    }
}
