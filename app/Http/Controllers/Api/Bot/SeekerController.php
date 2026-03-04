<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Seeker;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SeekerController extends BotCrudController
{
    public function index(Request $request): JsonResponse
    {
        $seekers = Seeker::query()
            ->with(['user.role', 'region', 'seekersType', 'subject'])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($seekers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules($request));

        $seeker = Seeker::create($this->payload($validated));

        return $this->success($this->loadRelations($seeker), 201);
    }

    public function show(Seeker $seeker): JsonResponse
    {
        return $this->success($this->loadRelations($seeker));
    }

    public function update(Request $request, Seeker $seeker): JsonResponse
    {
        $validated = $request->validate($this->rules($request, $seeker));

        $seeker->update($this->payload($validated, $seeker));

        return $this->success($this->loadRelations($seeker));
    }

    public function upsert(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules($request));

        $seeker = Seeker::query()->where('user_id', $validated['user_id'])->first();

        if ($seeker) {
            $seeker->update($this->payload($validated, $seeker));
        } else {
            $seeker = Seeker::create($this->payload($validated));
        }

        return $this->success($this->loadRelations($seeker));
    }

    public function destroy(Seeker $seeker): JsonResponse
    {
        $seeker->delete();

        return $this->deleted();
    }

    private function rules(Request $request, ?Seeker $seeker = null): array
    {
        $workFormat = (string) $request->input('work_format', '');
        if ($workFormat === 'hybrid') {
            $request->merge(['work_format' => 'gibrid']);
        }

        if ($request->filled('seeker_type_id') && ! $request->filled('seekertype_id')) {
            $request->merge(['seekertype_id' => $request->input('seeker_type_id')]);
        }

        if ($request->filled('cv_file_id') && ! $request->filled('cv_file_path')) {
            $request->merge(['cv_file_path' => $request->input('cv_file_id')]);
        }

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('seekers', 'user_id')->ignore($seeker?->id),
            ],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'seekertype_id' => ['required', 'integer', 'exists:seekers_types,id'],
            'seeker_type_id' => ['nullable', 'integer', 'exists:seekers_types,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'work_format' => ['nullable', Rule::in(Vacancy::WORK_FORMATS)],
            'about_me' => ['nullable', 'string'],
            'cv_file_path' => ['nullable', 'string', 'max:255'],
            'cv_file_id' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function payload(array $validated, ?Seeker $seeker = null): array
    {
        return [
            'user_id' => $validated['user_id'],
            'region_id' => $validated['region_id'],
            'seekertype_id' => $validated['seekertype_id'],
            'subject_id' => array_key_exists('subject_id', $validated) ? $validated['subject_id'] : $seeker?->subject_id,
            'experience' => array_key_exists('experience', $validated) ? $validated['experience'] : $seeker?->experience,
            'salary_min' => array_key_exists('salary_min', $validated) ? $validated['salary_min'] : $seeker?->salary_min,
            'work_format' => array_key_exists('work_format', $validated) ? $validated['work_format'] : $seeker?->work_format,
            'about_me' => array_key_exists('about_me', $validated) ? $validated['about_me'] : $seeker?->about_me,
            'cv_file_path' => array_key_exists('cv_file_path', $validated) ? $validated['cv_file_path'] : $seeker?->cv_file_path,
        ];
    }

    private function loadRelations(Seeker $seeker): Seeker
    {
        return $seeker->load(['user.role', 'region', 'seekersType', 'subject']);
    }
}
