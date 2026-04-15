<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinancialAnalysisController;

// Page d'accueil
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentification (générée par laravel/ui)
Auth::routes();

// Routes protégées
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::resource('transactions', TransactionController::class);

    // Factures
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');

    // Dettes
    Route::resource('debts', DebtController::class);
    Route::post('/debts/{debt}/payment', [DebtController::class, 'makePayment'])->name('debts.payment');

    // Budgets
    Route::resource('budgets', BudgetController::class);

    // Catégories
    Route::resource('categories', CategoryController::class);

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes pour les rapports
Route::middleware(['auth'])->group(function () {
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/analysis', [FinancialAnalysisController::class, 'index'])->name('analysis.index');
    Route::get('/analysis/recommendations', [FinancialAnalysisController::class, 'recommendations'])->name('analysis.recommendations');
    Route::get('/analysis/forecast', [FinancialAnalysisController::class, 'forecast'])->name('analysis.forecast');
});
Route::post('/invoices/{id}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');
