<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BotTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = env('BOT_API_TOKEN');
        if ($expected) {
            $provided = $request->header('X-BOT-TOKEN')
                ?? $request->header('X-Bot-Token')
                ?? $request->bearerToken()
                ?? $request->query('token');

            if (!$provided || !hash_equals($expected, $provided)) {
                return response()->json(['message' => 'Unauthorized. Invalid BOT_API_TOKEN.'], 401);
            }
        }

        return $next($request);
    }
}
