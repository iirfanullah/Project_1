<?php

use App\Http\Controllers\Auth\GoogleLogin;
use App\Http\Controllers\Meeting\MeetingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//Meeting Routes
Route::post('/store', [MeetingController::class, 'store'])->name('meetings.store');
Route::delete('/delete/{id}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
Route::get('/edit/{id}', [MeetingController::class, 'edit'])->name('meetings.edit');
Route::put('/update/{id}', [MeetingController::class, 'update'])->name('meetings.update');

Auth::routes();

//home Screen Rout
Route::get('/home', [MeetingController::class, 'index'])->name('home');

//Google login Routes
Route::get('/google/login', [GoogleLogin::class, 'loginPage'])->name('google.login');
Route::get('/google/redirect', [GoogleLogin::class, 'googleRedirect'])->name('google.redirect');
