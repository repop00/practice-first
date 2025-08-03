<?php

use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('authenticate');
// });

Route::get('/', [UserController::class, 'authenticate'])->name('authenticate');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::middleware(['activeuser'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::post('/storepost', [UserController::class, 'storepost'])->name('storepost');
    Route::post('/storecomment/{id}', [UserController::class, 'storecomment'])->name('storecomment');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
