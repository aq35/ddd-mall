<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestMiddlewareController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test/middleware', [TestMiddlewareController::class, 'index'])->name('test.middleware');
