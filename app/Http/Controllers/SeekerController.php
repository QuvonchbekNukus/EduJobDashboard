<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Seeker;
use App\Models\SeekersType;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SeekerController extends Controller
{
    public function index(): View
    {
        $seekers = Seeker::query()
            ->with(['user', 'region', 'seekersType', 'subject'])
            ->orderBy('id')
            ->paginate(12);

        return view('seekers.index', compact('seekers'));
    }

    public function create(): View
    {
        $users = User::query()
            ->whereDoesntHave('seeker')
            ->orderBy('name')
            ->orderBy('username')
            ->get();
        $regions = Region::query()->orderBy('name')->get();
        $seekersTypes = SeekersType::query()->where('is_active', true)->orderBy('id')->get();
        $subjects = Subject::query()->where('is_active', true)->orderBy('id')->get();

        return view('seekers.create', compact('users', 'regions', 'seekersTypes', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:seekers,user_id'],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'seekertype_id' => ['required', 'integer', 'exists:seekers_types,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'work_format' => ['nullable', Rule::in(['online', 'offline', 'gibrid'])],
            'about_me' => ['nullable', 'string'],
            'cv_file_path' => ['nullable', 'string', 'max:255'],
        ]);

        Seeker::create([
            'user_id' => $validated['user_id'],
            'region_id' => $validated['region_id'],
            'seekertype_id' => $validated['seekertype_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'experience' => $validated['experience'] ?? null,
            'salary_min' => $validated['salary_min'] ?? null,
            'work_format' => $validated['work_format'] ?? null,
            'about_me' => $validated['about_me'] ?? null,
            'cv_file_path' => $validated['cv_file_path'] ?? null,
        ]);

        return redirect()->route('seekers.index')->with('status', 'Seeker created.');
    }

    public function edit(Seeker $seeker): View
    {
        $users = User::query()
            ->where(function ($query) use ($seeker) {
                $query->whereDoesntHave('seeker')
                    ->orWhereKey($seeker->user_id);
            })
            ->orderBy('name')
            ->orderBy('username')
            ->get();
        $regions = Region::query()->orderBy('name')->get();
        $seekersTypes = SeekersType::query()
            ->where(function ($query) use ($seeker) {
                $query->where('is_active', true)
                    ->orWhereKey($seeker->seekertype_id);
            })
            ->orderBy('id')
            ->get();
        $subjects = Subject::query()
            ->where(function ($query) use ($seeker) {
                $query->where('is_active', true)
                    ->orWhereKey($seeker->subject_id);
            })
            ->orderBy('id')
            ->get();

        return view('seekers.edit', compact('seeker', 'users', 'regions', 'seekersTypes', 'subjects'));
    }

    public function update(Request $request, Seeker $seeker): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('seekers', 'user_id')->ignore($seeker->id),
            ],
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'seekertype_id' => ['required', 'integer', 'exists:seekers_types,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'work_format' => ['nullable', Rule::in(['online', 'offline', 'gibrid'])],
            'about_me' => ['nullable', 'string'],
            'cv_file_path' => ['nullable', 'string', 'max:255'],
        ]);

        $seeker->update([
            'user_id' => $validated['user_id'],
            'region_id' => $validated['region_id'],
            'seekertype_id' => $validated['seekertype_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'experience' => $validated['experience'] ?? null,
            'salary_min' => $validated['salary_min'] ?? null,
            'work_format' => $validated['work_format'] ?? null,
            'about_me' => $validated['about_me'] ?? null,
            'cv_file_path' => $validated['cv_file_path'] ?? null,
        ]);

        return redirect()->route('seekers.index')->with('status', 'Seeker updated.');
    }

    public function destroy(Seeker $seeker): RedirectResponse
    {
        $seeker->delete();

        return redirect()->route('seekers.index')->with('status', 'Seeker deleted.');
    }
}
