<?php

use App\Http\Controllers\ShipmentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware([
    'auth',
    'verified'
])->group(function () {
    Route::get('/shipments', [ShipmentsController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/{shipment}/show', [ShipmentsController::class, 'show'])->name('shipments.show');
    Route::post('/shipments/{shipment}/addReferent', [ShipmentsController::class, 'addReferent'])->name('shipments.add_referent');
});

require __DIR__ . '/settings.php';
