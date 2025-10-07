<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ProductController; 
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

// Public routes
Route::get('/', function () {
    return view('/auth/login');
});

// Admin only routes (all features)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin exclusive features can be added here in the future
});

// Admin and Kasir routes (dashboard and POS)
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/search', [PosController::class, 'search'])->name('pos.search');
    Route::post('/pos/add-to-cart', [PosController::class, 'addToCart'])->name('pos.addToCart');
    Route::post('/pos/update-quantity', [PosController::class, 'updateQuantity'])->name('pos.updateQuantity');
    Route::post('/pos/remove-from-cart', [PosController::class, 'removeFromCart'])->name('pos.removeFromCart');
    Route::post('/pos/submit-transaction', [PosController::class, 'submitTransaction'])->name('pos.submitTransaction');
    // Allow kasir to restock from dashboard alert
    Route::post('products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
});

// All authenticated users can access transactions (admin, kasir, penjaga_gudang)
Route::middleware(['auth', 'role:admin,kasir,penjaga_gudang'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

// Admin and Penjaga Gudang routes (products and suppliers)
Route::middleware(['auth', 'role:admin,penjaga_gudang'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
});

// Shared routes for all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Fallback dashboard route that redirects based on role
Route::get('/dashboard', function (Request $request) {
    $user = auth()->user();
    
    if ($user->isAdmin() || $user->isKasir()) {
        return app(DashboardController::class)->index($request);
    } elseif ($user->isPenjagaGudang()) {
        return redirect()->route('products.index');
    }
    
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
