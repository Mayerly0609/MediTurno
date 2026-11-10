<?php

use App\Http\Controllers\Api\V1\AccesorioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/v1/accesorios',[AccesorioController::class, 'index']);