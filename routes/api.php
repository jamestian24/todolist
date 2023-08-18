<?php

use App\Http\Controllers\AuthTokenController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
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

Route::post('/auth/login', [AuthTokenController::class, 'login']);
Route::group(['middleware'=>'auth:sanctum'], function ($route) {
    $route->apiResource('/task', TaskController::class);
    $route->apiResource('/member', MemberController::class);
    $route->post('/auth/logout', [AuthTokenController::class, 'logout']);
});


