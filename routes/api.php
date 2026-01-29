<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Bot\UserController as BotUserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('bot.token')->group(function () {
    Route::post('/bot/users/upsert', [BotUserController::class, 'test']);
});