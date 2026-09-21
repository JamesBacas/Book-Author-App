@extends('layouts.app')

@section('title', $author->name)

@section('content')
<div class="space-y-6">
    <!-- Header & Actions -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-3xl font-bold text-slate-900">{{ $author->name }}</h1>
            </div>
            <p class="text-slate-500 mt-1">Born: {{ $author->birth_date->format('F j, Y') }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('books.create', ['author_id' => $author->id]) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-700 shadow-sm">
                + Add Book for Author
            </a>
            <a href="{{ route('authors.edit', $author) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 shadow-sm">
                Edit Author
            </a>
            <form action="{{ route('authors.destroy', $author) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This will delete the author and all their books.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-md text-sm font-medium hover:bg-rose-700 shadow-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Published Books Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900">Books by {{ $author->name }} ({{ $author->books->count() }})</h2>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Title</th>
                        <th class="py-3 px-4 text-left">Published Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse($author->books as $book)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono text-slate-400">#{{ $book->id }}</td>
                            <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                                <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                            </td>
                            <td class="py-3 px-4">{{ $book->published_date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('books.show', $book) }}" class="text-slate-600 hover:text-slate-900">View</a>
                                <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this book?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                No books found for this author.
                                <a href="{{ route('books.create', ['author_id' => $author->id]) }}" class="text-indigo-600 underline">Add one now</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
