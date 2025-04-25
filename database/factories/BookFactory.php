<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(50, 200),
            // 'image_url' => $this->faker->imageUrl(200, 300, 'books'),
        ];
    }

    public function withAuthors($count = 1)
    {
        return $this->afterCreating(function (Book $book) use ($count) {
            $authors = Author::factory()->count($count)->create();
            $book->authors()->attach($authors);
        });
    }
}
