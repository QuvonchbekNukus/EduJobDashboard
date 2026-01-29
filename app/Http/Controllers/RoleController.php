<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->orderBy('id')->paginate(12);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => $request->input('name') ?? 'user',
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Role::create([
            'name' => $validated['name'],
            'guard_name' => config('auth.defaults.guard', 'web'),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('roles.index')->with('status', 'Role created.');
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $request->merge([
            'name' => $request->input('name') ?? $role->name ?? 'user',
        ]);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role->id),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $role->update([
            'name' => $validated['name'],
            'guard_name' => $role->guard_name ?: config('auth.defaults.guard', 'web'),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('roles.index')->with('status', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('status', 'Role deleted.');
    }
}
