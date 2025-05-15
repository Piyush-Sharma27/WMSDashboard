<?php

 use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\GrnController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('user', [UserController::class, 'user']);


Route::get('/grn-data', [GrnController::class, 'index']);