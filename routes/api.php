<?php

use App\Http\Controllers\Api\Bot\ApplicationController as BotApplicationController;
use App\Http\Controllers\Api\Bot\EmployerController as BotEmployerController;
use App\Http\Controllers\Api\Bot\LookupController as BotLookupController;
use App\Http\Controllers\Api\Bot\PlanController as BotPlanController;
use App\Http\Controllers\Api\Bot\SeekerController as BotSeekerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Bot\UserController as BotUserController;
use App\Http\Controllers\Api\Bot\VacancyController as BotVacancyController;
use App\Http\Controllers\Api\Bot\VacancyPostController as BotVacancyPostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('bot.token')->prefix('bot')->name('bot.')->group(function () {
    Route::post('/users/upsert', [BotUserController::class, 'upsert'])->name('users.upsert');
    Route::post('/seekers/upsert', [BotSeekerController::class, 'upsert'])->name('seekers.upsert');
    Route::post('/employers/upsert', [BotEmployerController::class, 'upsert'])->name('employers.upsert');

    Route::get('/regions', [BotLookupController::class, 'regions'])->name('regions.index');
    Route::get('/seekers-types', [BotLookupController::class, 'seekersTypes'])->name('seekers-types.index');
    Route::get('/subjects', [BotLookupController::class, 'subjects'])->name('subjects.index');

    Route::apiResource('/users', BotUserController::class);
    Route::apiResource('/employers', BotEmployerController::class);
    Route::apiResource('/seekers', BotSeekerController::class);
    Route::apiResource('/vacancies', BotVacancyController::class);
    Route::apiResource('/applications', BotApplicationController::class);
    Route::apiResource('/vacancy_posts', BotVacancyPostController::class)->parameters([
        'vacancy_posts' => 'vacancyPost',
    ]);
    Route::apiResource('/plans', BotPlanController::class);
});
