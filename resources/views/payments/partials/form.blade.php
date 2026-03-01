@php
    $paymentModel = $payment ?? null;

    $currentProvider = old('provider');
    if ($currentProvider === null && $paymentModel) {
        $currentProvider = is_object($paymentModel->provider) ? $paymentModel->provider->value : $paymentModel->provider;
    }

    $currentStatus = old('status');
    if ($currentStatus === null && $paymentModel) {
        $currentStatus = is_object($paymentModel->status) ? $paymentModel->status->value : $paymentModel->status;
    }
    $currentStatus ??= \App\Enums\PaymentStatus::PENDING->value;

    $currentPaidAt = old('paid_at');
    if ($currentPaidAt === null && $paymentModel?->paid_at) {
        $currentPaidAt = $paymentModel->paid_at->format('Y-m-d\TH:i');
    }

    $currentMeta = old('meta');
    if (is_array($currentMeta)) {
        $currentMeta = json_encode($currentMeta, JSON_PRETTY_PRINT);
    } elseif ($currentMeta === null && $paymentModel && is_array($paymentModel->meta)) {
        $currentMeta = json_encode($paymentModel->meta, JSON_PRETTY_PRINT);
    }
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="mb-3">
        <label for="user_id" class="form-label">Foydalanuvchi</label>
        <select id="user_id" name="user_id" required class="form-select @error('user_id') is-invalid @enderror">
            <option value="" disabled {{ old('user_id', $paymentModel?->user_id) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ (string) old('user_id', $paymentModel?->user_id) === (string) $user->id ? 'selected' : '' }}>
                    {{ $user->name ?? $user->username ?? ('User #' . $user->id) }} ({{ $user->username ?? '-' }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="plan_id" class="form-label">Plan</label>
        <select id="plan_id" name="plan_id" required class="form-select @error('plan_id') is-invalid @enderror">
            <option value="" disabled {{ old('plan_id', $paymentModel?->plan_id) ? '' : 'selected' }}>Tanlang</option>
            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" {{ (string) old('plan_id', $paymentModel?->plan_id) === (string) $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }} [{{ $plan->type }}] - {{ number_format((int) $plan->price, 0, '.', ' ') }} so`m
                    @if (! $plan->is_active)
                        (inactive)
                    @endif
                </option>
            @endforeach
        </select>
        @error('plan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="vacancy_id" class="form-label">Vacancy (faqat per_post)</label>
        <select id="vacancy_id" name="vacancy_id" class="form-select @error('vacancy_id') is-invalid @enderror">
            <option value="" {{ old('vacancy_id', $paymentModel?->vacancy_id) ? '' : 'selected' }}>Tanlanmagan</option>
            @foreach ($vacancies as $vacancy)
                <option value="{{ $vacancy->id }}" {{ (string) old('vacancy_id', $paymentModel?->vacancy_id) === (string) $vacancy->id ? 'selected' : '' }}>
                    #{{ $vacancy->id }} - {{ $vacancy->title }} [{{ $vacancy->status ?? '-' }}]
                </option>
            @endforeach
        </select>
        @error('vacancy_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="provider" class="form-label">Provider</label>
        <select id="provider" name="provider" required class="form-select @error('provider') is-invalid @enderror">
            <option value="" disabled {{ $currentProvider ? '' : 'selected' }}>Tanlang</option>
            @foreach ($providerOptions as $provider)
                <option value="{{ $provider }}" {{ (string) $currentProvider === (string) $provider ? 'selected' : '' }}>
                    {{ strtoupper($provider) }}
                </option>
            @endforeach
        </select>
        @error('provider')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="amount" class="form-label">Amount (UZS)</label>
        <input
            id="amount"
            name="amount"
            type="number"
            min="1"
            required
            value="{{ old('amount', $paymentModel?->amount) }}"
            class="form-control @error('amount') is-invalid @enderror"
        >
        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach ($statusOptions as $statusOption)
                <option value="{{ $statusOption }}" {{ (string) $currentStatus === (string) $statusOption ? 'selected' : '' }}>
                    {{ strtoupper($statusOption) }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="provider_invoice_id" class="form-label">Provider invoice ID</label>
        <input
            id="provider_invoice_id"
            name="provider_invoice_id"
            type="text"
            maxlength="120"
            value="{{ old('provider_invoice_id', $paymentModel?->provider_invoice_id) }}"
            class="form-control @error('provider_invoice_id') is-invalid @enderror"
        >
        @error('provider_invoice_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="paid_at" class="form-label">Paid at</label>
        <input
            id="paid_at"
            name="paid_at"
            type="datetime-local"
            value="{{ $currentPaidAt }}"
            class="form-control @error('paid_at') is-invalid @enderror"
        >
        @error('paid_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="meta" class="form-label">Meta (JSON object)</label>
        <textarea
            id="meta"
            name="meta"
            rows="4"
            class="form-control @error('meta') is-invalid @enderror"
            placeholder='{"provider_response":{"transaction":"123"}}'
        >{{ $currentMeta }}</textarea>
        @error('meta')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-brand">{{ $submitLabel }}</button>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-ink">Bekor qilish</a>
    </div>
</form>
