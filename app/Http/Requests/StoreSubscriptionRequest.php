<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
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
            'created_from_payment_id' => ['nullable', 'integer', 'exists:payments,id'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'is_active' => ['nullable', 'boolean'],
            'canceled_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $plan = Plan::query()->find($this->integer('plan_id'));

            if (! $plan) {
                return;
            }

            if ($plan->type !== Plan::TYPE_SUBSCRIPTION) {
                $validator->errors()->add('plan_id', 'Faqat subscription type plan uchun obuna ochish mumkin.');
            }

            if (blank($plan->duration_days) || (int) $plan->duration_days <= 0) {
                $validator->errors()->add('plan_id', 'Subscription plan uchun duration_days musbat bo`lishi kerak.');
            }
        });
    }
}
