<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyAnswerController;
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
    Route::post('/organizations/active', [OrganizationController::class, 'setActive'])->name('organizations.active');

    // Organization members
    Route::post('/organizations/{organization}/members', [OrganizationController::class, 'storeMember'])->name('organizations.members.store');
    Route::delete('/organizations/{organization}/members/{user}', [OrganizationController::class, 'destroyMember'])->name('organizations.members.destroy');

    // Surveys
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::patch('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    Route::post('/surveys/{survey}/share', [SurveyController::class, 'generatePublicLink'])->name('surveys.share');
    
    // Survey questions
    Route::get('/surveys/{survey}/questions/create', [SurveyController::class, 'createQuestion'])->name('surveys.questions.create');
    Route::post('/surveys/{survey}/questions', [SurveyController::class, 'storeQuestion'])->name('surveys.questions.store');
});

Route::get('/survey/{token}', [SurveyController::class, 'publicShow'])->name('surveys.public');
Route::post('/surveys/{survey}/answers', [SurveyAnswerController::class, 'store'])->name('surveys.answers.store');

require __DIR__.'/auth.php';
