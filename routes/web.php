<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\SampleCategoryController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
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
//Login
Route::get('/login', [DashboardController::class, 'showLoginForm'])->name('showLoginForm');
Route::post('/login', [DashboardController::class, 'login'])->name('login');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Users
Route::get('/users', [UserController::class, 'index'])->name('users');
Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');

// Trips
Route::get('/trips', [TripController::class, 'index'])->name('trips');

// Destinations
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations');
Route::post('/destinations', [DestinationController::class, 'store'])->name('destination.store');
Route::put('/destinations/{id}', [DestinationController::class, 'update'])->name('destination.update');
Route::delete('/destinations/{id}', [DestinationController::class, 'destroy'])->name('destination.destroy');

// Currencies
Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies');
Route::post('/currencies', [CurrencyController::class, 'store'])->name('currency.store');
Route::put('/currencies/{id}', [CurrencyController::class, 'update'])->name('currency.update');
Route::delete('/currencies/{id}', [CurrencyController::class, 'destroy'])->name('currency.destroy');

// Sample Categories
Route::get('/categories', [SampleCategoryController::class, 'index'])->name('sample');
Route::get('/categories/create', [SampleCategoryController::class, 'create'])->name('sample.create');
Route::post('/categories', [SampleCategoryController::class, 'store'])->name('sample.store');

//Config
Route::get('/config', [ConfigController::class, 'index'])->name('config');
Route::put('/config-update/{id}', [ConfigController::class, 'update'])->name('config.update');

//Admin 
Route::get('/admins', [AdminController::class, 'index'])->name('admins');
Route::put('/admins-update/{id}', [AdminController::class, 'update'])->name('admin.update');

