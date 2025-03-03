<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CategoryController;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('categories', CategoryController::class);