<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Region;
use App\Models\SeekersType;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class LookupController extends BotCrudController
{
    public function regions(): JsonResponse
    {
        $regions = Region::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return $this->success($regions);
    }

    public function seekersTypes(): JsonResponse
    {
        $types = SeekersType::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'name', 'label']);

        return $this->success($types);
    }

    public function subjects(): JsonResponse
    {
        $subjects = Subject::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'name', 'label']);

        return $this->success($subjects);
    }
}
