<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Vacancy;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $publishedVacancies = Vacancy::query()->where('status', 'published')->count();
        $pendingVacancies = Vacancy::query()->where('status', 'pending')->count();
        $verifiedEmployers = Employer::query()->where('is_verified', true)->count();
        $archivedVacancies = Vacancy::query()->where('status', 'archived')->count();

        $recentVacancies = Vacancy::query()
            ->with(['employer', 'region'])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'publishedVacancies',
            'pendingVacancies',
            'verifiedEmployers',
            'archivedVacancies',
            'recentVacancies',
        ));
    }
}
