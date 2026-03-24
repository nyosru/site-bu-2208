<?php

use App\Http\Controllers\Web\AdvertisementController;
use App\Http\Controllers\Web\CatalogPageController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\VkAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

use App\Http\Controllers\MetricsController;

Route::get('/metrics', MetricsController::class);

Route::view('/', 'home')->name('home');
Route::get('/catalog/{id}', [CatalogPageController::class, 'show'])->name('catalog.show');
Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
Route::post('/advertisements/create/step-two', [AdvertisementController::class, 'stepTwo'])->name('advertisements.step-two');
Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
Route::get('/advertisements/{advertisement}', [AdvertisementController::class, 'show'])->name('advertisements.show');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/auth/vk/redirect', [VkAuthController::class, 'redirect'])->name('auth.vk.redirect');
    Route::get('/auth/vk/callback', [VkAuthController::class, 'callback'])->name('auth.vk.callback');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');
