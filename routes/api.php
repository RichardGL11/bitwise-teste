<?php

use App\Http\Controllers\User\CreateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('users',CreateUserController::class)->name('users.store');
