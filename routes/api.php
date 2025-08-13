<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

Route::post('/user/by-email', [UserApiController::class, 'getByEmail']);
Route::post('/user/send-email', [UserApiController::class, 'sendEmail']);
Route::post('/user/user-login', [UserApiController::class, 'userLogin']);
/*Route::post('/user/validate-access-token', [UserApiController::class, 'verifyToken']);*/
Route::get('/user/validate-access-token', [UserApiController::class, 'verifyToken']);


Route::middleware('auth:api')->group(function () {
    /*Route::post('/user/validate-access-token', [UserApiController::class, 'verifyToken']);*/
    /*Route::get('/user/validate-access-token', [UserApiController::class, 'verifyToken']);*/
});
