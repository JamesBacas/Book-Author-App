@extends('layouts.app')

@section('title', 'Authors List')

@section('content')
<div class="space-y-6">
    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Authors</h1>
            <p class="text-sm text-slate-500">Manage author profiles and their published books</p>
        </div>
        <div>
            <a href="{{ route('authors.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                + Add Author
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('authors.index') }}" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Search author name..." 
                class="w-full sm:w-80 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-md text-sm font-medium hover:bg-slate-700 transition">
                Search
            </button>
            @if($search)
                <a href="{{ route('authors.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-md text-sm font-medium hover:bg-slate-300 transition flex items-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Authors Table -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Name</th>
                    <th class="py-3 px-4 text-left">Birth Date</th>
                    <th class="py-3 px-4 text-left">Books Count</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-700">
                @forelse($authors as $author)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">#{{ $author->id }}</td>
                        <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                            <a href="{{ route('authors.show', $author) }}">{{ $author->name }}</a>
                        </td>
                        <td class="py-3 px-4">{{ $author->birth_date->format('M d, Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('authors.show', $author) }}" class="text-slate-600 hover:text-slate-900">View</a>
                            <a href="{{ route('authors.edit', $author) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('authors.destroy', $author) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this author? All associated books will be deleted as well.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            No authors found.
                            @if($search)
                                Try resetting your search.
                            @else
                                <a href="{{ route('authors.create') }}" class="text-indigo-600 underline">Create one now</a>.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $authors->links() }}
    </div>
</div>
@endsection
