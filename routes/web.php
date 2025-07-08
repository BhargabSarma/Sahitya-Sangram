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

Route::get('/', [AdminController::class, 'index'])->name('index');

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
Route::get('authors/create', [AuthorController::class, 'create'])->name('authors.create');
Route::post('authors', [AuthorController::class, 'store'])->name('authors.store');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors');

Route::get('/books/read/{id}', [BookReaderController::class, 'showReader'])->name('books.read');
Route::get('/books/{bookId}/{page}', [BookImageController::class, 'show'])->name('books.page');
Route::get('/bookshelf', [BookController::class, 'bookshelf'])->name('books.bookshelf');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/publish', function () {
    return view('publish');
})->name('publish');

// User Authentication Routes (add these after your existing routes)
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
