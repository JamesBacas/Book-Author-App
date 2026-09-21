@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Add New Book</h1>
        <a href="{{ route('books.index') }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to Books</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
        <form action="{{ route('books.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Book Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="e.g. The Fellowship of the Ring"
                />
                @error('title')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="author_id" class="block text-sm font-medium text-slate-700">Author</label>
                <select 
                    name="author_id" 
                    id="author_id" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                >
                    <option value="">-- Select Author --</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id', $selectedAuthorId) == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="published_date" class="block text-sm font-medium text-slate-700">Published Date</label>
                <input 
                    type="date" 
                    name="published_date" 
                    id="published_date" 
                    value="{{ old('published_date') }}" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                />
                @error('published_date')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('books.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-md text-sm font-medium hover:bg-slate-200">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 shadow-sm">Save Book</button>
            </div>
        </form>
    </div>
</div>
@endsection
