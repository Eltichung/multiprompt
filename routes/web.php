<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PromptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect(route('dashboard'));
});
Route::get('/chat', function () {
  return view('chat');
});
Route::middleware('guest')->group(function () {
  Route::get('/login/', function () {
    return view('auth.login');
  })->name('admin.view.login');;
  Route::post('/login/', [AuthController::class, 'login'])->name('login');;
  Route::post('/register/', [AuthController::class, 'register'])->name('register');;
  Route::get('/register/', function () {
    return view('auth.register');
  })->name('admin.view.register');
});
Route::get('/chat', [ChatController::class, 'index']);
Route::post('/chat/data', [ChatController::class, 'getData'])->name('get.data.chat');

Route::middleware('auth')->group(function () {



  Route::get('/dashboard', function () {
    return view('dashboard');
  })->name('dashboard');
  Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');;
});
