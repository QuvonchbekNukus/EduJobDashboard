<?php

namespace App\Http\Controllers;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = (string) $request->query('status', 'all');
        $providerFilter = (string) $request->query('provider', 'all');

        $payments = Payment::query()
            ->with(['user', 'plan', 'vacancy'])
            ->when($statusFilter !== 'all', function (Builder $query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when($providerFilter !== 'all', function (Builder $query) use ($providerFilter) {
                $query->where('provider', $providerFilter);
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('payments.index', [
            'payments' => $payments,
            'statusFilter' => $statusFilter,
            'providerFilter' => $providerFilter,
            'statusOptions' => PaymentStatus::values(),
            'providerOptions' => PaymentProvider::values(),
        ]);
    }

    public function create(): View
    {
        return view('payments.create', $this->formData());
    }

    public function store(StorePaymentRequest $request, PaymentService $paymentService): RedirectResponse
    {
        $payload = $this->payload($request->validated());
        $payment = Payment::create($payload);
        $this->applyStatusTransition($payment, (string) $payload['status'], $paymentService, 'admin-store');

        return redirect()->route('payments.index')->with('status', 'Payment created.');
    }

    public function edit(Payment $payment): View
    {
        return view('payments.edit', array_merge(
            $this->formData(),
            ['payment' => $payment],
        ));
    }

    public function update(StorePaymentRequest $request, Payment $payment, PaymentService $paymentService): RedirectResponse
    {
        $payload = $this->payload($request->validated());
        $payment->update($payload);
        $this->applyStatusTransition($payment, (string) $payload['status'], $paymentService, 'admin-update');

        return redirect()->route('payments.index')->with('status', 'Payment updated.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('status', 'Payment deleted.');
    }

    protected function formData(): array
    {
        return [
            'users' => User::query()
                ->orderBy('name')
                ->orderBy('username')
                ->get(['id', 'name', 'username']),
            'plans' => Plan::query()
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name', 'type', 'price', 'duration_days', 'is_active']),
            'vacancies' => Vacancy::query()
                ->orderByDesc('id')
                ->get(['id', 'title', 'status']),
            'providerOptions' => PaymentProvider::values(),
            'statusOptions' => PaymentStatus::values(),
        ];
    }

    protected function payload(array $validated): array
    {
        return [
            'user_id' => (int) $validated['user_id'],
            'plan_id' => (int) $validated['plan_id'],
            'vacancy_id' => $validated['vacancy_id'] ?? null,
            'provider' => (string) $validated['provider'],
            'amount' => (int) $validated['amount'],
            'status' => (string) ($validated['status'] ?? PaymentStatus::PENDING->value),
            'provider_invoice_id' => $validated['provider_invoice_id'] ?? null,
            'paid_at' => $validated['paid_at'] ?? null,
            'meta' => $validated['meta'] ?? null,
        ];
    }

    protected function applyStatusTransition(
        Payment $payment,
        string $targetStatus,
        PaymentService $paymentService,
        string $source
    ): void {
        if ($targetStatus === PaymentStatus::PAID->value) {
            $paymentService->markAsPaid($payment, ['source' => $source]);

            return;
        }

        if ($targetStatus === PaymentStatus::FAILED->value) {
            $paymentService->fail($payment, null, ['source' => $source]);
        }
    }
}
