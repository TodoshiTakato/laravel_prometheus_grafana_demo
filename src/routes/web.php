<?php

use App\Http\Controllers\MetricsController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['message' => 'Welcome to Laravel API'];
});

Route::get('/metrics', [MetricsController::class, 'metrics']);
Route::get('/test', [TestController::class, 'test']);
