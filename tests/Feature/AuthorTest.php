<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_authors()
    {
        $authors = Author::factory()->count(3)->create();

        $response = $this->get(route('authors.index'));

        $response->assertStatus(200);
        $response->assertSee($authors->first()->name);
    }

    public function test_can_search_authors_by_name()
    {
        Author::factory()->create(['name' => 'George Orwell']);
        Author::factory()->create(['name' => 'Jane Austen']);

        $response = $this->get(route('authors.index', ['search' => 'Orwell']));

        $response->assertStatus(200);
        $response->assertSee('George Orwell');
        $response->assertDontSee('Jane Austen');
    }

    public function test_can_search_authors_by_birth_date()
    {
        Author::factory()->create(['name' => 'George Orwell', 'birth_date' => '1903-06-25']);
        Author::factory()->create(['name' => 'Jane Austen', 'birth_date' => '1775-12-16']);

        $response = $this->get(route('authors.index', ['search' => '1903']));

        $response->assertStatus(200);
        $response->assertSee('George Orwell');
        $response->assertDontSee('Jane Austen');
    }

    public function test_can_create_author()
    {
        $data = [
            'name' => 'Ernest Hemingway',
            'birth_date' => '1899-07-21',
        ];

        $response = $this->post(route('authors.store'), $data);

        $response->assertRedirect(route('authors.index'));
        $this->assertDatabaseHas('authors', ['name' => 'Ernest Hemingway']);
    }

    public function test_can_create_author_via_ajax()
    {
        $data = [
            'name' => 'Virginia Woolf',
            'birth_date' => '1882-01-25',
        ];

        $response = $this->postJson(route('authors.store'), $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('authors', ['name' => 'Virginia Woolf']);
    }

    public function test_author_validation_fails_with_invalid_data()
    {
        $response = $this->post(route('authors.store'), [
            'name' => '',
            'birth_date' => 'not-a-date',
        ]);

        $response->assertSessionHasErrors(['name', 'birth_date']);
    }

    public function test_can_show_author_with_books()
    {
        $author = Author::factory()->has(Book::factory()->count(2))->create();

        $response = $this->get(route('authors.show', $author));

        $response->assertStatus(200);
        $response->assertSee($author->name);
        $response->assertSee($author->books->first()->title);
    }

    public function test_can_update_author()
    {
        $author = Author::factory()->create();

        $response = $this->put(route('authors.update', $author), [
            'name' => 'Updated Name',
            'birth_date' => '1950-01-01',
        ]);

        $response->assertRedirect(route('authors.show', $author));
        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_author()
    {
        $author = Author::factory()->create();

        $response = $this->delete(route('authors.destroy', $author));

        $response->assertRedirect(route('authors.index'));
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
