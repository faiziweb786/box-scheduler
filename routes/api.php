<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BoxController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// Example routes for boxes
Route::get('/boxes', [BoxController::class, 'index']);
Route::post('/boxes/store', [BoxController::class, 'store']);
