<?php

use App\Http\Controllers\Api\AdvertisementTaskController;
use App\Http\Controllers\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('cats', [CatalogController::class, 'index']);

// Route::get('cats', function (Request $request) {
//     return json_encode([1 => 2, 'sdf' => 'sdfsdfff']);
// });

Route::get('cat/{id}', [CatalogController::class, 'show0']);
Route::get('cat-in/{id?}', [CatalogController::class, 'showIn']);
Route::get('cat-tree/{id}', [CatalogController::class, 'showTree']);
Route::post('advertisement-tasks', [AdvertisementTaskController::class, 'store']);
