<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\LeadNoteController;
use App\Http\Controllers\Api\V1\ReminderController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\SupportController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login-otp', [AuthController::class, 'loginOtp']);
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
    
    Route::get('/pages/about', [PageController::class, 'about']);
    Route::get('/pages/privacy-policy', [PageController::class, 'privacyPolicy']);
    Route::get('/support/info', [SupportController::class, 'info']);

    Route::middleware('auth:sanctum')->group(function () {
        // User Profile
        Route::get('/user', [UserController::class, 'profile']);
        Route::put('/user/profile', [UserController::class, 'updateProfile']);
        
        // Dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/upcoming-followups', [DashboardController::class, 'upcomingFollowups']);
        
        // Leads
        Route::get('/leads', [LeadController::class, 'index']);
        Route::post('/leads', [LeadController::class, 'store']);
        Route::get('/leads/{id}', [LeadController::class, 'show']);
        Route::put('/leads/{id}', [LeadController::class, 'update']);
        Route::patch('/leads/{id}/status', [LeadController::class, 'updateStatus']);
        Route::delete('/leads/{id}', [LeadController::class, 'destroy']);
        
        // Lead Notes
        Route::post('/leads/{id}/notes', [LeadNoteController::class, 'store']);
        Route::put('/notes/{id}', [LeadNoteController::class, 'update']);
        Route::delete('/notes/{id}', [LeadNoteController::class, 'destroy']);
        
        // Reminders
        Route::get('/reminders', [ReminderController::class, 'index']);
        Route::post('/reminders', [ReminderController::class, 'store']);
        Route::patch('/reminders/{id}/status', [ReminderController::class, 'updateStatus']);
        
        // Support Messages
        Route::post('/support/messages', [SupportController::class, 'storeMessage']);
    });
});
