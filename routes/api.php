<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BreathingExerciseController;
use App\Http\Middleware\RoleMiddleware;

// Routes pour Authentification
Route::prefix('auth')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [UserController::class, 'logout']);

    // Routes pour Users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])->middleware(RoleMiddleware::class.':Administrateur,Super-Administrateur');
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store'])->middleware(RoleMiddleware::class.':Super-Administrateur');
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Routes pour Ressource
    Route::prefix('resources')->group(function() {
        Route::post('/', [ResourceController::class, 'store']);
        Route::put('/{id}', [ResourceController::class, 'update']);
        Route::delete('/{id}', [ResourceController::class, 'destroy']);
    });

    // Routes pour Statistique
    Route::get('/statistics', [StatisticController::class, 'index']);
});