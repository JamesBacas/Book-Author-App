<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $books = Book::with('author')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('published_date', 'like', "%{$search}%")
                      ->orWhereHas('author', function ($aq) use ($search) {
                          $aq->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('books.index', compact('books', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $authors = Author::orderBy('name')->get();
        $selectedAuthorId = $request->query('author_id');

        return view('books.create', compact('authors', 'selectedAuthorId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        $book->load('author');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Book '{$book->title}' created successfully.",
                'book' => $book,
                'formatted_published_date' => $book->published_date->format('M d, Y'),
                'author_name' => $book->author ? $book->author->name : 'Unknown',
                'author_url' => $book->author ? route('authors.show', $book->author) : '#',
                'show_url' => route('books.show', $book),
                'edit_url' => route('books.edit', $book),
                'destroy_url' => route('books.destroy', $book),
            ]);
        }

        return redirect()->route('books.index')
            ->with('success', "Book '{$book->title}' created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('author');

        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();

        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Book '{$book->title}' updated successfully.",
                'book' => $book,
            ]);
        }

        return redirect()->route('books.show', $book)
            ->with('success', "Book '{$book->title}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Book $book)
    {
        $title = $book->title;
        $book->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Book '{$title}' deleted successfully.",
            ]);
        }

        return redirect()->route('books.index')
            ->with('success', "Book '{$title}' deleted successfully.");
    }
}
