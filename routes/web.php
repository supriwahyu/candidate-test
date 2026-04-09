<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
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
    Route::delete('/suppliers/delete/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});

require __DIR__.'/auth.php';
