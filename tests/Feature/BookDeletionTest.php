<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test directories
        $this->ensureDirectoryExists(base_path('storage/images/front'));
        $this->ensureDirectoryExists(base_path('storage/images/back'));
        $this->ensureDirectoryExists(storage_path('app/books'));
    }

    private function ensureDirectoryExists($path)
    {
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    public function test_book_deletion_cascades_to_reviews()
    {
        // Create an author and user
        $author = Author::create([
            'name' => 'Test Author',
            'email' => 'author@test.com',
            'biography' => 'Test biography',
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create an order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 10.00,
            'status' => 'completed',
        ]);

        // Create a book
        $book = Book::create([
            'title' => 'Test Book',
            'author_id' => $author->id,
            'description' => 'Test description',
            'category' => 'Fiction',
            'level' => 'Beginner',
            'hard_copy_price' => 10.00,
            'digital_price' => 5.00,
        ]);

        // Create a review for the book
        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Great book!',
        ]);

        // Verify the review exists
        $this->assertDatabaseHas('reviews', ['id' => $review->id]);

        // Delete the book
        $book->delete();

        // Verify the book is deleted
        $this->assertDatabaseMissing('books', ['id' => $book->id]);

        // Verify the review is also deleted (cascade)
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_book_controller_deletion_removes_files()
    {
        // Create an author
        $author = Author::create([
            'name' => 'Test Author',
            'email' => 'author@test.com',
            'biography' => 'Test biography',
        ]);

        // Create test files
        $frontImagePath = base_path('storage/images/front/test-front.jpg');
        $backImagePath = base_path('storage/images/back/test-back.jpg');
        $bookFilePath = storage_path('app/books/test-book.pdf');
        $bookImagesDir = storage_path('app/books/1');

        file_put_contents($frontImagePath, 'test front image content');
        file_put_contents($backImagePath, 'test back image content');
        file_put_contents($bookFilePath, 'test book content');

        // Create book images directory with a test image
        mkdir($bookImagesDir, 0755, true);
        file_put_contents($bookImagesDir.'/page1.jpg', 'test page image');

        // Create a book with file references
        $book = Book::create([
            'title' => 'Test Book',
            'author_id' => $author->id,
            'description' => 'Test description',
            'category' => 'Fiction',
            'level' => 'Beginner',
            'hard_copy_price' => 10.00,
            'digital_price' => 5.00,
            'cover_image_front' => 'images/front/test-front.jpg',
            'cover_image_back' => 'images/back/test-back.jpg',
            'book_file' => 'books/test-book.pdf',
        ]);

        // Verify files exist before deletion
        $this->assertTrue(file_exists($frontImagePath));
        $this->assertTrue(file_exists($backImagePath));
        $this->assertTrue(file_exists($bookFilePath));
        $this->assertTrue(is_dir($bookImagesDir));

        // Test the controller method directly (simulating what would happen)
        $controller = new \App\Http\Controllers\BookController;
        $controller->destroy($book);

        // Verify files are deleted
        $this->assertFalse(file_exists($frontImagePath));
        $this->assertFalse(file_exists($backImagePath));
        $this->assertFalse(file_exists($bookFilePath));
        $this->assertFalse(is_dir($bookImagesDir));
    }

    public function test_book_controller_deletion_handles_missing_files_gracefully()
    {
        // Create an author
        $author = Author::create([
            'name' => 'Test Author',
            'email' => 'author@test.com',
            'biography' => 'Test biography',
        ]);

        // Create a book with file references to non-existent files
        $book = Book::create([
            'title' => 'Test Book',
            'author_id' => $author->id,
            'description' => 'Test description',
            'category' => 'Fiction',
            'level' => 'Beginner',
            'hard_copy_price' => 10.00,
            'digital_price' => 5.00,
            'cover_image_front' => 'images/front/non-existent.jpg',
            'cover_image_back' => 'images/back/non-existent.jpg',
            'book_file' => 'books/non-existent.pdf',
        ]);

        // Test the controller method directly - this should not throw an exception
        $controller = new \App\Http\Controllers\BookController;
        $controller->destroy($book);

        // Verify the book is deleted
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    protected function tearDown(): void
    {
        // Clean up test files and directories
        $this->cleanupTestFiles();
        parent::tearDown();
    }

    private function cleanupTestFiles()
    {
        $testPaths = [
            base_path('storage/images/front/test-front.jpg'),
            base_path('storage/images/back/test-back.jpg'),
            storage_path('app/books/test-book.pdf'),
            storage_path('app/books/1'),
        ];

        foreach ($testPaths as $path) {
            if (is_file($path)) {
                unlink($path);
            } elseif (is_dir($path)) {
                $this->removeDirectory($path);
            }
        }
    }

    private function removeDirectory($dir)
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}
