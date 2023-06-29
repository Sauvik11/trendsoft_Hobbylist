<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('admin/home', [App\Http\Controllers\UserController::class, 'index'])->name('admin.home');
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');

Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'index'])->name('users.edit');
Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'index'])->name('users.update');
Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'index'])->name('users.destroy');
