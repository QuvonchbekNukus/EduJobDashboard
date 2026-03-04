<?php

namespace App\Http\Controllers\Api\Bot;

use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $typeFilter = (string) $request->query('type', 'all');
        $activeFilter = (string) $request->query('active', 'all');

        $plans = Plan::query()
            ->withCount(['payments', 'subscriptions'])
            ->when($typeFilter !== 'all', function ($query) use ($typeFilter) {
                $query->where('type', $typeFilter);
            })
            ->when($activeFilter !== 'all', function ($query) use ($activeFilter) {
                $query->where('is_active', $activeFilter === '1');
            })
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($plans);
    }

    public function store(PlanRequest $request): JsonResponse
    {
        $plan = Plan::create($this->payload($request->validated()));

        return $this->success($this->loadCounts($plan), 201);
    }

    public function show(Plan $plan): JsonResponse
    {
        return $this->success($this->loadCounts($plan));
    }

    public function update(PlanRequest $request, Plan $plan): JsonResponse
    {
        $plan->update($this->payload($request->validated(), $plan));

        return $this->success($this->loadCounts($plan));
    }

    public function destroy(Plan $plan): JsonResponse
    {
        try {
            $plan->delete();
        } catch (QueryException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bu plan to`lov yoki subscriptionlarda ishlatilgan, o`chirib bo`lmaydi.',
            ], 422);
        }

        return $this->deleted();
    }

    private function payload(array $validated, ?Plan $plan = null): array
    {
        $type = (string) $validated['type'];

        return [
            'name' => (string) $validated['name'],
            'type' => $type,
            'price' => (int) $validated['price'],
            'duration_days' => $type === Plan::TYPE_SUBSCRIPTION ? (int) $validated['duration_days'] : null,
            'is_active' => array_key_exists('is_active', $validated)
                ? (bool) $validated['is_active']
                : ($plan?->is_active ?? true),
        ];
    }

    private function loadCounts(Plan $plan): Plan
    {
        return $plan->loadCount(['payments', 'subscriptions']);
    }
}
