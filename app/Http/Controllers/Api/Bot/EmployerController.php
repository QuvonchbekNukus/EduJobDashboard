<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Employer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployerController extends BotCrudController
{
    private const ORG_TYPES = ['learning_center', 'school', 'kindergarden'];

    public function index(Request $request): JsonResponse
    {
        $employers = Employer::query()
            ->with(['user.role', 'region'])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($employers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules($request));

        $employer = Employer::create($this->payload($validated));

        return $this->success($this->loadRelations($employer), 201);
    }

    public function show(Employer $employer): JsonResponse
    {
        return $this->success($this->loadRelations($employer));
    }

    public function update(Request $request, Employer $employer): JsonResponse
    {
        $validated = $request->validate($this->rules($request, $employer));

        $employer->update($this->payload($validated, $employer));

        return $this->success($this->loadRelations($employer));
    }

    public function upsert(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules($request));

        $employer = Employer::query()->where('user_id', $validated['user_id'])->first();

        if ($employer) {
            $employer->update($this->payload($validated, $employer));
        } else {
            $employer = Employer::create($this->payload($validated));
        }

        return $this->success($this->loadRelations($employer));
    }

    public function destroy(Employer $employer): JsonResponse
    {
        $employer->delete();

        return $this->deleted();
    }

    private function rules(Request $request, ?Employer $employer = null): array
    {
        $orgType = (string) $request->input('org_type', '');
        if ($orgType === 'kindergarten') {
            $request->merge(['org_type' => 'kindergarden']);
        }

        if ($request->filled('address') && ! $request->filled('adress')) {
            $request->merge(['adress' => $request->input('address')]);
        }

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('employers', 'user_id')->ignore($employer?->id),
            ],
            'org_name' => ['nullable', 'string', 'max:255'],
            'org_type' => ['nullable', Rule::in(self::ORG_TYPES)],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'adress' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'org_contact' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    private function payload(array $validated, ?Employer $employer = null): array
    {
        return [
            'user_id' => $validated['user_id'],
            'org_name' => array_key_exists('org_name', $validated) ? $validated['org_name'] : $employer?->org_name,
            'org_type' => array_key_exists('org_type', $validated) ? $validated['org_type'] : $employer?->org_type,
            'region_id' => $validated['region_id'],
            'city' => array_key_exists('city', $validated) ? $validated['city'] : $employer?->city,
            'district' => array_key_exists('district', $validated) ? $validated['district'] : $employer?->district,
            'adress' => array_key_exists('adress', $validated) ? $validated['adress'] : $employer?->adress,
            'org_contact' => array_key_exists('org_contact', $validated) ? $validated['org_contact'] : $employer?->org_contact,
            'is_verified' => array_key_exists('is_verified', $validated)
                ? (bool) $validated['is_verified']
                : ($employer?->is_verified ?? false),
            'is_active' => array_key_exists('is_active', $validated)
                ? (bool) $validated['is_active']
                : ($employer?->is_active ?? true),
        ];
    }

    private function loadRelations(Employer $employer): Employer
    {
        return $employer->load(['user.role', 'region']);
    }
}
