<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $filter = (string) $request->query('filter', 'all');
        if (! in_array($filter, ['all', 'with_permissions', 'without_permissions'], true)) {
            $filter = 'all';
        }

        $rolesQuery = Role::query()
            ->with('permissions')
            ->orderBy('id');

        if ($search !== '') {
            $rolesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('permissions', function ($permissionQuery) use ($search) {
                        $permissionQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($filter === 'with_permissions') {
            $rolesQuery->has('permissions');
        } elseif ($filter === 'without_permissions') {
            $rolesQuery->doesntHave('permissions');
        }

        $roles = $rolesQuery->paginate(12)->withQueryString();

        $totalRoles = Role::query()->count();
        $rolesWithPermissions = Role::query()->has('permissions')->count();
        $rolesWithoutPermissions = Role::query()->doesntHave('permissions')->count();

        return view('roles.index', compact(
            'roles',
            'search',
            'filter',
            'totalRoles',
            'rolesWithPermissions',
            'rolesWithoutPermissions'
        ));
    }

    public function create(): View
    {
        $permissions = Permission::query()
            ->where('guard_name', config('auth.defaults.guard', 'web'))
            ->orderBy('name')
            ->get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => $request->input('name') ?? 'user',
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where('guard_name', config('auth.defaults.guard', 'web')),
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => config('auth.defaults.guard', 'web'),
            'description' => $validated['description'] ?? null,
        ]);

        $permissionNames = $validated['permissions'] ?? [];
        if (! empty($permissionNames)) {
            $permissions = Permission::query()
                ->where('guard_name', config('auth.defaults.guard', 'web'))
                ->whereIn('name', $permissionNames)
                ->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('status', 'Role created.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()
            ->where('guard_name', config('auth.defaults.guard', 'web'))
            ->orderBy('name')
            ->get();
        $rolePermissions = $role->permissions->pluck('name')->all();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
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
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')
                    ->where('guard_name', config('auth.defaults.guard', 'web')),
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
            'guard_name' => $role->guard_name ?: config('auth.defaults.guard', 'web'),
            'description' => $validated['description'] ?? null,
        ]);

        $permissionNames = $validated['permissions'] ?? [];
        $permissions = Permission::query()
            ->where('guard_name', config('auth.defaults.guard', 'web'))
            ->whereIn('name', $permissionNames)
            ->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('status', 'Role updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('status', 'Role deleted.');
    }
}
