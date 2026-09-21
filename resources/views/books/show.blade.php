@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Navigation Back -->
    <div>
        <a href="{{ route('books.index') }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to Books</a>
    </div>

    <!-- Book Details Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 space-y-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded">Book Details</span>
                <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $book->title }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('books.edit', $book) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 shadow-sm">
                    Edit Book
                </a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this book?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded-md text-sm font-medium hover:bg-rose-700 shadow-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div>
                <dt class="font-medium text-slate-500">Author</dt>
                <dd class="mt-1 font-semibold text-slate-900 text-base">
                    @if($book->author)
                        <a href="{{ route('authors.show', $book->author) }}" class="text-indigo-600 hover:underline">
                            {{ $book->author->name }}
                        </a>
                    @else
                        <span class="text-slate-400 italic">Unknown</span>
                    @endif
                </dd>
            </div>

            <div>
                <dt class="font-medium text-slate-500">Published Date</dt>
                <dd class="mt-1 font-semibold text-slate-900 text-base">
                    {{ $book->published_date->format('F j, Y') }}
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
