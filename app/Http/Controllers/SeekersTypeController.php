<?php

namespace App\Http\Controllers;

use App\Models\SeekersType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SeekersTypeController extends Controller
{
    public function index(): View
    {
        $seekersTypes = SeekersType::query()->orderBy('id')->paginate(12);

        return view('seekers_types.index', compact('seekersTypes'));
    }

    public function create(): View
    {
        return view('seekers_types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:seekers_types,name'],
            'label' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        SeekersType::create([
            'name' => $validated['name'],
            'label' => $validated['label'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('seekers-types.index')->with('status', 'Seekers type created.');
    }

    public function edit(SeekersType $seekersType): View
    {
        return view('seekers_types.edit', compact('seekersType'));
    }

    public function update(Request $request, SeekersType $seekersType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('seekers_types', 'name')->ignore($seekersType->id),
            ],
            'label' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $seekersType->update([
            'name' => $validated['name'],
            'label' => $validated['label'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('seekers-types.index')->with('status', 'Seekers type updated.');
    }

    public function destroy(SeekersType $seekersType): RedirectResponse
    {
        $seekersType->delete();

        return redirect()->route('seekers-types.index')->with('status', 'Seekers type deleted.');
    }
}
