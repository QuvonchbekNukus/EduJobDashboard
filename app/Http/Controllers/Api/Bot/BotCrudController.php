<?php

namespace App\Http\Controllers\Api\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BotCrudController extends Controller
{
    protected function perPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 15);

        return max(1, min($perPage, 100));
    }

    protected function paginated(LengthAwarePaginator $paginator): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    protected function success(mixed $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'data' => $data,
        ], $status);
    }

    protected function deleted(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
