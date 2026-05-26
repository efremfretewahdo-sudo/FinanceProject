<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiInsightController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OtherIncomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnpaidController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/',           fn() => view('public.home'))->name('home');
Route::get('/about',      fn() => view('public.about'))->name('about');
Route::get('/contact',    fn() => view('public.contact'))->name('contact');
Route::post('/contact',   [ContactController::class, 'store'])->name('contact.store');

// Google OAuth
Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Pending approval page (auth required, no approval check)
Route::middleware('auth')->get('/pending-approval', fn() => view('auth.pending-approval'))->name('approval.pending');

// Auth + approved routes
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Finance resources
    Route::resource('transactions', TransactionController::class)->except(['show']);
    // Members
    Route::get('/members',               [MemberController::class, 'index'])->name('members');
    Route::post('/members',              [MemberController::class, 'store'])->name('members.store');
    Route::patch('/members/{member}',    [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}',   [MemberController::class, 'destroy'])->name('members.destroy');

    // Payments
    Route::get('/payments',              [PaymentController::class, 'index'])->name('payments');
    Route::post('/payments',             [PaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{payment}',  [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::patch('/payments/{payment}/paid', [PaymentController::class, 'markPaid'])->name('payments.paid');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

    // Unpaid
    Route::get('/unpaid',                    [UnpaidController::class, 'index'])->name('unpaid');
    Route::post('/unpaid',                   [UnpaidController::class, 'store'])->name('unpaid.store');
    Route::patch('/unpaid/{item}/pay',       [UnpaidController::class, 'markPaid'])->name('unpaid.pay');
    Route::delete('/unpaid/{item}',          [UnpaidController::class, 'destroy'])->name('unpaid.destroy');
    Route::post('/unpaid/{member}/approve',  [UnpaidController::class, 'approveMember'])->name('unpaid.approve');

    // Other Income
    Route::get('/other-income',              [OtherIncomeController::class, 'index'])->name('other-income');
    Route::post('/other-income',             [OtherIncomeController::class, 'store'])->name('other-income.store');
    Route::delete('/other-income/{income}',  [OtherIncomeController::class, 'destroy'])->name('other-income.destroy');

    // Expenses (filtered transactions — expense type only)
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');

    // Static pages
    Route::get('/ai-insights', [PageController::class, 'aiInsights'])->name('ai-insights');
    Route::get('/calculator',  [PageController::class, 'calculator'])->name('calculator');
    // AI report generation (AJAX)
    Route::post('/ai-insights/generate', [AiInsightController::class, 'generate'])->name('ai.generate');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                   [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',                       [AdminController::class, 'users'])->name('users');
    Route::get('/messages',                    [AdminController::class, 'messages'])->name('messages');
    Route::get('/subscriptions',               [AdminController::class, 'subscriptions'])->name('subscriptions');
    Route::post('/lock',                       [AdminController::class, 'lockSystem'])->name('lock');
    Route::post('/unlock',                     [AdminController::class, 'unlockSystem'])->name('unlock');
    Route::post('/users/{user}/approve',       [AdminController::class, 'approveUser'])->name('users.approve');
    Route::delete('/users/{user}/reject',      [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::patch('/messages/{message}/read',   [ContactController::class, 'markRead'])->name('messages.read');
    Route::delete('/messages/{message}',       [ContactController::class, 'destroy'])->name('messages.destroy');
});

require __DIR__ . '/auth.php';
