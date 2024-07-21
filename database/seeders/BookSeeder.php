<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Keys for the data.
     */
    private static $keys = [
        'title',
        'publisher',
        'author',
        'genre',
        'published_at',
        'amount_words',
        'price',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'The Great Gatsby',
                'Scribner',
                'F. Scott Fitzgerald',
                'Novel',
                '1925-04-10',
                47000,
                9.99,
            ],
            [
                'To Kill a Mockingbird',
                'J. B. Lippincott & Co.',
                'Harper Lee',
                'Novel',
                '1960-07-11',
                100000,
                7.99,
            ],
            [
                '1984',
                'Secker & Warburg',
                'George Orwell',
                'Novel',
                '1949-06-08',
                88000,
                6.99,
            ],
            [
                'The Catcher in the Rye',
                'Little, Brown and Company',
                'J. D. Salinger',
                'Novel',
                '1951-07-16',
                73000,
                8.99,
            ],
            [
                'The Hobbit',
                'Allen & Unwin',
                'J. R. R. Tolkien',
                'Fantasy',
                '1937-09-21',
                95000,
                11.99,
            ],
            [
                'The Lord of the Rings',
                'Allen & Unwin',
                'J. R. R. Tolkien',
                'Fantasy',
                '1954-07-29',
                455125,
                19.99,
            ],
            [
                'The Da Vinci Code',
                'Doubleday',
                'Dan Brown',
                'Mystery',
                '2003-03-18',
                137000,
                14.99,
            ],
            [
                'The Alchemist',
                'HarperCollins',
                'Paulo Coelho',
                'Novel',
                '1988-01-01',
                45000,
                10.99,
            ],
        ];

        foreach ($data as $item) {
            $combine = array_combine(self::$keys, $item);
            Book::create($combine);
        }
    }
}
