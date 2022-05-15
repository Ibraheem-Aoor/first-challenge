<?php

use App\Http\Controllers\Api\Auth\AuthUserController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Profile\ProfileController;
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

/*
    * Prefix => api
*/

Route::middleware('auth:sanctum')->group(function()
{
    Route::get('logout' , [AuthUserController::class , 'logout']);
    Route::apiResource('profile' , ProfileController::class);
    Route::post('forget-password' , [PasswordController::class , 'update']);
});

Route::group(['middleware'=> 'guest:sanctum'] , function()
{
    Route::post('signup' , [AuthUserController::class , 'signUp']);
    Route::post('login' , [AuthUserController::class , 'login']);
});
