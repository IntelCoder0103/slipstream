<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIs\CustomerApiController;

Route::apiResource('customers', CustomerApiController::class);