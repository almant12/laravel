<?php


use App\Http\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['auth','verified'],'prefix'=>'listing','as'=>'listing.'],function (){
    Route::get('/manage',[ListingController::class,'manage'])->name('manage');
    Route::get('/create',[ListingController::class,'create'])->name('create');
    Route::post('/store',[ListingController::class,'store'])->name('store');
    Route::get('/show/{listing}',[ListingController::class,'show'])->name('show');
    Route::get('/{listing}/edit',[ListingController::class,'edit'])->name('edit');
    Route::put('/{listing}',[ListingController::class,'update'])->name('update');
    Route::delete('/{listing}',[ListingController::class,'destroy'])->name('delete');
});

Route::get('/',[ListingController::class,'index'])->name('index')->middleware(['auth','verified']);


