<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CustomOrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ActivityLogController as CustomerActivityLogController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CustomOrderController as AdminCustomOrderController; // <-- NA-AYOS: Added alias
use App\Models\ActivityLog;

// ============================================
// 1. GUEST ROUTES (Hindi pa naka-login)
// ============================================
Route::middleware('guest')->group(function () {
    // Default route → Redirect to Login
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Auth Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // OTP Verification
    Route::get('/verify-otp', [OtpVerificationController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify']);
    Route::post('/resend-otp', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');
});

// ============================================
// 2. AUTHENTICATED ROUTES (Naka-login na - Customer & General)
// ============================================
Route::middleware('auth')->group(function () {
    
    // Customer Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    
    // Home/Shop Page
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Product Details
    Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Customer Orders
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
    
    // Custom Order Request (Customer)
    Route::get('/custom-order', [CustomOrderController::class, 'create'])->name('custom-order.create');
    Route::post('/custom-order', [CustomOrderController::class, 'store'])->name('custom-order.store');
    
    // Profile Settings (Customer)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('customer.profile.update');

    // Customer Activity History (Makikita lang nila ang sarili nilang logs)
    Route::get('/my-activity', [CustomerActivityLogController::class, 'index'])->name('customer.activity-logs.index');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ============================================
// 3. ADMIN ROUTES (Protected by 'auth' and 'admin' middleware)
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Admin Dashboard Root Redirect
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Admin Dashboard (May Recent Activity Logs)
    Route::get('/dashboard', function () {
        // Kunin ang pinakabagong 5 activity logs kasama ang user details
        $recentLogs = ActivityLog::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('recentLogs'));
    })->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Categories, Colors, Add-ons Management
    Route::resource('categories', CategoryController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('addons', AddonController::class);
    
    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Users Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Custom Orders Management (NA-AYOS: Gumamit ng AdminCustomOrderController)
    Route::get('custom-orders', [AdminCustomOrderController::class, 'index'])->name('custom-orders.index');
    Route::get('custom-orders/{id}', [AdminCustomOrderController::class, 'show'])->name('custom-orders.show');
    Route::post('custom-orders/{id}/status', [AdminCustomOrderController::class, 'updateStatus'])->name('custom-orders.updateStatus');

    // Activity Logs Management (Full Page para sa Admin)
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});