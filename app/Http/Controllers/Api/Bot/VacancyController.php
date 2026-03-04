<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VacancyController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $query = Vacancy::query()
            ->with(['region', 'category', 'employer.user', 'subject', 'post'])
            ->orderByDesc('id');

        $status = (string) $request->query('status', '');
        if ($status !== '' && in_array($status, Vacancy::STATUSES, true)) {
            $query->where('status', $status);
        }

        $employerId = (int) $request->query('employer_id', 0);
        if ($employerId > 0) {
            $query->where('employer_id', $employerId);
        }

        $vacancies = $query->paginate($this->perPage($request));

        return $this->paginated($vacancies);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateVacancy($request);

        $vacancy = Vacancy::create($this->payload($validated));

        return $this->success($this->loadRelations($vacancy), 201);
    }

    public function show(Vacancy $vacancy): JsonResponse
    {
        return $this->success($this->loadRelations($vacancy));
    }

    public function update(Request $request, Vacancy $vacancy): JsonResponse
    {
        $validated = $this->validateVacancy($request);

        $vacancy->update($this->payload($validated, $vacancy));

        return $this->success($this->loadRelations($vacancy));
    }

    public function destroy(Vacancy $vacancy): JsonResponse
    {
        $vacancy->delete();

        return $this->deleted();
    }

    private function validateVacancy(Request $request): array
    {
        $validated = $request->validate([
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'employer_id' => ['required', 'integer', 'exists:employers,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'salary_from' => ['nullable', 'integer', 'min:0'],
            'salary_to' => ['nullable', 'integer', 'min:0'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'work_format' => ['required', Rule::in(Vacancy::WORK_FORMATS)],
            'requirements' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_username' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(Vacancy::STATUSES)],
            'published_at' => ['nullable', 'date'],
            'benefits' => ['nullable', 'string'],
        ]);

        if (
            isset($validated['salary_from'], $validated['salary_to']) &&
            $validated['salary_to'] < $validated['salary_from']
        ) {
            throw ValidationException::withMessages([
                'salary_to' => 'salary_to salary_from dan kichik bo`lishi mumkin emas.',
            ]);
        }

        return $validated;
    }

    private function payload(array $validated, ?Vacancy $vacancy = null): array
    {
        $status = $validated['status'] ?? ($vacancy?->status ?? 'pending');
        $publishedAt = $validated['published_at'] ?? ($vacancy?->published_at?->format('Y-m-d'));

        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = now()->toDateString();
        }

        return [
            'region_id' => $validated['region_id'],
            'category_id' => $validated['category_id'],
            'employer_id' => $validated['employer_id'],
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'salary_from' => $validated['salary_from'] ?? null,
            'salary_to' => $validated['salary_to'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'work_format' => $validated['work_format'],
            'requirements' => $validated['requirements'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_username' => $validated['contact_username'] ?? null,
            'status' => $status,
            'published_at' => $publishedAt,
            'benefits' => $validated['benefits'] ?? null,
        ];
    }

    private function loadRelations(Vacancy $vacancy): Vacancy
    {
        return $vacancy->load(['region', 'category', 'employer.user', 'subject', 'post']);
    }
}
