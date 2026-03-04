<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employer;
use App\Models\Region;
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

        $user = $request->user();
        $canManageView = (bool) $user?->can('vacancies.manage.view');
        $canViewOwn = (bool) $user?->can('employer.vacancies.view_own');
        $canPublicView = (bool) $user?->can('vacancies.view');

        abort_if(! $canManageView && ! $canViewOwn && ! $canPublicView, 403);

        $ownEmployerId = null;
        $vacanciesQuery = Vacancy::query()->with(['region', 'category', 'employer', 'subject']);

        if ($canManageView) {
            // Superadmin/admin can view all vacancies.
        } elseif ($canViewOwn) {
            $ownEmployerId = $this->resolveOwnEmployerId($request);
            $vacanciesQuery->where('employer_id', $ownEmployerId);
        } else {
            $vacanciesQuery->where('status', 'published');
            $status = 'published';
        }

        $vacanciesQuery->when($search !== '', function (Builder $query) use ($search) {
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
        });

        if (($canManageView || $canViewOwn) && $status !== 'all') {
            $vacanciesQuery->where('status', $status);
        }

        $vacancies = $vacanciesQuery
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $statsQuery = Vacancy::query();
        if ($canManageView) {
            // all
        } elseif ($canViewOwn && $ownEmployerId) {
            $statsQuery->where('employer_id', $ownEmployerId);
        } else {
            $statsQuery->where('status', 'published');
        }

        $canCreateVacancy = (bool) $user?->can('vacancies.manage.create')
            || (bool) $user?->can('employer.vacancies.create_own');
        $canUpdateVacancy = (bool) $user?->can('vacancies.manage.update')
            || (bool) $user?->can('employer.vacancies.update_own');
        $canDeleteVacancy = (bool) $user?->can('vacancies.manage.delete')
            || (bool) $user?->can('employer.vacancies.delete_own');

        return view('vacancies.index', [
            'vacancies' => $vacancies,
            'search' => $search,
            'status' => $status,
            'statusOptions' => Vacancy::STATUSES,
            'totalVacancies' => (clone $statsQuery)->count(),
            'publishedVacancies' => (clone $statsQuery)->where('status', 'published')->count(),
            'pendingVacancies' => (clone $statsQuery)->where('status', 'pending')->count(),
            'archivedVacancies' => (clone $statsQuery)->where('status', 'archived')->count(),
            'canCreateVacancy' => $canCreateVacancy,
            'canUpdateVacancy' => $canUpdateVacancy,
            'canDeleteVacancy' => $canDeleteVacancy,
            'canFilterByStatus' => $canManageView || $canViewOwn,
        ]);
    }

    public function create(Request $request): View
    {
        $canManageCreate = (bool) $request->user()?->can('vacancies.manage.create');

        if ($canManageCreate) {
            return view('vacancies.create', $this->formData());
        }

        abort_unless((bool) $request->user()?->can('employer.vacancies.create_own'), 403);

        $employerId = $this->resolveOwnEmployerId($request);

        return view('vacancies.create', $this->formData($employerId, false));
    }

    public function store(Request $request): RedirectResponse
    {
        $canManageCreate = (bool) $request->user()?->can('vacancies.manage.create');
        $canOwnCreate = (bool) $request->user()?->can('employer.vacancies.create_own');

        abort_if(! $canManageCreate && ! $canOwnCreate, 403);

        $validated = $this->validateVacancy($request, $canManageCreate);

        if (! $canManageCreate) {
            $validated['employer_id'] = $this->resolveOwnEmployerId($request);
        }

        Vacancy::create($this->payload($validated));

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya yaratildi.');
    }

    public function edit(Request $request, Vacancy $vacancy): View
    {
        $canManageUpdate = (bool) $request->user()?->can('vacancies.manage.update');

        if ($canManageUpdate) {
            return view('vacancies.edit', array_merge(
                $this->formData(),
                ['vacancy' => $vacancy],
            ));
        }

        $this->authorizeOwnVacancy($request, $vacancy, 'employer.vacancies.update_own');

        return view('vacancies.edit', array_merge(
            $this->formData((int) $vacancy->employer_id, false),
            ['vacancy' => $vacancy],
        ));
    }

    public function update(Request $request, Vacancy $vacancy): RedirectResponse
    {
        $canManageUpdate = (bool) $request->user()?->can('vacancies.manage.update');

        if (! $canManageUpdate) {
            $this->authorizeOwnVacancy($request, $vacancy, 'employer.vacancies.update_own');
        }

        $validated = $this->validateVacancy($request, $canManageUpdate);

        if (! $canManageUpdate) {
            $validated['employer_id'] = (int) $vacancy->employer_id;
        }

        $vacancy->update($this->payload($validated, $vacancy));

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya yangilandi.');
    }

    public function destroy(Request $request, Vacancy $vacancy): RedirectResponse
    {
        $canManageDelete = (bool) $request->user()?->can('vacancies.manage.delete');

        if (! $canManageDelete) {
            $this->authorizeOwnVacancy($request, $vacancy, 'employer.vacancies.delete_own');
        }

        $vacancy->delete();

        return redirect()->route('vacancies.index')->with('status', 'Vakansiya o`chirildi.');
    }

    protected function formData(?int $lockedEmployerId = null, bool $canManageStatus = true): array
    {
        $employers = Employer::query()
            ->when($lockedEmployerId !== null, function (Builder $query) use ($lockedEmployerId) {
                $query->where('id', $lockedEmployerId);
            })
            ->orderBy('org_name')
            ->orderBy('id')
            ->get();

        return [
            'regions' => Region::query()->orderBy('name')->get(),
            'categories' => Category::query()->orderBy('name')->get(),
            'employers' => $employers,
            'subjects' => Subject::query()->orderBy('label')->orderBy('name')->get(),
            'statusOptions' => Vacancy::STATUSES,
            'workFormatOptions' => Vacancy::WORK_FORMATS,
            'lockedEmployerId' => $lockedEmployerId,
            'canManageStatus' => $canManageStatus,
        ];
    }

    protected function validateVacancy(Request $request, bool $canManage): array
    {
        $rules = [
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
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
            'benefits' => ['nullable', 'string'],
        ];

        if ($canManage) {
            $rules['employer_id'] = ['required', 'integer', 'exists:employers,id'];
            $rules['status'] = ['nullable', Rule::in(Vacancy::STATUSES)];
            $rules['published_at'] = ['nullable', 'date'];
        }

        $validated = $request->validate($rules);

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

    protected function payload(array $validated, ?Vacancy $vacancy = null): array
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

    protected function authorizeOwnVacancy(Request $request, Vacancy $vacancy, string $permission): void
    {
        abort_unless((bool) $request->user()?->can($permission), 403);

        $ownEmployerId = $this->resolveOwnEmployerId($request);

        abort_if((int) $vacancy->employer_id !== $ownEmployerId, 403);
    }

    protected function resolveOwnEmployerId(Request $request): int
    {
        $employerId = (int) ($request->user()?->employer?->id ?? 0);

        abort_if($employerId === 0, 403, 'Employer profile topilmadi.');

        return $employerId;
    }
}
