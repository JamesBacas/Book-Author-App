<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucwords($this->faker->words(rand(2, 5), true)),
            'author_id' => \App\Models\Author::factory(),
            'published_date' => $this->faker->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),
        ];
    }
}
