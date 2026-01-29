<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        $regions = Region::query()->orderBy('id')->paginate(12);

        return view('regions.index', compact('regions'));
    }

    public function create(): View
    {
        return view('regions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:regions,slug'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Region::create([
            'name' => $validated['name'] ?? null,
            'slug' => $validated['slug'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('regions.index')->with('status', 'Region created.');
    }

    public function edit(Region $region): View
    {
        return view('regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('regions', 'slug')->ignore($region->id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $region->update([
            'name' => $validated['name'] ?? null,
            'slug' => $validated['slug'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('regions.index')->with('status', 'Region updated.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        $region->delete();

        return redirect()->route('regions.index')->with('status', 'Region deleted.');
    }
}
