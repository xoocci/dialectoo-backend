<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\usuarioController;
use App\Http\Controllers\API\PassportAuthController;
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

/**Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('register',[PassportAuthController::class,'register']);
Route::post('login',[PassportAuthController::class,'login']);
Route::middleware('auth:api')->group(function(){
Route::get('get-user',[PassportAuthController::class,'userInfo']);
});

Route::post('/auth/register', [AuthController::class, 'register']);
//Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/employees', [EmployeeController::class, 'all']);


//Route::post('/login', [usuarioController::class, 'authenticate']);