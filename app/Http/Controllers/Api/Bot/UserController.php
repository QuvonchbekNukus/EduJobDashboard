<?php

namespace App\Http\Controllers\Api\Bot;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'telegram_id' => ['required', 'integer'],
            'name' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'role_id' => ['nullable', 'integer'],
        ]);

        $user = User::where('telegram_id', $validated['telegram_id'])->first();

        if ($user) {
            $update = collect($validated)
                ->except('telegram_id')
                ->filter(fn ($value) => $value !== null)
                ->all();

            $user->fill($update)->save();
        } else {
            $user = User::create([
                'telegram_id' => $validated['telegram_id'],
                'name' => $validated['name'] ?? null,
                'lastname' => $validated['lastname'] ?? null,
                'username' => $validated['username'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'role_id' => $validated['role_id'] ?? null,
                'password' => Str::random(32),
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'user' => $user,
        ]);
    }
    public function test(Request $request)
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Test endpoint is working!',
        ]);
    }
}
