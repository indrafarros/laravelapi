<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/posts', [PostContoller::class, 'index']);
    Route::get('/posts/{id}', [PostContoller::class, 'show']);
    Route::get('/posts2/{id}', [PostContoller::class, 'show2']);
    Route::post('/post', [PostContoller::class, 'store']);
    Route::patch('/posts/{id}', [PostContoller::class, 'update'])->middleware('authorpost');
    Route::delete('/posts/{id}', [PostContoller::class, 'destroy'])->middleware('authorpost');

    Route::get('/logout', [AuthenticationController::class, 'logout']);
});
