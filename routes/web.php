<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;



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

Route::get('/', [HomeController::class, 'index']);

Route::prefix('/admin')->group(function(){
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerAction']);
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/{slug}/links', [LinkController::class, 'pageLinks']);
    Route::get('/{slug}/design', [LinkController::class, 'pageDesign']);
    Route::get('/{slug}/stats', [LinkController::class, 'pageStats']);

    Route::get('/linkorder/{linkId}/{pos}', [LinkController::class, 'linkOrderUpdate']);
    Route::get('/{slug}/newlink', [LinkController::class, 'newLink']);
    Route::post('/{slug}/newlink', [LinkController::class, 'newLinkAction']);
    Route::get('/{slug}/editlink/{id}', [LinkController::class, 'editLink']);
    Route::post('/{slug}/editlink/{id}', [LinkController::class, 'editLinkAction']);
});

Route::get('/{slug}', [PageController::class, 'index']);
