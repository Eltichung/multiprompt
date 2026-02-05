<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PromptController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return redirect(route('chat'));
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


Route::middleware('auth')->group(function () {
  Route::get('/chat', [ChatController::class, 'index'])->name('chat');
  Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
  Route::post('/chat/data-histories', [ChatController::class, 'getDataChatHistories'])->name('get.data.chat.histories');
  Route::post('/chat/data-prompt', [ChatController::class, 'getDataPrompt'])->name('get.data.prompt');
  Route::post('/chat/send-message', [PromptController::class, 'submit'])->name('chat.send.message');

});
