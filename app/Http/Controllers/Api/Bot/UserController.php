<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class UserController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->with(['role', 'seeker.region', 'seeker.seekersType', 'seeker.subject', 'employer.region'])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'name' => $validated['name'] ?? null,
                'lastname' => $validated['lastname'] ?? null,
                'username' => $validated['username'] ?? null,
                'telegram_id' => $validated['telegram_id'],
                'phone' => $validated['phone'] ?? null,
                'role_id' => $validated['role_id'],
                'password' => $validated['password'],
            ]);

            $this->syncUserRole($user, (int) $validated['role_id']);

            return $user;
        });

        return $this->success($this->loadRelations($user), 201);
    }

    public function show(User $user): JsonResponse
    {
        return $this->success($this->loadRelations($user));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate($this->rules($user, false));

        $user = DB::transaction(function () use ($user, $validated): User {
            $payload = [
                'name' => $validated['name'] ?? null,
                'lastname' => $validated['lastname'] ?? null,
                'username' => $validated['username'] ?? null,
                'telegram_id' => $validated['telegram_id'],
                'phone' => $validated['phone'] ?? null,
                'role_id' => $validated['role_id'],
            ];

            if (! empty($validated['password'])) {
                $payload['password'] = $validated['password'];
            }

            $user->update($payload);
            $this->syncUserRole($user, (int) $validated['role_id']);

            return $user;
        });

        return $this->success($this->loadRelations($user));
    }

    public function destroy(User $user): JsonResponse
    {
        DB::transaction(function () use ($user): void {
            $user->seeker()->delete();
            $user->employer()->delete();
            $user->subscriptions()->delete();
            $user->delete();
        });

        return $this->deleted();
    }

    public function upsert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'telegram_id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', Rule::in(['seeker', 'employer'])],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ]);

        $user = User::where('telegram_id', $validated['telegram_id'])->first();
        $defaultRoleId = Role::where('name', 'seeker')->value('id') ?? 2;
        $roleName = $validated['role'] ?? null;

        if ($roleName !== null) {
            $resolvedRoleId = Role::where('name', $roleName)->value('id');
            if (! $resolvedRoleId) {
                throw ValidationException::withMessages([
                    'role' => 'Tanlangan role topilmadi.',
                ]);
            }
            $roleId = $validated['role_id'] ?? $resolvedRoleId;
        } else {
            $roleId = $validated['role_id'] ?? $defaultRoleId;
        }

        if ($user) {
            $update = collect($validated)
                ->except(['telegram_id', 'role'])
                ->filter(fn ($value) => $value !== null)
                ->all();

            if (! empty($roleId)) {
                $update['role_id'] = $roleId;
            }

            $user->fill($update)->save();
        } else {
            $user = User::create([
                'telegram_id' => $validated['telegram_id'],
                'name' => $validated['name'] ?? null,
                'lastname' => $validated['lastname'] ?? null,
                'username' => $validated['username'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'role_id' => $roleId,
                'password' => Str::random(32),
            ]);
        }

        if ($roleName !== null) {
            $role = Role::where('name', $roleName)->first();
        } else {
            $role = ! empty($roleId) ? Role::find($roleId) : null;
        }

        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return $this->success($this->loadRelations($user));
    }

    private function rules(?User $user = null, bool $passwordRequired = true): array
    {
        $passwordRules = $passwordRequired
            ? ['required', Password::defaults()]
            : ['nullable', Password::defaults()];

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'telegram_id' => [
                'required',
                'integer',
                Rule::unique('users', 'telegram_id')->ignore($user?->id),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => $passwordRules,
        ];
    }

    private function syncUserRole(User $user, int $roleId): void
    {
        $role = Role::find($roleId);

        if ($role) {
            $user->syncRoles([$role->name]);
        }
    }

    private function loadRelations(User $user): User
    {
        return $user->load([
            'role',
            'seeker.region',
            'seeker.seekersType',
            'seeker.subject',
            'employer.region',
        ]);
    }
}
