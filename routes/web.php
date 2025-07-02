<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseFileController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\DocumentController;



// Dashboard Home (optional)
Route::get('/', function () {
    return view('home'); // Create a 'dashboard.blade.php' if you want a landing page
})->name('home');

Route::resource('clients', ClientController::class);
Route::resource('cases', CaseController::class);
Route::resource('hearings', HearingController::class);
Route::resource('documents', DocumentController::class);

// Nested resource for case files
Route::resource('case.files', CaseFileController::class)->shallow();

Route::post('/case/{case}/files', [CaseFileController::class, 'store'])->name('case.files.store');
