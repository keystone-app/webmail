<?php

use App\Http\Controllers\Api\EmailController;
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
});
