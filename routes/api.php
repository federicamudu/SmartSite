<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\AuthController;

// ROTTA PUBBLICA (Chiunque può provare a fare login)
Route::post('/login', [AuthController::class, 'login']);


// ROTTE PROTETTE (Serve il Token Sanctum!)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents', [DocumentController::class, 'store']);
    
});