<?php

namespace App\Http\Requests;

use App\Models\Plan;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
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
        $planId = $this->route('plan')?->id ?? $this->route('plan');

        return [
            'name' => [
                'required',
                'string',
                'max:80',
                Rule::unique('plans', 'name')->where(function ($query) {
                    return $query->where('type', strtolower((string) $this->input('type')));
                })->ignore($planId),
            ],
            'type' => ['required', 'string', Rule::in(Plan::TYPES)],
            'price' => ['required', 'integer', 'min:1'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('type')) {
            $this->merge([
                'type' => strtolower((string) $this->input('type')),
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $type = $this->input('type');
            $durationDays = $this->input('duration_days');

            if ($type === Plan::TYPE_SUBSCRIPTION && blank($durationDays)) {
                $validator->errors()->add('duration_days', 'Subscription plan uchun duration_days majburiy.');
            }

            if (in_array($type, [Plan::TYPE_PER_POST, Plan::TYPE_VIP], true) && ! blank($durationDays)) {
                $validator->errors()->add('duration_days', "Per post yoki VIP plan uchun duration_days null bo'lishi kerak.");
            }
        });
    }
}
