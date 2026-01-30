<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ChannelController extends Controller
{
    public function index(): View
    {
        $channels = Channel::query()
            ->with('region')
            ->orderBy('id')
            ->paginate(12);

        return view('channels.index', compact('channels'));
    }

    public function create(): View
    {
        $regions = Region::query()->orderBy('name')->get();

        return view('channels.create', compact('regions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'tg_chat_id' => ['required', 'integer', 'unique:channels,tg_chat_id'],
            'region_id' => ['required', 'integer', 'exists:regions,id', 'unique:channels,region_id'],
            'type' => ['nullable', Rule::in(['CHANNEL', 'GROUP'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Channel::create([
            'name' => $validated['name'] ?? null,
            'username' => $validated['username'] ?? null,
            'tg_chat_id' => $validated['tg_chat_id'],
            'region_id' => $validated['region_id'],
            'type' => $validated['type'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('channels.index')->with('status', 'Channel created.');
    }

    public function edit(Channel $channel): View
    {
        $regions = Region::query()->orderBy('name')->get();

        return view('channels.edit', compact('channel', 'regions'));
    }

    public function update(Request $request, Channel $channel): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'tg_chat_id' => [
                'required',
                'integer',
                Rule::unique('channels', 'tg_chat_id')->ignore($channel->id),
            ],
            'region_id' => [
                'required',
                'integer',
                'exists:regions,id',
                Rule::unique('channels', 'region_id')->ignore($channel->id),
            ],
            'type' => ['nullable', Rule::in(['CHANNEL', 'GROUP'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $channel->update([
            'name' => $validated['name'] ?? null,
            'username' => $validated['username'] ?? null,
            'tg_chat_id' => $validated['tg_chat_id'],
            'region_id' => $validated['region_id'],
            'type' => $validated['type'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('channels.index')->with('status', 'Channel updated.');
    }

    public function destroy(Channel $channel): RedirectResponse
    {
        $channel->delete();

        return redirect()->route('channels.index')->with('status', 'Channel deleted.');
    }
}