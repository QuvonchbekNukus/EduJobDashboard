<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {
    }

    public function markAsPaid(Payment $payment, array $providerPayload = []): Payment
    {
        return DB::transaction(function () use ($payment, $providerPayload): Payment {
            /** @var \App\Models\Payment $lockedPayment */
            $lockedPayment = Payment::query()
                ->with(['user', 'plan', 'subscription'])
                ->lockForUpdate()
                ->findOrFail($payment->id);

            $lockedPayment->status = PaymentStatus::PAID;
            $lockedPayment->paid_at = $lockedPayment->paid_at ?? now();
            $lockedPayment->meta = $this->mergeMeta($lockedPayment->meta, $providerPayload);
            $lockedPayment->save();

            $plan = $lockedPayment->plan;
            $user = $lockedPayment->user;

            if (
                $plan &&
                $user &&
                $plan->type === Plan::TYPE_SUBSCRIPTION &&
                ! $lockedPayment->subscription
            ) {
                $this->subscriptionService->startSubscription($user, $plan, $lockedPayment);
            }

            return $lockedPayment->refresh();
        });
    }

    public function fail(Payment $payment, ?string $reason = null, array $providerPayload = []): Payment
    {
        if ($reason !== null && $reason !== '') {
            $providerPayload['failure_reason'] = $reason;
        }

        $payment->status = PaymentStatus::FAILED;
        $payment->paid_at = null;
        $payment->meta = $this->mergeMeta($payment->meta, $providerPayload);
        $payment->save();

        return $payment->refresh();
    }

    /**
     * @param  array<mixed>|null  $existingMeta
     * @param  array<mixed>  $payload
     * @return array<mixed>
     */
    protected function mergeMeta(?array $existingMeta, array $payload): array
    {
        $base = is_array($existingMeta) ? $existingMeta : [];

        return array_merge($base, $payload);
    }
}
