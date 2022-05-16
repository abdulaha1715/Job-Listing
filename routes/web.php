<?php

use App\Http\Controllers\JobListingController;
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
Route::get('/', [JobListingController::class, 'home_list']);

// Listing Route
Route::resource('listing', JobListingController::class);
