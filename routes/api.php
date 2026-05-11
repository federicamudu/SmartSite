<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentRevisionController;
use App\Http\Controllers\Api\DocumentDownloadController;

// ROTTA PUBBLICA (Chiunque può provare a fare login)
Route::post('/login', [AuthController::class, 'login']);


// ROTTE PROTETTE (Serve il Token Sanctum!)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('role:owner');
    Route::post('/documents/{document}/revisions', [DocumentRevisionController::class, 'store']);
    Route::get('/revisions/{revision}/download', [DocumentDownloadController::class, 'download']);
    Route::patch('/revisions/{revision}/status', [DocumentRevisionController::class, 'updateStatus'])->middleware('role:owner');
});