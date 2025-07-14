<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReaderController;
use App\Http\Controllers\BookImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PaymentsController;


Route::get('/', [AdminController::class, 'index'])->name('index');
// ADMIN Routes
Route::prefix('admin')->as('admin.')->group(function () {

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin', 'verify.post.size'])->group(function () {
        Route::get('/dashboard', [BookController::class, 'dashboard'])->name('dashboard');
        Route::resource('books', BookController::class);
        Route::get('/books/{id}/check_ready', [BookController::class, 'checkReady'])->name('books.check_ready');
    });
});
//Authors Routes
Route::get('authors/create', [AuthorController::class, 'create'])->name('authors.create');
Route::post('authors', [AuthorController::class, 'store'])->name('authors.store');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors');
// Misc Routes
Route::get('/books/read/{id}', [BookReaderController::class, 'showReader'])->name('books.read');

Route::get('/books/{id}/sample', [BookReaderController::class, 'showSample'])->name('books.readSample');

Route::get('/books/{bookId}/{page}', [BookImageController::class, 'show'])->name('books.page');

Route::get('/bookshelf', [BookController::class, 'bookshelf'])->name('books.bookshelf');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

Route::get('/publish', function () {
    return view('publish');
})->name('publish');

// User Authentication Routes (add these after your existing routes)
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


// Google
Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Facebook
Route::get('auth/facebook', [SocialAuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);

// Cart routes (persistent for logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{book}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('order.checkout.post');
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/order/confirmation/{order}', [OrderController::class, 'confirmation'])->name('order.confirmation');
    Route::get('/orders', [OrderController::class, 'history'])->name('order.history');

    // Payment routes (add these to complete payment process)

    Route::get('/payments/{order}', [PaymentsController::class, 'show'])->name('payments.show');
    Route::post('/payments/{order}', [PaymentsController::class, 'pay'])->name('payments.pay');
    Route::post('/payments/callback', [PaymentsController::class, 'callback'])->name('payments.callback'); // Razorpay webhook/callback (optional)


    // Library Routes

    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/review/{book}', [LibraryController::class, 'reviewForm'])->name('library.review.form');
    Route::post('/library/review/{book}', [LibraryController::class, 'storeReview'])->name('library.review.store');
});
