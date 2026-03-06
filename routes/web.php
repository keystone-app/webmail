<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\DraftController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth');

Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/emails', [EmailController::class, 'index']);
    Route::get('/emails/{email}', [EmailController::class, 'show']);
    // Note: attachments.show is needed for proxying inline images/downloads
    Route::get('/attachments/{attachment}', [AttachmentController::class, 'show'])->name('attachments.show');
    
    Route::post('/drafts', [DraftController::class, 'store']);
    Route::put('/drafts/{draft}', [DraftController::class, 'update']);
    
    Route::post('/messages/send', [MessageController::class, 'send']);
});
