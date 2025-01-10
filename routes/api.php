<?php

use App\Http\Controllers\Api\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['checkIfHasKey', 'checkUserToken']) // Apply middleware to the entire group
    ->prefix('uploads') // Add the prefix for the group
    ->controller(UploadController::class) // Set the controller for the group
    ->group(function () {
        Route::post('/upload', 'upload'); // Define the route with the method name only
        Route::get('/getImages', 'getUserImages'); // Define the route with the method name only
    });
