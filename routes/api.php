<?php

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

use App\Http\Controllers\SharedLoginController; // Added import

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint to generate a token for shared login
Route::post('/shared-login/generate-token', [SharedLoginController::class, 'generateToken'])
    // ->middleware('auth:web')
    ; // Requires user to be logged in via web session
