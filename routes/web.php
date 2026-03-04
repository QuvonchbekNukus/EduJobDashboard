<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeekerController;
use App\Http\Controllers\SeekersTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('roles', RoleController::class)
        ->only(['index'])
        ->middleware('permission:roles.view');
    Route::resource('roles', RoleController::class)
        ->only(['create', 'store'])
        ->middleware('permission:roles.create');
    Route::resource('roles', RoleController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:roles.update');
    Route::resource('roles', RoleController::class)
        ->only(['destroy'])
        ->middleware('permission:roles.delete');

    Route::resource('users', UserController::class)
        ->only(['index'])
        ->middleware('permission:users.view');
    Route::resource('users', UserController::class)
        ->only(['create', 'store'])
        ->middleware('permission:users.create');
    Route::resource('users', UserController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:users.update');
    Route::resource('users', UserController::class)
        ->only(['destroy'])
        ->middleware('permission:users.delete');

    Route::resource('regions', RegionController::class)
        ->only(['index'])
        ->middleware('permission:regions.view');
    Route::resource('regions', RegionController::class)
        ->only(['create', 'store'])
        ->middleware('permission:regions.create');
    Route::resource('regions', RegionController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:regions.update');
    Route::resource('regions', RegionController::class)
        ->only(['destroy'])
        ->middleware('permission:regions.delete');

    Route::resource('categories', CategoryController::class)
        ->only(['index'])
        ->middleware('permission:categories.view');
    Route::resource('categories', CategoryController::class)
        ->only(['create', 'store'])
        ->middleware('permission:categories.create');
    Route::resource('categories', CategoryController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:categories.update');
    Route::resource('categories', CategoryController::class)
        ->only(['destroy'])
        ->middleware('permission:categories.delete');

    Route::resource('subjects', SubjectController::class)
        ->only(['index'])
        ->middleware('permission:subjects.view');
    Route::resource('subjects', SubjectController::class)
        ->only(['create', 'store'])
        ->middleware('permission:subjects.create');
    Route::resource('subjects', SubjectController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:subjects.update');
    Route::resource('subjects', SubjectController::class)
        ->only(['destroy'])
        ->middleware('permission:subjects.delete');

    Route::resource('seekers-types', SeekersTypeController::class)
        ->only(['index'])
        ->middleware('permission:seekers_types.view');
    Route::resource('seekers-types', SeekersTypeController::class)
        ->only(['create', 'store'])
        ->middleware('permission:seekers_types.create');
    Route::resource('seekers-types', SeekersTypeController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:seekers_types.update');
    Route::resource('seekers-types', SeekersTypeController::class)
        ->only(['destroy'])
        ->middleware('permission:seekers_types.delete');

    Route::resource('seekers', SeekerController::class)
        ->only(['index'])
        ->middleware('permission:seekers.view');
    Route::resource('seekers', SeekerController::class)
        ->only(['create', 'store'])
        ->middleware('permission:seekers.create');
    Route::resource('seekers', SeekerController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:seekers.update');
    Route::resource('seekers', SeekerController::class)
        ->only(['destroy'])
        ->middleware('permission:seekers.delete');

    Route::resource('employers', EmployerController::class)
        ->only(['index'])
        ->middleware('permission:employers.public.view|employers.view');
    Route::resource('employers', EmployerController::class)
        ->only(['create', 'store'])
        ->middleware('permission:employers.create');
    Route::resource('employers', EmployerController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:employers.update');
    Route::resource('employers', EmployerController::class)
        ->only(['destroy'])
        ->middleware('permission:employers.delete');

    Route::resource('channels', ChannelController::class)
        ->only(['index'])
        ->middleware('permission:channels.view');
    Route::resource('channels', ChannelController::class)
        ->only(['create', 'store'])
        ->middleware('permission:channels.create');
    Route::resource('channels', ChannelController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:channels.update');
    Route::resource('channels', ChannelController::class)
        ->only(['destroy'])
        ->middleware('permission:channels.delete');

    Route::resource('plans', PlanController::class)
        ->only(['index'])
        ->middleware('permission:plans.view');
    Route::resource('plans', PlanController::class)
        ->only(['create', 'store'])
        ->middleware('permission:plans.manage.create');
    Route::resource('plans', PlanController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:plans.manage.update');
    Route::resource('plans', PlanController::class)
        ->only(['destroy'])
        ->middleware('permission:plans.manage.delete');

    Route::resource('payments', PaymentController::class)
        ->only(['index'])
        ->middleware('permission:payments.manage.view|employer.payments.history.view_own');
    Route::resource('payments', PaymentController::class)
        ->only(['create', 'store'])
        ->middleware('permission:payments.manage.create');
    Route::resource('payments', PaymentController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:payments.manage.update');
    Route::resource('payments', PaymentController::class)
        ->only(['destroy'])
        ->middleware('permission:payments.manage.delete');

    Route::resource('vacancies', VacancyController::class)
        ->only(['index'])
        ->middleware('permission:vacancies.manage.view|vacancies.view|employer.vacancies.view_own');
    Route::resource('vacancies', VacancyController::class)
        ->only(['create', 'store'])
        ->middleware('permission:vacancies.manage.create|employer.vacancies.create_own');
    Route::resource('vacancies', VacancyController::class)
        ->only(['edit', 'update'])
        ->middleware('permission:vacancies.manage.update|employer.vacancies.update_own');
    Route::resource('vacancies', VacancyController::class)
        ->only(['destroy'])
        ->middleware('permission:vacancies.manage.delete|employer.vacancies.delete_own');
});

require __DIR__.'/auth.php';
