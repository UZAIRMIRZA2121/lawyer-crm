<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseFileController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;


// Dashboard Home (optional)
Route::get('/', function () {
    return view('home'); // Create a 'dashboard.blade.php' if you want a landing page
})->name('home');
Auth::routes();

// Protected routes (only for authenticated users)
Route::middleware(['auth'])->group(function () {

    // Dashboard page for admins
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('cases', CaseController::class);
    Route::resource('hearings', HearingController::class);
    Route::resource('documents', DocumentController::class);

    // Nested resource for case files
    Route::resource('case.files', CaseFileController::class)->shallow();
    Route::post('/case/{case}/files', [CaseFileController::class, 'store'])->name('case.files.store');
    Route::get('/case/{case}/hearings/create', [HearingController::class, 'create'])->name('hearings.create');

    Route::prefix('cases/{case}')->group(function () {
        Route::resource('/hearings', HearingController::class);
        Route::get('/hearings/create', [HearingController::class, 'create'])->name('hearings.create');
    });
    
    Route::resource('cases.transactions', TransactionController::class);

});
