<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books()
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_can_search_books_by_title()
    {
        Book::factory()->create(['title' => '1984']);
        Book::factory()->create(['title' => 'Pride and Prejudice']);

        $response = $this->get(route('books.index', ['search' => '1984']));

        $response->assertStatus(200);
        $response->assertSee('1984');
        $response->assertDontSee('Pride and Prejudice');
    }

    public function test_can_create_book()
    {
        $author = Author::factory()->create();

        $data = [
            'title' => 'To Kill a Mockingbird',
            'author_id' => $author->id,
            'published_date' => '1960-07-11',
        ];

        $response = $this->post(route('books.store'), $data);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['title' => 'To Kill a Mockingbird', 'author_id' => $author->id]);
    }

    public function test_book_validation_fails_for_nonexistent_author()
    {
        $response = $this->post(route('books.store'), [
            'title' => 'Sample Title',
            'author_id' => 99999,
            'published_date' => '2020-01-01',
        ]);

        $response->assertSessionHasErrors(['author_id']);
    }

    public function test_can_show_book()
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.show', $book));

        $response->assertStatus(200);
        $response->assertSee($book->title);
        $response->assertSee($book->author->name);
    }

    public function test_can_update_book()
    {
        $book = Book::factory()->create();
        $newAuthor = Author::factory()->create();

        $response = $this->put(route('books.update', $book), [
            'title' => 'Updated Title',
            'author_id' => $newAuthor->id,
            'published_date' => '2021-05-10',
        ]);

        $response->assertRedirect(route('books.show', $book));
        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title', 'author_id' => $newAuthor->id]);
    }

    public function test_can_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
