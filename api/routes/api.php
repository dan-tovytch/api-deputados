<?php

use App\Http\Controllers\DeputadosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("deputados")->group(function () {
    Route::get("/ranking", [DeputadosController::class, 'ranking'])->name("ranking.deputados");
});
