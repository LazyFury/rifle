<?php

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

function templateContext(
    array $context = []
) {
    return [
        'context' => [
            'title' => 'Hello World!',
            'content' => 'Welcome to my first Laravel application.',
            'icp' => '京ICP备12345678号-1',
        ],
        'request' => request(),
        'domain' => request()->getHost() ?? 'localhost',
    ] + $context;
}

Route::get('/', function () {
    return view('welcome', templateContext([
        'hello' => 'Hello World!',
    ]));
});
