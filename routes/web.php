<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations', [OrganizationController::class, 'create'])->name('organizations.store');
    Route::patch('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

    // Organization members
    Route::post('/organizations/{organization}/members', [OrganizationController::class, 'storeMember'])->name('organizations.members.store');
    Route::delete('/organizations/{organization}/members/{user}', [OrganizationController::class, 'destroyMember'])->name('organizations.members.destroy');
});

require __DIR__.'/auth.php';
