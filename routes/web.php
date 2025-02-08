<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/students/upload', [StudentController::class, 'index'])->name('students.upload');
    Route::post('/students/upload', [StudentController::class, 'upload'])->name('students.upload.post');
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');

});

