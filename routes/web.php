<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GoogleLogin;
use App\Http\Controllers\Meeting\MeetingController;
use App\Http\Controllers\testController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', [testController::class, 'hello']);
Route::post('/store', [MeetingController::class, 'store'])->name('meetings.store');
// Route::post('/store', [EventController::class, 'store'])->name('meetings.store');
// Route::delete('/delete/{id}', [EventController::class, 'destroy'])->name('meetings.destroy');
// Route::get('/edit/{id}', [EventController::class, 'edit'])->name('meetings.edit');
// Route::put('/update/{id}', [EventController::class, 'update'])->name('meetings.update');
Route::delete('/delete/{id}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
Route::get('/edit/{id}', [MeetingController::class, 'edit'])->name('meetings.edit');
Route::put('/update/{id}', [MeetingController::class, 'update'])->name('meetings.update');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/google/login', [GoogleLogin::class, 'loginPage'])->name('google.login');
Route::get('/google/redirect', [GoogleLogin::class, 'googleRedirect'])->name('google.redirect');
