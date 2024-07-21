<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_all_books()
    {
        // Arrange: create 3 books
        Book::factory()->count(3)->create();

        // Act: call the getCollection method
        $response = $this->getJson('/api/books');

        // Assert: check the response
        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_should_return_a_book_by_id()
    {
        // Arrange: create a book
        $book = Book::factory()->create();

        // Act: call the getByID method
        $response = $this->getJson("/api/books/{$book->id}");

        // Assert: check the response
        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'id' => $book->id,
                        'title' => $book->title,
                        'publisher' => $book->publisher,
                        'author' => $book->author,
                        'genre' => $book->genre,
                        'published_at' => $book->published_at,
                        'amount_words' => $book->amount_words,
                        'price' => $book->price,
                        'currency' => 'USD',
                    ],
                ]);
    }

    /** @test */
    public function it_should_return_404_if_book_not_found()
    {
        // Act: call the getByID method with non-existent id
        $response = $this->getJson('/api/books/999');

        // Assert: check the response status
        $response->assertStatus(404)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Book not found',
                ]);
    }

    /** @test */
    public function it_should_create_a_new_book()
    {
        // Arrange: create data for a new book
        $data = [
            'title' => 'Test Book',
            'publisher' => 'Test Publisher',
            'author' => 'Test Author',
            'genre' => 'Fiction',
            'published_at' => '2023-01-01',
            'amount_words' => 50000,
            'price' => 9.99,
        ];

        // Act: call the create method
        $response = $this->postJson('/api/books', $data);

        // Assert: check the response
        $response->assertStatus(200)
                 ->assertJson([
                        'status' => 'success',
                        'data' => [
                            ...$data,
                            'id' => $response['data']['id'],
                            'currency' => 'USD'
                        ],
                 ]);

        // Additional check that the book was actually created
        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function it_should_update_a_book()
    {
        // Arrange: create a book
        $book = Book::factory()->create();

        // New data for updating
        $data = [
            'title' => 'Updated Title',
            'publisher' => 'Updated Publisher',
            'author' => 'Updated Author',
            'genre' => 'Non-Fiction',
            'published_at' => '2023-06-01',
            'amount_words' => 60000,
            'price' => 12.99,
        ];

        // Act: вcall the update method
        $response = $this->patchJson("/api/books/{$book->id}", $data);

        // Assert: check the response
        $response->assertStatus(200)
                 ->assertJson([
                        'status' => 'success',
                        'data' => [
                            ...$data,
                            'id' => $book->id,
                            'currency' => 'USD'
                        ],
                 ]);

        // Additional check that the book was actually updated
        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function it_should_return_404_if_updating_non_existent_book()
    {
        // New data for updating
        $data = [
            'title' => 'Updated Title',
            'publisher' => 'Updated Publisher',
            'author' => 'Updated Author',
            'genre' => 'Non-Fiction',
            'published_at' => '2023-06-01',
            'amount_words' => 60000,
            'price' => 12.99,
        ];

        // Act: call the update method with non-existent id
        $response = $this->patchJson('/api/books/999', $data);

        // Assert: check the response status
        $response->assertStatus(404)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Book not found',
                ]);
    }

    /** @test */
    public function it_should_delete_a_book()
    {
        // Arrange: create a book
        $book = Book::factory()->create();

        // Act: call the delete method
        $response = $this->deleteJson("/api/books/{$book->id}");

        // Assert: check the response
        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Book deleted successfully',
                ]);

        // Additional check that the book was actually deleted
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /** @test */
    public function it_should_return_404_if_deleting_non_existent_book()
    {
        // Act: call the delete method with non-existent id
        $response = $this->deleteJson('/api/books/999');

        // Assert: check the response status
        $response->assertStatus(404)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Book not found',
                ]);
    }
}
