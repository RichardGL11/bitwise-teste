<?php

use App\Http\Controllers\User\CreateGithubUserController;
use App\Http\Controllers\User\CreateUserController;
use App\Http\Controllers\User\ShowUserController;
use App\Http\Controllers\User\UpdateUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('users',CreateUserController::class)->name('users.store');
Route::post('github-users', CreateGithubUserController::class)->name('users.github.store');

Route::get('users/email/{user:email}',ShowUserController::class)->name('users.email.show');
Route::get('users/username/{user:username}', ShowUserController::class)->name('users.show');
Route::put('users/{user}', UpdateUserController::class)->name('users.update');

