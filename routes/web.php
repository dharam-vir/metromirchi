<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard']); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

 //category
 Route::group(['prefix => "category'], function () {
    Route::get('/create', [CategoryController::class, "Create"]);
    Route::get('/show', [CategoryController::class, "Show"]);
    Route::post('/update', [CategoryController::class, "Update"]);
    Route::post('/delete', [CategoryController::class, "Delete"]);
    Route::post('/de-active', [CategoryController::class, "deActive"]);
});
