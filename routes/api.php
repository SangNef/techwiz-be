<?php

use App\Http\Controllers\User\ConfigController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\DestinationController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\LinkController;
use App\Http\Controllers\User\TripController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//User router
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/activate', [UserController::class, 'activateAccount'])->name('user.activate');
Route::get('/user', [UserController::class, 'profile']);
Route::post('/user-update', [UserController::class, 'updateProfile']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::post('/logout', [UserController::class, 'logout']);


//Trip router
Route::get('/trips', [TripController::class, 'index']);
Route::post('/create-trip', [TripController::class, 'store']);
Route::get('/trips-show', [TripController::class, 'show']);
Route::put('/trips-update', [TripController::class, 'update']);
Route::delete('/trips-delete', [TripController::class, 'destroy']);

//Expense router
Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expenses', [ExpenseController::class, 'store']);
Route::get('/expenses', [ExpenseController::class, 'show']);
Route::put('/expenses-update', [ExpenseController::class, 'update']);
Route::delete('/expenses-delete', [ExpenseController::class, 'destroy']);


//Link router
Route::get('/links', [LinkController::class, 'index']);
Route::post('/create-links', [LinkController::class, 'store']);
Route::get('/show-links', [LinkController::class, 'show']);
Route::put('/links-update', [LinkController::class, 'update']);
Route::delete('/links-delete', [LinkController::class, 'destroy']);

//Config router
Route::get('/home-screen', [ConfigController::class, 'getHomeScreen']);


//Contact router
Route::post('/contacts', [ContactController::class, 'store']);

//Destination router
Route::get('/destinations', [DestinationController::class, 'getTopDestinations']);
Route::get('/destination', [DestinationController::class, 'getDestination']);
Route::get('/destination/{id}', [DestinationController::class, 'destinationDetail']);
// Admin router