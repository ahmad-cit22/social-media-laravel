<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

// auth routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register-form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login-form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::any('/logout', [LoginController::class, 'logout'])->name('logout');

// profile routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('edit-profile');
Route::post('/update-profile', [ProfileController::class, 'update'])->name('update-profile');

// posts routes
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
