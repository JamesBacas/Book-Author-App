@extends('layouts.app')

@section('title', 'Edit Author')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Edit Author</h1>
        <a href="{{ route('authors.show', $author) }}" class="text-sm text-slate-600 hover:text-slate-900">&larr; Back to Author</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
        <form action="{{ route('authors.update', $author) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Author Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $author->name) }}" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                />
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-medium text-slate-700">Birth Date</label>
                <input 
                    type="date" 
                    name="birth_date" 
                    id="birth_date" 
                    value="{{ old('birth_date', $author->birth_date->format('Y-m-d')) }}" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                />
                @error('birth_date')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('authors.show', $author) }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-md text-sm font-medium hover:bg-slate-200">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 shadow-sm">Update Author</button>
            </div>
        </form>
    </div>
</div>
@endsection
