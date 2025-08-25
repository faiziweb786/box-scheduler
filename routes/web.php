<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BoxController;

Route::get('/', function () {
    return view('welcome');
});

// Box Scheduler Routes
Route::get('/box-scheduler', [BoxController::class, 'index'])->name('box.scheduler');

// API Routes for AJAX calls
Route::get('/api/boxes', [BoxController::class, 'getBoxes']);
Route::post('/api/boxes', [BoxController::class, 'createBox']);
Route::post('/api/boxes/create/{count?}', [BoxController::class, 'createBoxes']);
Route::post('/api/boxes/reset', [BoxController::class, 'reset']);

// Test email route (remove after testing)
Route::get('/test-email', function () {
    $boxCount = 16;
    $fullName = 'Muhammad Ahmad'; // Replace with your actual name

    try {
        Mail::to('Dawood.ahmed@collaborak.com')->send(
            new App\Mail\TaskCompletedMail($boxCount, $fullName)
        );
        return 'Email sent successfully! Check your email or logs.';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});
