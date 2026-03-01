<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Models\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(Request $request): View
    {
        $typeFilter = (string) $request->query('type', 'all');
        $activeFilter = (string) $request->query('active', 'all');

        $plans = Plan::query()
            ->withCount('payments')
            ->when($typeFilter !== 'all', function ($query) use ($typeFilter) {
                $query->where('type', $typeFilter);
            })
            ->when($activeFilter !== 'all', function ($query) use ($activeFilter) {
                $query->where('is_active', $activeFilter === '1');
            })
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('plans.index', [
            'plans' => $plans,
            'typeFilter' => $typeFilter,
            'activeFilter' => $activeFilter,
            'typeOptions' => Plan::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('plans.create', [
            'typeOptions' => Plan::TYPES,
        ]);
    }

    public function store(PlanRequest $request): RedirectResponse
    {
        Plan::create($this->payload($request->validated()));

        return redirect()->route('plans.index')->with('status', 'Plan created.');
    }

    public function edit(Plan $plan): View
    {
        return view('plans.edit', [
            'plan' => $plan,
            'typeOptions' => Plan::TYPES,
        ]);
    }

    public function update(PlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($this->payload($request->validated()));

        return redirect()->route('plans.index')->with('status', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        try {
            $plan->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('plans.index')
                ->withErrors(['delete' => 'Bu plan to`lovlarda ishlatilgan, o`chirib bo`lmaydi.']);
        }

        return redirect()->route('plans.index')->with('status', 'Plan deleted.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function payload(array $validated): array
    {
        $type = (string) $validated['type'];

        return [
            'name' => (string) $validated['name'],
            'type' => $type,
            'price' => (int) $validated['price'],
            'duration_days' => $type === Plan::TYPE_SUBSCRIPTION ? (int) $validated['duration_days'] : null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];
    }
}
