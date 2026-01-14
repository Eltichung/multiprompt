<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::middleware('guest')->group(function(){
    Route::get('/login/', function () {
        return view('auth.login');
    })->name('admin.view.login');;
    Route::post('/login/', [AuthController::class,'login'])->name('login');;
    Route::post('/register/', [AuthController::class,'register'])->name('register');;

    Route::get('/register/', function () {
        return view('auth.register');
    })->name('admin.view.register');
});

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/logout', [AuthController::class,'logout'])->name('admin.logout');;
});
