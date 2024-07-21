<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use Illuminate\Http\Request;

class BookService
{
    /**
     * Fields that can be updated
     * @var array
     */
    protected array $fields;

    /**
     * BookService constructor
     */
    public function __construct() {
        $this->fields = [
            'title',
            'publisher',
            'author',
            'genre',
            'published_at',
            'amount_words',
            'price'
        ];
    }

    /**
     * Get all books
     * 
     * @return array|null
     */
    public function getCollection(): ?array
    {
        $books = Book::all()->map(fn ($book) => [
            'id' => $book->id,
            'title' => $book->title,
            'publisher' => $book->publisher,
            'author' => $book->author,
            'genre' => $book->genre,
            'published_at' => $book->published_at,
            'amount_words' => $book->amount_words,
            'price' => $book->price,
            'currency' => 'USD'
        ]);

        return $books->count() ? $books->toArray() : null;
    }

    /**
     * Get a book by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getByID(int $id): ?array
    {
        if ($book = Book::where('id', $id)->first()) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'publisher' => $book->publisher,
                'author' => $book->author,
                'genre' => $book->genre,
                'published_at' => $book->published_at,
                'amount_words' => $book->amount_words,
                'price' => $book->price,
                'currency' => 'USD'
            ];
        }

        return null;
    }

    /**
     * Create a new book
     * 
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        $book = Book::create([
            'title' => $request->title,
            'publisher' => $request->publisher,
            'author' => $request->author,
            'genre' => $request->genre,
            'published_at' => $request->published_at,
            'amount_words' => $request->amount_words,
            'price' => $request->price
        ])->toArray();

        return [
            ...$book,
            'currency' => 'USD'
        ];
    }

    /**
     * Update a book
     * 
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, int $id): ?array
    {
        $book = Book::find($id);

        if (! $book) {
            return null;
        }

        foreach ($this->fields as $field) {
            if ($request->$field) {
                $book->$field = $request->$field;
            }
        }

        $book->save();

        return [
            ...$book->toArray(),
            'currency' => 'USD'
        ];
    }

    /**
     * Delete a book
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return Book::where('id', $id)->delete() ? true : false;
    }
}
