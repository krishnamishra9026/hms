<?php

use Illuminate\Http\Request;
use Api\HospitalController;
use Api\PatientController;

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

Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('patients', PatientController::class);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
