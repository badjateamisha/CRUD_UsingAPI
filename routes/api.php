<?php

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ContactController;


Route::post('create', [ContactController::class, 'create']);
Route::Get('display',[ContactController::class,'display']);
Route::Get('display/{id}',[ContactController::class,'display_id']);
Route::Put('updatebyID/{id}', [ContactController::class, 'update_by_id']);
Route::post('updatebyID/{id}', [ContactController::class, 'update_by_id']);
//Delete data by Delete method
Route::Delete('deletebyID/{id}',[ContactController::class,'delete_by_id']);

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
 
Route::group(['middleware' => 'auth:api'], function(){
 Route::post('user-details', [UserController::class, 'userDetails']);
});
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
