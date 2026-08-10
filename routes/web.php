<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index']);
Route::post('/api/chat', [ChatController::class, 'chat']);
