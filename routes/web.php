<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login',    [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login',   [AuthenticatedSessionController::class, 'store']);
Route::post('/logout',  [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register',  [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fund Routes
    Route::resource('funds', FundController::class);

    // Transaction Routes
    Route::resource('transactions', TransactionController::class);

    // Member Routes
    Route::resource('members', MemberController::class);

    // Report Routes
    Route::get('/reports',              [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf',          [ReportController::class, 'generatePdf'])->name('reports.pdf');
    Route::get('/reports/transactions/pdf', [ReportController::class, 'transactionPdf'])->name('reports.transactions.pdf');
});
