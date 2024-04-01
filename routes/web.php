<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

// posts
Route::get('/posts', [PostController::class, 'list']);
Route::get('/post/{slug}', [PostController::class, 'detail']);
