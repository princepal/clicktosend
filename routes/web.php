<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoadboardController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileCombinedController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/combined', [ProfileController::class, 'updateprofile'])->name('profile.update.combined');
    Route::resource('templates', App\Http\Controllers\TemplateController::class);

    // Loadboard routes
    Route::get('/loadboards', [LoadboardController::class, 'index'])->name('loadboards.index');
    Route::get('/user/loadboards', [LoadboardController::class, 'getUserLoadboards'])->name('user.loadboards');
    Route::post('/user/loadboards/attach', [LoadboardController::class, 'attachLoadboard'])->name('user.loadboards.attach');
    Route::delete('/user/loadboards/detach', [LoadboardController::class, 'detachLoadboard'])->name('user.loadboards.detach');

    // Plan routes
    Route::resource('plans', PlanController::class);
    Route::match(['get', 'post'], '/plans/subscribe/{plan}', [App\Http\Controllers\PlanController::class, 'subscribe'])->name('plans.subscribe');
});

require __DIR__ . '/auth.php';
