<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Region;
use App\Models\Role;
use App\Models\Seeker;
use App\Models\SeekersType;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->with(['role', 'seeker.region', 'seeker.seekersType', 'seeker.subject', 'employer.region'])
            ->orderBy('id')
            ->paginate(12);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::query()->orderBy('name')->get();
        $regions = Region::query()->orderBy('name')->get();
        $seekersTypes = SeekersType::query()->where('is_active', true)->orderBy('id')->get();
        $subjects = Subject::query()->where('is_active', true)->orderBy('id')->get();

        return view('users.create', compact('roles', 'regions', 'seekersTypes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));

        DB::transaction(function () use ($validated): void {
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
            $this->syncAccountType($user, $validated);
        });

        return redirect()->route('users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        $user->load(['seeker.region', 'seeker.seekersType', 'seeker.subject', 'employer.region']);

        $roles = Role::query()->orderBy('name')->get();
        $regions = Region::query()->orderBy('name')->get();
        $seekersTypes = SeekersType::query()
            ->where(function ($query) use ($user) {
                $query->where('is_active', true)
                    ->orWhere('id', $user->seeker?->seekertype_id);
            })
            ->orderBy('id')
            ->get();
        $subjects = Subject::query()
            ->where(function ($query) use ($user) {
                $query->where('is_active', true)
                    ->orWhere('id', $user->seeker?->subject_id);
            })
            ->orderBy('id')
            ->get();

        return view('users.edit', compact('user', 'roles', 'regions', 'seekersTypes', 'subjects'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate($this->rules($request, $user));

        DB::transaction(function () use ($user, $validated): void {
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
            $this->syncAccountType($user, $validated);
        });

        return redirect()->route('users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        DB::transaction(function () use ($user): void {
            $user->seeker()->delete();
            $user->employer()->delete();
            $user->subscriptions()->delete();
            $user->delete();
        });

        return redirect()->route('users.index')->with('status', 'User deleted.');
    }

    private function rules(Request $request, ?User $user = null): array
    {
        $accountType = (string) $request->input('account_type', 'none');

        $passwordRules = $user
            ? ['nullable', 'confirmed', Password::defaults()]
            : ['required', 'confirmed', Password::defaults()];

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
            'account_type' => ['required', Rule::in(['none', 'seeker', 'employer'])],

            'seeker_region_id' => [
                Rule::requiredIf($accountType === 'seeker'),
                'nullable',
                'integer',
                'exists:regions,id',
            ],
            'seekertype_id' => ['nullable', 'integer', 'exists:seekers_types,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'work_format' => ['nullable', Rule::in(['online', 'offline', 'gibrid'])],
            'about_me' => ['nullable', 'string'],
            'cv_file_path' => ['nullable', 'string', 'max:255'],

            'employer_region_id' => [
                Rule::requiredIf($accountType === 'employer'),
                'nullable',
                'integer',
                'exists:regions,id',
            ],
            'org_name' => ['nullable', 'string', 'max:255'],
            'org_type' => ['nullable', Rule::in(['learning_center', 'school', 'kindergarden'])],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'adress' => ['nullable', 'string', 'max:255'],
            'org_contact' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function syncUserRole(User $user, int $roleId): void
    {
        $role = Role::find($roleId);
        if ($role) {
            $user->syncRoles([$role->name]);
        }
    }

    private function syncAccountType(User $user, array $validated): void
    {
        $accountType = $validated['account_type'] ?? 'none';

        if ($accountType === 'seeker') {
            Seeker::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'region_id' => $validated['seeker_region_id'],
                    'seekertype_id' => $validated['seekertype_id'] ?? null,
                    'subject_id' => $validated['subject_id'] ?? null,
                    'experience' => $validated['experience'] ?? null,
                    'salary_min' => $validated['salary_min'] ?? null,
                    'work_format' => $validated['work_format'] ?? null,
                    'about_me' => $validated['about_me'] ?? null,
                    'cv_file_path' => $validated['cv_file_path'] ?? null,
                ]
            );

            $user->employer()->delete();

            return;
        }

        if ($accountType === 'employer') {
            Employer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'org_name' => $validated['org_name'] ?? null,
                    'org_type' => $validated['org_type'] ?? null,
                    'region_id' => $validated['employer_region_id'],
                    'city' => $validated['city'] ?? null,
                    'district' => $validated['district'] ?? null,
                    'adress' => $validated['adress'] ?? null,
                    'org_contact' => $validated['org_contact'] ?? null,
                    'is_verified' => (bool) ($validated['is_verified'] ?? false),
                    'is_active' => (bool) ($validated['is_active'] ?? false),
                ]
            );

            $user->seeker()->delete();

            return;
        }

        $user->seeker()->delete();
        $user->employer()->delete();
    }
}
