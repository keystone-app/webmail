<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Placeholder for protected route
Route::middleware('auth')->get('/home', function () {
    return 'Welcome Home';
});

// Frontend routes handled by Svelte (via catch-all or specific)
Route::get('/login', function () {
    return view('app'); // SPA entry point
})->name('login');
