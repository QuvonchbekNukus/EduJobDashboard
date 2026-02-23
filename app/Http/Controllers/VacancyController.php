<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employer;
use App\Models\Region;
use App\Models\SeekersType;
use App\Models\Subject;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VacancyController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', 'all');

        $vacancies = Vacancy::query()
            ->with(['region', 'category', 'employer', 'seekerType', 'subject'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $innerQuery) use ($search) {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('district', 'like', "%{$search}%")
                        ->orWhere('contact_phone', 'like', "%{$search}%")
                        ->orWhereHas('employer', function (Builder $employerQuery) use ($search) {
                            $employerQuery->where('org_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== 'all', function (Builder $query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('vacancies.index', [
            'vacancies' => $vacancies,
            'search' => $search,
            'status' => $status,
            'statusOptions' => Vacancy::STATUSES,
            'totalVacancies' => Vacancy::count(),
            'publishedVacancies' => Vacancy::query()->where('status', 'published')->count(),
            'pendingVacancies' => Vacancy::query()->where('status', 'pending')->count(),
            'archivedVacancies' => Vacancy::query()->where('status', 'archived')->count(),
        ]);
    }

    public function create(): View
    {
        return view('vacancies.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateVacancy($request);

        Vacancy::create($this->payload($validated));

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya yaratildi.');
    }

    public function edit(Vacancy $vacancy): View
    {
        return view('vacancies.edit', array_merge(
            $this->formData(),
            ['vacancy' => $vacancy],
        ));
    }

    public function update(Request $request, Vacancy $vacancy): RedirectResponse
    {
        $validated = $this->validateVacancy($request);

        $vacancy->update($this->payload($validated));

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya yangilandi.');
    }

    public function destroy(Vacancy $vacancy): RedirectResponse
    {
        $vacancy->delete();

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya o`chirildi.');
    }

    protected function formData(): array
    {
        return [
            'regions' => Region::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
            'employers' => Employer::query()->orderBy('org_name')->orderBy('id')->get(),
            'seekersTypes' => SeekersType::query()->orderBy('label')->orderBy('name')->get(),
            'subjects' => Subject::query()->orderBy('label')->orderBy('name')->get(),
            'statusOptions' => Vacancy::STATUSES,
            'workFormatOptions' => Vacancy::WORK_FORMATS,
        ];
    }

    protected function validateVacancy(Request $request): array
    {
        $validated = $request->validate([
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'employer_id' => ['required', 'integer', 'exists:employers,id'],
            'seeker_type_id' => ['required', 'integer', 'exists:seekers_types,id'],
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
                'salary_to' => 'Maosh gacha qiymati maosh dan qiymatidan kichik bo`lmasligi kerak.',
            ]);
        }

        return $validated;
    }

    protected function payload(array $validated): array
    {
        $status = $validated['status'] ?? 'pending';
        $publishedAt = $validated['published_at'] ?? null;

        if ($status === 'published' && empty($publishedAt)) {
            $publishedAt = now()->toDateString();
        }

        return [
            'region_id' => $validated['region_id'],
            'category_id' => $validated['category_id'],
            'employer_id' => $validated['employer_id'],
            'seeker_type_id' => $validated['seeker_type_id'],
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
}
