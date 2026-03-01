<?php

namespace App\Http\Requests;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    protected bool $metaDecodeFailed = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'vacancy_id' => ['nullable', 'integer', 'exists:vacancies,id'],
            'provider' => ['required', 'string', Rule::in(PaymentProvider::values())],
            'amount' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', Rule::in(PaymentStatus::values())],
            'provider_invoice_id' => ['nullable', 'string', 'max:120'],
            'paid_at' => ['nullable', 'date'],
            'meta' => ['nullable', 'array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('provider')) {
            $this->merge([
                'provider' => strtolower((string) $this->input('provider')),
            ]);
        }

        if ($this->filled('status')) {
            $this->merge([
                'status' => strtolower((string) $this->input('status')),
            ]);
        }

        $meta = $this->input('meta');

        if (is_string($meta)) {
            $meta = trim($meta);

            if ($meta === '') {
                $this->merge(['meta' => null]);

                return;
            }

            $decoded = json_decode($meta, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->merge(['meta' => $decoded]);

                return;
            }

            $this->metaDecodeFailed = true;
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $plan = Plan::query()->find($this->integer('plan_id'));

            if ($plan) {
                $vacancyId = $this->input('vacancy_id');

                if ($plan->type === Plan::TYPE_PER_POST && blank($vacancyId)) {
                    $validator->errors()->add('vacancy_id', 'Per post plan uchun vacancy_id majburiy.');
                }

                if ($plan->type !== Plan::TYPE_PER_POST && ! blank($vacancyId)) {
                    $validator->errors()->add('vacancy_id', 'Faqat per_post plan uchun vacancy_id berilishi mumkin.');
                }
            }

            $status = (string) $this->input('status', PaymentStatus::PENDING->value);
            $paidAt = $this->input('paid_at');

            if ($status === PaymentStatus::PAID->value && blank($paidAt)) {
                $validator->errors()->add('paid_at', 'Status paid bo`lsa paid_at majburiy.');
            }

            if ($status !== PaymentStatus::PAID->value && ! blank($paidAt)) {
                $validator->errors()->add('paid_at', 'Status paid bo`lmaganda paid_at null bo`lishi kerak.');
            }

            if ($this->metaDecodeFailed) {
                $validator->errors()->add('meta', 'Meta valid JSON object bo`lishi kerak.');
            }

            $providerInvoiceId = $this->input('provider_invoice_id');
            $provider = $this->input('provider');

            if (! blank($providerInvoiceId) && ! blank($provider)) {
                $paymentId = null;
                $routePayment = $this->route('payment');

                if ($routePayment instanceof Payment) {
                    $paymentId = $routePayment->id;
                } elseif (is_numeric($routePayment)) {
                    $paymentId = (int) $routePayment;
                }

                $duplicateInvoiceQuery = Payment::query()
                    ->where('provider', (string) $provider)
                    ->where('provider_invoice_id', (string) $providerInvoiceId);

                if ($paymentId !== null) {
                    $duplicateInvoiceQuery->whereKeyNot($paymentId);
                }

                if ($duplicateInvoiceQuery->exists()) {
                    $validator->errors()->add(
                        'provider_invoice_id',
                        'Bu provider uchun ushbu provider_invoice_id allaqachon mavjud.'
                    );
                }
            }
        });
    }
}
