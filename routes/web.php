<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\PDFController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AboutPageController;

// BUNGKUS SEMUA RUTE DI DALAM GRUP 'web' AGAR SESSION BERJALAN
Route::middleware('web')->group(function () {

    // Default Landing Page
    Route::get('/', function () {
        return view('welcome');
    });

    // Rute untuk Publik (User) - Sesuai Sketsa
    Route::get('/about-us', [AboutPageController::class, 'show'])->name('about.show');

    // Rute untuk Admin (Grup dengan middleware auth/admin)
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // Halaman form edit
        Route::get('/about-us/edit', [AboutPageController::class, 'edit'])->name('about.edit');

        // Proses update
        Route::put('/about-us/update', [AboutPageController::class, 'update'])->name('about.update');
    });

    // Custom Auth
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Auth::routes();

    // Redirect user berdasarkan role
    Route::get('/redirect', function () {
        $role = Auth::user()->role ?? null;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'user') {
            return redirect()->route('user.dashboard');
        }

        return redirect('/');
    });

    // ---------------- ADMIN ROUTES ----------------
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

        Route::patch('/produk/{produk}/stock', [ProdukController::class, 'updateStock'])
            ->name('produk.updateStock');

        Route::resource('produk', ProdukController::class);
        Route::resource('kategori', KategoriController::class);
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // Transaksi Admin
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/{id}/konfirmasi', [AdminTransactionController::class, 'konfirmasi'])->name('transactions.konfirmasi');

        // Chat admin
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    });

    // ---------------- USER ROUTES ----------------
    Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');


        // Checkout
        Route::get('/checkout', [TransactionController::class, 'checkoutForm'])->name('checkout.form');
        Route::post('/checkout', [TransactionController::class, 'processCheckout'])->name('checkout.process');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
        Route::post('/transactions/selesai/{id}', [TransactionController::class, 'terimaPesanan'])->name('transactions.selesai');
        Route::get('/struk/{id}', [PDFController::class, 'cetakStruk'])->name('struk');

        // Chat user
        Route::get('/chat', [ChatController::class, 'index'])->name('chat');
        Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');

        Route::get('/pay/va', [PaymentController::class, 'createVA']);
    });
}); // <-- TUTUP GRUP 'web' DI SINI
