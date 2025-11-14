<?php

use App\Http\Controllers\Apply\GenerateSkipUrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SkipUrlController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 申出スキップURL生成API
Route::middleware(['auth:sanctum'])->post('/apply/skip-url/generate', GenerateSkipUrlController::class)
    ->name('api.apply.skip-url.generate');

// Webルートに移動したので削除
// Route::middleware(['auth:web'])->post('/generate-skip-url', [SkipUrlController::class, 'generateUrl']);
