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

    public function test_can_search_books_by_published_date_and_author_name()
    {
        $author1 = Author::factory()->create(['name' => 'F. Scott Fitzgerald']);
        $author2 = Author::factory()->create(['name' => 'Herman Melville']);

        Book::factory()->create(['title' => 'The Great Gatsby', 'author_id' => $author1->id, 'published_date' => '1925-04-10']);
        Book::factory()->create(['title' => 'Moby Dick', 'author_id' => $author2->id, 'published_date' => '1851-10-18']);

        // Search by author name
        $response1 = $this->get(route('books.index', ['search' => 'Fitzgerald']));
        $response1->assertStatus(200);
        $response1->assertSee('The Great Gatsby');
        $response1->assertDontSee('Moby Dick');

        // Search by published date year
        $response2 = $this->get(route('books.index', ['search' => '1851']));
        $response2->assertStatus(200);
        $response2->assertSee('Moby Dick');
        $response2->assertDontSee('The Great Gatsby');
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

    public function test_can_create_book_via_ajax()
    {
        $author = Author::factory()->create();

        $data = [
            'title' => 'The Catcher in the Rye',
            'author_id' => $author->id,
            'published_date' => '1951-07-16',
        ];

        $response = $this->postJson(route('books.store'), $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('books', ['title' => 'The Catcher in the Rye']);
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
