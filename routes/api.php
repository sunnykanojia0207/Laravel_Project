<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/profile', [AuthController::class, 'registerInfo']);
Route::get('/user', [AuthController::class, 'getUser']);
route::post('/rpa', [AuthController::class, 'runRPA']);
// route::post('/api/rpa', [AuthController::class, 'runRPA']);
;Route::post('/stop-rpa', [AuthController::class, 'stopRPA']);
// Route::get('/userlist', [AuthController::class, 'fetchUserList']);
(Route::get('/userlist', [AuthController::class, 'fetchUserList'])->middleware('checkAge'));

Route::get('/userdetails/{userId}', [AuthController::class, 'fetchUserDetails']);
Route::post('/updateuser', [AuthController::class, 'updateUser']);
Route::delete('/user/{userId}', [AuthController::class, 'deleteUser']);