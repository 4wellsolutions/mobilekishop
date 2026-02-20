<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\BlogCategoryApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Blog API Routes (protected by Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogApiController::class, 'index']);
        Route::post('/', [BlogApiController::class, 'store']);
        Route::post('/media', [BlogApiController::class, 'uploadMedia']);
        Route::get('/{slug}', [BlogApiController::class, 'show']);
        Route::put('/{slug}', [BlogApiController::class, 'update']);
        Route::delete('/{slug}', [BlogApiController::class, 'destroy']);
    });

    Route::prefix('blog-categories')->group(function () {
        Route::get('/', [BlogCategoryApiController::class, 'index']);
        Route::post('/', [BlogCategoryApiController::class, 'store']);
        Route::get('/{slug}', [BlogCategoryApiController::class, 'show']);
        Route::put('/{slug}', [BlogCategoryApiController::class, 'update']);
        Route::delete('/{slug}', [BlogCategoryApiController::class, 'destroy']);
    });
});
