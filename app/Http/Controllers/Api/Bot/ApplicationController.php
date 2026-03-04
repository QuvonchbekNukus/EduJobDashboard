<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $query = Application::query()
            ->with(['vacancy.region', 'vacancy.category', 'vacancy.employer', 'vacancy.subject', 'seeker.user'])
            ->orderByDesc('id');

        $vacancyId = (int) $request->query('vacancy_id', 0);
        if ($vacancyId > 0) {
            $query->where('vacancy_id', $vacancyId);
        }

        $seekerId = (int) $request->query('seeker_id', 0);
        if ($seekerId > 0) {
            $query->where('seeker_id', $seekerId);
        }

        $status = (string) $request->query('status', '');
        if ($status !== '' && in_array($status, Application::STATUSES, true)) {
            $query->where('status', $status);
        }

        $applications = $query->paginate($this->perPage($request));

        return $this->paginated($applications);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $this->ensureUniquePair($validated);

        $application = Application::create($this->payload($validated));

        return $this->success($this->loadRelations($application), 201);
    }

    public function show(Application $application): JsonResponse
    {
        return $this->success($this->loadRelations($application));
    }

    public function update(Request $request, Application $application): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $this->ensureUniquePair($validated, $application);

        $application->update($this->payload($validated, $application));

        return $this->success($this->loadRelations($application));
    }

    public function destroy(Application $application): JsonResponse
    {
        $application->delete();

        return $this->deleted();
    }

    private function rules(): array
    {
        return [
            'vacancy_id' => ['required', 'integer', 'exists:vacancies,id'],
            'seeker_id' => ['required', 'integer', 'exists:seekers,id'],
            'status' => ['nullable', Rule::in(Application::STATUSES)],
        ];
    }

    private function ensureUniquePair(array $validated, ?Application $application = null): void
    {
        $query = Application::query()
            ->where('vacancy_id', $validated['vacancy_id'])
            ->where('seeker_id', $validated['seeker_id']);

        if ($application) {
            $query->whereKeyNot($application->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'seeker_id' => 'Bu seeker ushbu vacancy ga allaqachon ariza yuborgan.',
            ]);
        }
    }

    private function payload(array $validated, ?Application $application = null): array
    {
        return [
            'vacancy_id' => $validated['vacancy_id'],
            'seeker_id' => $validated['seeker_id'],
            'status' => $validated['status'] ?? ($application?->status ?? 'sent'),
        ];
    }

    private function loadRelations(Application $application): Application
    {
        return $application->load(['vacancy.region', 'vacancy.category', 'vacancy.employer', 'vacancy.subject', 'seeker.user']);
    }
}
