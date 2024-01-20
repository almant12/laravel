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

Route::group(['middleware'=>['auth'],'prefix'=>'user','as'=>'user.'],function (){
    Route::get('/edit/{user}',[UserController::class,'edit'])->name('edit');
    Route::put('/{user}',[UserController::class,'update'])->name('update');
    Route::post('/logout',[UserController::class,'logout'])->name('logout');
});
Route::get('/signup',[UserController::class,'register'])->name('user.register');
Route::get('/login', [UserController::class, 'login'])->name('user.login');
Route::post('/signup',[UserController::class,'store'])->name('user.store');
Route::post('/login',[UserController::class,'authenticate'])->name('user.authenticate');
Route::get('/user/confirmEmail',[UserController::class,'verifyToken']);

