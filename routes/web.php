<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\ExercisesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RemindersController;
use App\Http\Controllers\CarersController;
use App\Http\Controllers\TrackingController;


Route::get('/', function () {
    return view('layaout.app', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
});

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Module Exercices
Route::prefix('exercises')->name('exercises.')->group(function () {
    Route::get('/', [ExercisesController::class, 'index'])->name('index');
    Route::post('/start/{type}', [ExercisesController::class, 'start'])->name('start');
    Route::post('/validate', [ExercisesController::class, 'validate'])->name('validate');
});

// Module Agenda/Rappels
Route::prefix('reminders')->name('reminders.')->group(function () {
    Route::get('/', [RemindersController::class, 'index'])->name('index');
    Route::post('/store', [RemindersController::class, 'store'])->name('store');
    Route::patch('/{id}/done', [RemindersController::class, 'markDone'])->name('done');
    Route::delete('/{id}', [RemindersController::class, 'destroy'])->name('destroy');
    Route::get('/today', [RemindersController::class, 'getTodayReminders'])->name('today');
    Route::post('/{id}/notify', [RemindersController::class, 'sendNotification'])->name('notify');

});

// Module Suivi
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/', [TrackingController::class, 'index'])->name('index');
    Route::get('/report', [TrackingController::class, 'generateReport'])->name('report');
    Route::get('/report', [TrackingController::class, 'generateReport'])->name('report');
    Route::get('/export', [TrackingController::class, 'exportData'])->name('export');
});

// Module Aidants
Route::prefix('carers')->name('carers.')->group(function () {
    Route::get('/', [CarersController::class, 'index'])->name('index');
    Route::post('/note', [CarersController::class, 'postNote'])->name('note');
  
    Route::post('/note', [CarersController::class, 'postNote'])->name('note');
    Route::delete('/note/{id}', [CarersController::class, 'deleteNote'])->name('note.delete');
    Route::get('/filter/{category}', [CarersController::class, 'filterByCategory'])->name('filter');
    Route::get('/dashboard', [CarersController::class, 'dashboard'])->name('dashboard');
    Route::get('/export', [CarersController::class, 'exportPatientData'])->name('export');
});

// Module SOS
Route::post('/emergency/trigger', [EmergencyController::class, 'trigger'])->name('emergency.trigger');



require __DIR__.'/settings.php';
