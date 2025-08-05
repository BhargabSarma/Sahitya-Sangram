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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorInquiryController;
use App\Http\Controllers\Admin\AdminAuthorInquiryController;
use App\Http\Controllers\Admin\AdminOrderPaymentController;
use \App\Http\Controllers\Admin\InventoryController;
use \App\Http\Controllers\Admin\DeliveryAgentController;


Route::get('/', [HomeController::class, 'index'])->name('index');
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

        //Book Conversion 
        Route::post('/books/{book}/start-conversion', [BookController::class, 'startConversion'])->name('books.startConversion');

        // Import from FTP routes
        Route::get('/import-from-ftp', [BookController::class, 'showImportFromFtp'])->name('importFromFtp');
        Route::post('/import-from-ftp', [BookController::class, 'importFromFtp'])->name('importFromFtp.post');

        //Author Inquiry
        Route::get('/admin/author-inquiries', [AdminAuthorInquiryController::class, 'index'])->name('author.inquiries');
        Route::get('/admin/author-inquiries/{id}', [AdminAuthorInquiryController::class, 'show'])->name('author.inquiries.show');
        Route::post('/admin/author-inquiries/{id}/approve', [AdminAuthorInquiryController::class, 'approve'])->name('author.inquiries.approve');
        Route::post('/admin/author-inquiries/{id}/deny', [AdminAuthorInquiryController::class, 'deny'])->name('author.inquiries.deny');
        Route::delete('/admin/author-inquiries/{id}', [AdminAuthorInquiryController::class, 'destroy'])->name('author.inquiries.destroy');

        // Order/Payment Admin Routes
        Route::get('/admin/orders', [AdminOrderPaymentController::class, 'orders'])->name('orders');
        Route::get('/admin/orders/{id}', [AdminOrderPaymentController::class, 'orderShow'])->name('orders.show');
        Route::get('/admin/payments', [AdminOrderPaymentController::class, 'payments'])->name('payments');
        Route::get('/admin/payments/{id}', [AdminOrderPaymentController::class, 'paymentShow'])->name('payments.show');
        Route::post('/admin/payments/{id}/update-status', [AdminOrderPaymentController::class, 'updatePaymentStatus'])->name('payments.updateStatus');
        Route::post('/admin/orders/{order}/create-shipment', [AdminOrderPaymentController::class, 'createShipment'])->name('orders.createShipment');
        //Inventory
        Route::resource('inventory', InventoryController::class);

        Route::get('courier-partners', [DeliveryAgentController::class, 'showCourierPartners'])->name('courier_partners');
        Route::post('courier-partners/default', [DeliveryAgentController::class, 'setDefaultCourierPartner'])->name('set_default_courier');
    });
});

Route::get('authors/create', [AuthorController::class, 'create'])->name('authors.create');
Route::post('authors', [AuthorController::class, 'store'])->name('authors.store');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors');

// Author submission page
Route::get('/author/inquiry', [AuthorInquiryController::class, 'create'])->name('author.inquiry.create');
Route::post('/author/inquiry', [AuthorInquiryController::class, 'store'])->name('author.inquiry.store');

// Misc Routes
Route::get('/books/read/{id}', [BookReaderController::class, 'showReader'])->name('books.read');

Route::get('/books/{id}/sample', [BookReaderController::class, 'showSample'])->name('books.readSample');

Route::get('/books/{bookId}/{page}', [BookImageController::class, 'show'])->name('books.page');

Route::get('/bookshelf', [BookController::class, 'bookshelf'])->name('books.bookshelf');

// search kora route 
Route::get('/books/search', [BookController::class, 'ajaxSearch'])->name('books.ajaxSearch');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Author inquiry form

Route::get('/publish', [AuthorInquiryController::class, 'showForm'])->name('author.inquiry.form');
Route::post('/publish', [AuthorInquiryController::class, 'submit'])->name('author.inquiry.submit');

// User Authentication Routes 

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

// Cart routes

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{book}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/addresses', [ProfileController::class, 'show'])->name('profile.addresses.show');
    Route::get('/profile/addresses/create', [ProfileController::class, 'create'])->name('profile.addresses.create');
    Route::post('/profile/addresses', [ProfileController::class, 'store'])->name('profile.addresses.store');
    Route::post('/profile/addresses/{address}/set-default', [ProfileController::class, 'setDefault'])->name('profile.addresses.set-default');
    Route::get('/profile/addresses/{address}/edit', [ProfileController::class, 'edit'])->name('profile.addresses.edit');
    Route::put('/profile/addresses/{address}', [ProfileController::class, 'update'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroy'])->name('profile.addresses.destroy');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

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

// Check Pincode Route
Route::post('/check-pincode', [OrderController::class, 'checkPincode'])->name('check.pincode');
