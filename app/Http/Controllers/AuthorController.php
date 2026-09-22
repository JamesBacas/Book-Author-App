<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $authors = Author::withCount('books')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('birth_date', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('authors.index', compact('authors', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());
        $author->loadCount('books');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Author '{$author->name}' created successfully.",
                'author' => $author,
                'formatted_birth_date' => $author->birth_date->format('M d, Y'),
                'show_url' => route('authors.show', $author),
                'edit_url' => route('authors.edit', $author),
                'destroy_url' => route('authors.destroy', $author),
            ]);
        }

        return redirect()->route('authors.index')
            ->with('success', "Author '{$author->name}' created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author->load('books');

        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Author '{$author->name}' updated successfully.",
                'author' => $author,
            ]);
        }

        return redirect()->route('authors.show', $author)
            ->with('success', "Author '{$author->name}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Author $author)
    {
        $name = $author->name;
        $author->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Author '{$name}' deleted successfully.",
            ]);
        }

        return redirect()->route('authors.index')
            ->with('success', "Author '{$name}' deleted successfully.");
    }
}
