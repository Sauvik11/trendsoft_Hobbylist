<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\HobbyController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// List users
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show']);
Route::post('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
// Create a user
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy']);
// Edit a user
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

// Delete a user
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

