<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hearts360Controller;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('upload.index');
Route::post('/upload', [DashboardController::class, 'upload'])->name('upload.process');

Route::get('hearts360/hipertensi', [Hearts360Controller::class, 'hipertensi'])->name('hearts360.hipertensi');
Route::get('hearts360/diabetes', [Hearts360Controller::class, 'diabetes'])->name('hearts360.diabetes');

// AJAX filters
Route::get('hearts360/get-regencies', [Hearts360Controller::class, 'getRegencies'])->name('hearts360.get-regencies');
Route::get('hearts360/get-districts', [Hearts360Controller::class, 'getDistricts'])->name('hearts360.get-districts');
Route::get('hearts360/get-villages', [Hearts360Controller::class, 'getVillages'])->name('hearts360.get-villages');
Route::get('hearts360/get-faskes', [Hearts360Controller::class, 'getFaskes'])->name('hearts360.get-faskes');

// Language switcher
Route::get('lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');
