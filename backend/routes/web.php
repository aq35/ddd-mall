<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

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

// デザインパターンミドルウェア
Route::get('/test/middleware', [TestController::class, 'middlewareTest'])->name('test.middleware');
// デザインパターンイベントディスパッチャー
Route::get('/test/event-dispatcher', [TestController::class, 'eventDispatcherTest'])->name('test.event-dispatcher');

// デザインパターンオブザーバー
Route::get('/test/observer', [TestController::class, 'observerTest'])->name('test.observer');

// ユーザーエンティティバリデーション＊デザインパターンミドルウェア
Route::get('/test/validate-middleware', [TestController::class, 'validateMiddleware'])->name('validate.middleware');
