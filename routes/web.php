<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sales\SaleIndex;
use App\Livewire\Sales\SaleCreate;
use App\Livewire\Sales\SaleEdit;
use App\Livewire\Sales\SaleShow;
use App\Livewire\Payments\PaymentIndex;
use App\Livewire\Payments\PaymentCreate;
use App\Livewire\Payments\PaymentEdit;
use App\Livewire\Payments\PaymentShow;
use App\Livewire\Items\ItemIndex;
use App\Livewire\Items\ItemCreate;
use App\Livewire\Items\ItemEdit;
use App\Livewire\Users\UserIndex;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Redirect root to sales
    Route::get('/', function () {
        return redirect()->route('sales.index');
    });

    // Sales Routes
    Route::get('/sales', SaleIndex::class)->name('sales.index');
    Route::get('/sales/create', SaleCreate::class)->name('sales.create');
    Route::get('/sales/{sale}', SaleShow::class)->name('sales.show');
    Route::get('/sales/{sale}/edit', SaleEdit::class)->name('sales.edit');

    // Payments Routes
    Route::get('/payments', PaymentIndex::class)->name('payments.index');
    Route::get('/payments/create', PaymentCreate::class)->name('payments.create');
    Route::get('/payments/{payment}', PaymentShow::class)->name('payments.show');
    Route::get('/payments/{payment}/edit', PaymentEdit::class)->name('payments.edit');

    // Items Routes
    Route::get('/items', ItemIndex::class)->name('items.index');
    Route::get('/items/create', ItemCreate::class)->name('items.create');
    Route::get('/items/{item}/edit', ItemEdit::class)->name('items.edit');

    Route::middleware('role:admin')->group(function () {
        // Dashboard Routes
        Route::get('/dashboard', Dashboard::class)->name('dashboard.index');

        // Users Routes
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserCreate::class)->name('users.create');
        Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');
    });
});