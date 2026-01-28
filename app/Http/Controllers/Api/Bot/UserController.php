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
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            $update = ['name' => $validated['name']];
            if (!empty($validated['password'])) {
                $update['password'] = $validated['password'];
            }

            $user->fill($update)->save();
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'] ?? Str::random(32),
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'user' => $user,
        ]);
    }
}
