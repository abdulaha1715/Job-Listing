<?php

use App\Http\Controllers\JobListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Root Route
Route::get('/', [JobListingController::class, 'home_list'])->name('home');

// Listing Route
Route::resource('listing', JobListingController::class);
// User Data Store
Route::post('/users', [UserController::class, 'store'])->name('users');
// Show Registration Form
Route::get('/register', [UserController::class, 'create'])->name('register');
// Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login');
// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
// User Log In
Route::post('/user/login', [UserController::class, 'authentication'])->name('auth-log');

// Manage Listings
Route::get('/listings/manage', [JobListingController::class, 'manage'])->name('manage');
