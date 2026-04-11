<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CltLayupController;
use App\Http\Controllers\CltLayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers/create', [SupplierController::class, 'store'])->name('suppliers.create');
    Route::put('/suppliers/edit/{id}', [SupplierController::class, 'update'])->name('suppliers.edit');
    Route::get('/suppliers/show/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::delete('/suppliers/delete/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::post('/suppliers/import/{id}', [SupplierController::class, 'import'])->name('suppliers.import');
    Route::get('suppliers/export/{id}', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::get('/conflict/select/{index}', [SupplierController::class, 'selectConflict']);
    Route::post('/conflict/clear-session', [SupplierController::class, 'clearConflictSession']);

    Route::get('/layups/show/{id}', [CltLayupController::class, 'show'])->name('layups.show');
    Route::delete('/layups/destroy/{id}', [CltLayupController::class, 'destroy'])->name('layups.destroy');
    Route::put('/layups/update/{id}', [CltLayupController::class, 'update'])->name('layups.update');
    Route::post('/layups/create', [CltLayupController::class, 'store'])->name('layups.store');

    Route::post('/layers/create', [CltLayerController::class, 'store'])->name('layers.store');
    Route::delete('/layers/destroy/{id}', [CltLayerController::class, 'destroy'])->name('layers.destroy');
    Route::put('/layers/update/{id}', [CltLayerController::class, 'update'])->name('layers.update');
});

require __DIR__.'/auth.php';
