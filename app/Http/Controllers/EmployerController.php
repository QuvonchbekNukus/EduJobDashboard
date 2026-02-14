<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployerController extends Controller
{
    public function index(): View
    {
        $employers = Employer::query()
            ->with(['user', 'region'])
            ->orderBy('id')
            ->paginate(12);

        return view('employers.index', compact('employers'));
    }

    public function create(): View
    {
        $users = User::query()
            ->whereDoesntHave('employer')
            ->orderBy('name')
            ->orderBy('username')
            ->get();
        $regions = Region::query()->orderBy('name')->get();

        return view('employers.create', compact('users', 'regions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:employers,user_id'],
            'org_name' => ['nullable', 'string', 'max:255'],
            'org_type' => ['nullable', Rule::in(['learning_center', 'school', 'kindergarden'])],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'adress' => ['nullable', 'string', 'max:255'],
            'org_contact' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Employer::create([
            'user_id' => $validated['user_id'],
            'org_name' => $validated['org_name'] ?? null,
            'org_type' => $validated['org_type'] ?? null,
            'region_id' => $validated['region_id'],
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'adress' => $validated['adress'] ?? null,
            'org_contact' => $validated['org_contact'] ?? null,
            'is_verified' => (bool) ($validated['is_verified'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('employers.index')->with('status', 'Employer created.');
    }

    public function edit(Employer $employer): View
    {
        $users = User::query()
            ->where(function ($query) use ($employer) {
                $query->whereDoesntHave('employer')
                    ->orWhereKey($employer->user_id);
            })
            ->orderBy('name')
            ->orderBy('username')
            ->get();
        $regions = Region::query()->orderBy('name')->get();

        return view('employers.edit', compact('employer', 'users', 'regions'));
    }

    public function update(Request $request, Employer $employer): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('employers', 'user_id')->ignore($employer->id),
            ],
            'org_name' => ['nullable', 'string', 'max:255'],
            'org_type' => ['nullable', Rule::in(['learning_center', 'school', 'kindergarden'])],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'adress' => ['nullable', 'string', 'max:255'],
            'org_contact' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $employer->update([
            'user_id' => $validated['user_id'],
            'org_name' => $validated['org_name'] ?? null,
            'org_type' => $validated['org_type'] ?? null,
            'region_id' => $validated['region_id'],
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'adress' => $validated['adress'] ?? null,
            'org_contact' => $validated['org_contact'] ?? null,
            'is_verified' => (bool) ($validated['is_verified'] ?? false),
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('employers.index')->with('status', 'Employer updated.');
    }

    public function destroy(Employer $employer): RedirectResponse
    {
        $employer->delete();

        return redirect()->route('employers.index')->with('status', 'Employer deleted.');
    }
}
