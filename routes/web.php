<?php

use App\Http\Controllers\Api\DocumentDownloadController;
use App\Http\Controllers\Api\DocumentRevisionController;
use App\Http\Controllers\ProfileController;
use App\Models\ActionLog;
use App\Models\Document;
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

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    
    // 1. Pesco i documenti e le loro revisioni dal Database
    $documents = Document::with('revisions')->latest()->get();

    // 2. Passo i documenti alla pagina Vue
    return Inertia::render('Dashboard', [
        'documents' => $documents
    ]);
    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/documents/{document}', function (Document $document) {
    // Carico il documento con tutte le sue revisioni (ordinate dalla più recente)
    $document->load(['revisions' => function ($query) {
        $query->latest();
    }]);

    return Inertia::render('Documents/Show', [
        'document' => $document
    ]);
})->middleware(['auth', 'verified'])->name('documents.show');

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/audit', function () {
    $logs = ActionLog::with('user')->latest()->get();

    return Inertia::render('Audit/Index', [
        'logs' => $logs
    ]);
})->middleware(['auth', 'verified'])->name('audit.index');

require __DIR__.'/auth.php';
