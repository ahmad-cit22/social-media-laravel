<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// auth routes

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register-form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

Route::get('/profile', [LoginController::class, 'profile'])->name('profile');
Route::post('/update-profile', [LoginController::class, 'updateProfile'])->name('update-profile');
Route::get('/change-password', [LoginController::class, 'changePassword'])->name('change-password');
Route::post('/change-password', [LoginController::class, 'updatePassword']);
Route::get('/delete-user/{id}', [LoginController::class, 'deleteUser'])->name('delete-user');
