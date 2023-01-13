<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum', 'trainingCenter']], function(){

        Route::resource('trainer', \App\Http\Controllers\TrainerController::class);
        Route::get('period', [\App\Http\Controllers\TrainerController::class, 'listWithCreatedPeriod']);  //->name('trainer.list');

});


Route::group([
    'prefix' =>'auth'
    ], function (){

        Route::post('/register', [\App\Http\Controllers\RegistrationController::class, 'register']);
        Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
});
