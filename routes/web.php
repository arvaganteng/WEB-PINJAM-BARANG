<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ItemController as AdminItemController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\FineController as AdminFineController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CatalogController as CustomerCatalogController;
use App\Http\Controllers\Customer\BorrowingController as CustomerBorrowingController;
use App\Http\Controllers\Customer\FineController as CustomerFineController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes (Customer)
Route::get('/login', [AuthController::class, 'showCustomerLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginCustomer'])->name('login.post');
Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp.view');
Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->name('login.otp.verify');
Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Auth Routes (Admin)
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.post');

// Logout (shared)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware([RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Items CRUD
    Route::resource('items', AdminItemController::class)->except(['create', 'show', 'edit']);

    // Categories CRUD
    Route::resource('categories', AdminCategoryController::class)->except(['create', 'show', 'edit']);

    // Customers Management (CRUD)
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [AdminCustomerController::class, 'store'])->name('customers.store');
    Route::put('/customers/{user}', [AdminCustomerController::class, 'update'])->name('customers.update');
    Route::patch('/customers/{user}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('/customers/{user}', [AdminCustomerController::class, 'destroy'])->name('customers.destroy');

    // Borrowings Verification & Extensions
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::patch('/borrowings/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::patch('/borrowings/{borrowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::patch('/borrowings/{borrowing}/confirm-payment', [AdminBorrowingController::class, 'confirmPayment'])->name('borrowings.confirm-payment');
    Route::patch('/borrowings/{borrowing}/reject-payment', [AdminBorrowingController::class, 'rejectPayment'])->name('borrowings.reject-payment');
    Route::patch('/borrowings/{borrowing}/release', [AdminBorrowingController::class, 'release'])->name('borrowings.release');
    Route::patch('/borrowings/{borrowing}/approve-extension', [AdminBorrowingController::class, 'approveExtension'])->name('borrowings.approve-extension');
    Route::patch('/borrowings/{borrowing}/reject-extension', [AdminBorrowingController::class, 'rejectExtension'])->name('borrowings.reject-extension');

    // Returns & Fines Management
    Route::get('/returns', [AdminReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns', [AdminReturnController::class, 'store'])->name('returns.store');
    Route::patch('/returns/{returnRecord}/confirm-fine', [AdminReturnController::class, 'confirmFinePayment'])->name('returns.confirm-fine');
    Route::get('/fines', [AdminFineController::class, 'index'])->name('fines.index');

    // Reports
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs/clear-old', [AdminActivityLogController::class, 'clearOld'])->name('activity-logs.clear-old');
});

/*
|--------------------------------------------------------------------------
| Customer Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->middleware([RoleMiddleware::class . ':customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Catalog & Detail
    Route::get('/catalog', [CustomerCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{item}', [CustomerCatalogController::class, 'show'])->name('catalog.show');

    // Borrowing & Fine Flow
    Route::get('/borrow/{item}', [CustomerBorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrow', [CustomerBorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings', [CustomerBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/history', [CustomerBorrowingController::class, 'history'])->name('borrowings.history');
    Route::get('/borrowings/{borrowing}', [CustomerBorrowingController::class, 'show'])->name('borrowings.show');
    Route::post('/borrowings/{borrowing}/pay', [CustomerBorrowingController::class, 'payBorrowing'])->name('borrowings.pay');
    Route::get('/borrowings/{borrowing}/invoice', [CustomerBorrowingController::class, 'invoice'])->name('borrowings.invoice');
    Route::post('/borrowings/{borrowing}/return-request', [CustomerBorrowingController::class, 'requestReturn'])->name('borrowings.return-request');
    Route::post('/borrowings/{borrowing}/extension-request', [CustomerBorrowingController::class, 'requestExtension'])->name('borrowings.extension-request');
    Route::post('/borrowings/{borrowing}/pay-fine', [CustomerBorrowingController::class, 'payFine'])->name('borrowings.pay-fine');
    Route::post('/borrowings/{borrowing}/review', [CustomerBorrowingController::class, 'submitReview'])->name('borrowings.review');
    Route::get('/fines', [CustomerFineController::class, 'index'])->name('fines.index');

    // Profile & Security
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Notifications (Shared for Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
