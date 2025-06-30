<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\BreathingExerciseController;
use App\Http\Middleware\RoleMiddleware;

// Routes pour l'Authentification
Route::prefix('auth')->group(function() {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::prefix('informations')->group(function() {
    Route::get('/', [InformationController::class, 'index']);
    Route::get('/{id}', [InformationController::class, 'show']);
});

Route::get('/menus', [MenuController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [UserController::class, 'showMe']);
    Route::post('/auth/logout', [UserController::class, 'logout']);

    // Routes pour les Users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::put('/', [UserController::class, 'updateMe']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::delete('/', [UserController::class, 'destroyMe']);
    });

    // Routes pour les Menus
    Route::prefix('menus')->group(function() {
        Route::post('/', [MenuController::class, 'store']);
        Route::put('/{id}', [MenuController::class, 'update']);
        Route::delete('/{id}', [MenuController::class, 'destroy']);
    });

    // Routes pour les Informations
    Route::prefix('informations')->group(function() {
        Route::post('/', [InformationController::class, 'store']);
        Route::put('/{id}', [InformationController::class, 'update']);
        Route::delete('/{id}', [InformationController::class, 'destroy']);
    });

    // Routes pour les Exercices de respiration
    Route::prefix('breathingExercises')->group(function() {
        Route::get('/', [BreathingExerciseController::class, 'index']);
        Route::post('/', [BreathingExerciseController::class, 'store']);
        Route::put('/{id}', [BreathingExerciseController::class, 'update']);
        Route::delete('/{id}', [BreathingExerciseController::class, 'destroy']);
    });
});