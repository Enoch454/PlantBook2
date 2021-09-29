<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes(['register' => false,]);
//Login
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

//register 
Route::post('/register', [App\Http\Controllers\Auth\LoginController::class, 'register'])->name('register');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::apiResource('/plantas', App\Http\Controllers\PlantaController::class);
Route::post('/plantas_update_img', [App\Http\Controllers\PlantaController::class, 'updateImg'])->name('updateImg');