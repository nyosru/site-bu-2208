<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GoodController;
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

Route::get('posts', [CatalogController::class,'index'] );

Route::get('cat/{id}', [CatalogController::class,'show'] );
Route::get('cat-in/{id}', [CatalogController::class,'showIn'] );

Route::get('goodFromCat/{cat_id}', [GoodController::class,'goodFromCat'] );
Route::get('good/{id}', [GoodController::class,'good'] );

Route::get('posts2', function (Request $request) {
    $data = [ 'data' => [ 11 , 22 , 333 ] ];
    // return response()->json($data, 204);
    return response()->json($data);
});