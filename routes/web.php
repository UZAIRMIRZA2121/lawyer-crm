<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaseAgainstClientController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseFileController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

// Home (public landing page)
Route::get('/', function () {
    return view('home'); // Create resources/views/home.blade.php if you want a homepage
})->name('home');
Route::get('/services', function () {
    return view('services'); // Create resources/views/home.blade.php if you want a homepage
})->name('services');
Route::get('/team', function () {
    return view('team'); // Create resources/views/home.blade.php if you want a homepage
})->name('team');
Route::get('/blogs', function () {
    return view('blogs'); // Create resources/views/home.blade.php if you want a homepage
})->name('blogs');
Route::get('/contact', function () {
    return view('contact'); // Create resources/views/home.blade.php if you want a homepage
})->name('contact');


// Laravel Auth routes (login, register, etc.)
Auth::routes();


// Protected routes (only authenticated users can access)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'show'])->name('profile.show');
    Route::post('/profile', [UserController::class, 'profileupdate'])->name('profile.update');
    Route::resource('users', UserController::class);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clients CRUD
    Route::resource('clients', ClientController::class);

    // Cases CRUD
    Route::resource('cases', CaseController::class);

    // Documents CRUD
    Route::resource('documents', DocumentController::class);

    // Case Files (nested under cases)
    Route::resource('cases.files', CaseFileController::class)->shallow();

    // Hearings nested under cases
    Route::resource('cases.hearings', HearingController::class);
    Route::resource('hearings', HearingController::class);

    // Transactions nested under cases
    Route::resource('cases.transactions', TransactionController::class);
    Route::resource('case-against-clients', CaseAgainstClientController::class);
    Route::resource('notices', NoticeController::class);
    Route::get('notices/clients-by-case/{caseId}', [NoticeController::class, 'getClientsByCase']);

    Route::post('/summon-print', [NoticeController::class, 'print'])->name('summon.print');


    Route::resource('tasks', TaskController::class);

});
