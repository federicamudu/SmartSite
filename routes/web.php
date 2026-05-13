<?php

use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\DocumentDownloadController;
use App\Http\Controllers\Api\DocumentRevisionController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// -- ROTTE DOCUMENTI (Dashboard e Dettaglio) --
Route::get('/dashboard', [DocumentController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/documents/create', [DocumentController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('documents.create');


Route::post('/documents', [DocumentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('documents.store');

Route::get('/documents/{document}', [DocumentController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('documents.show');

// -- ROTTE REVISIONI E FILE --
Route::post('/documents/{document}/revisions', [DocumentRevisionController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('revisions.store');

Route::get('/revisions/{revision}/download', [DocumentDownloadController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('revisions.download');

Route::get('/revisions/{revision}/preview', [DocumentDownloadController::class, 'preview'])
    ->middleware(['auth', 'verified'])
    ->name('revisions.preview');

Route::patch('/revisions/{revision}/approve', [DocumentRevisionController::class, 'approve'])
    ->middleware(['auth', 'verified'])
    ->name('revisions.approve');

Route::patch('/revisions/{revision}/reject', [DocumentRevisionController::class, 'reject'])
    ->middleware(['auth', 'verified'])
    ->name('revisions.reject');

// -- ROTTE AUDIT LOG --
Route::get('/audit', [AuditController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('audit.index');

Route::get('/audit/export-pdf', [AuditController::class, 'exportPdf'])
    ->middleware(['auth', 'verified'])
    ->name('audit.export');

// -- ROTTE PROFILO UTENTE (Default Breeze) --
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';