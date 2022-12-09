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
//Route::group('download', \App\Http\Controllers\DownloadController::class, ['upload', 'download', 'delete']);

Route::prefix('file')->group(function(){
    Route::post('upload', [\App\Http\Controllers\FileController::class, 'upload']);
    Route::get('download/{file}', [\App\Http\Controllers\FileController::class, 'download']);
    Route::get('delete/{file}', [\App\Http\Controllers\FileController::class, 'delete']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
