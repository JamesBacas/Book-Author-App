@extends('layouts.app')

@section('title', 'Books List')

@section('content')
<div class="space-y-6">
    <!-- Header Actions & Search -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Books</h1>
            <p class="text-sm text-slate-500">Manage book titles, authors, and publication dates</p>
        </div>
        <div class="flex items-center space-x-2">
            <button id="toggleAjaxFormBtn" onclick="toggleAjaxForm()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition">
                + Quick Add (AJAX)
            </button>
            <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition">
                Full Form
            </a>
        </div>
    </div>

    <!-- AJAX Notification Banner -->
    <div id="ajaxBanner" class="hidden p-4 rounded-md flex items-center justify-between border">
        <span id="ajaxBannerText"></span>
        <button onclick="document.getElementById('ajaxBanner').classList.add('hidden')" class="text-sm font-bold ml-4">&times;</button>
    </div>

    <!-- Collapsible Quick Add Form (AJAX Submission) -->
    <div id="ajaxFormCard" class="hidden bg-white p-6 rounded-lg shadow-sm border border-indigo-200 bg-indigo-50/20">
        <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center space-x-2">
            <span>⚡ Quick Add Book</span>
            <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded font-mono">AJAX Enabled</span>
        </h3>
        <form id="ajaxBookForm" onsubmit="submitBookAjax(event)" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            @csrf
            <div>
                <label for="ajax_title" class="block text-sm font-medium text-slate-700">Book Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="ajax_title" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g. One Hundred Years of Solitude"
                />
                <p id="error_title" class="mt-1 text-xs text-rose-600 hidden"></p>
            </div>
            <div>
                <label for="ajax_author_id" class="block text-sm font-medium text-slate-700">Author</label>
                <select 
                    name="author_id" 
                    id="ajax_author_id" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="">-- Select Author --</option>
                    @foreach(\App\Models\Author::orderBy('name')->get() as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
                <p id="error_author_id" class="mt-1 text-xs text-rose-600 hidden"></p>
            </div>
            <div>
                <label for="ajax_published_date" class="block text-sm font-medium text-slate-700">Published Date</label>
                <input 
                    type="date" 
                    name="published_date" 
                    id="ajax_published_date" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
                <p id="error_published_date" class="mt-1 text-xs text-rose-600 hidden"></p>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" id="ajaxSubmitBtn" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700 shadow-sm transition">
                    Save via AJAX
                </button>
            </div>
        </form>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200">
        <form method="GET" action="{{ route('books.index') }}" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Search by title, author name, or year (e.g. 1967)..." 
                class="w-full sm:w-96 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-md text-sm font-medium hover:bg-slate-700 transition">
                Search
            </button>
            @if($search)
                <a href="{{ route('books.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-md text-sm font-medium hover:bg-slate-300 transition flex items-center">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Books Table -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-xs">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Title</th>
                    <th class="py-3 px-4 text-left">Author</th>
                    <th class="py-3 px-4 text-left">Published Date</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="booksTbody" class="divide-y divide-slate-200 text-slate-700">
                @forelse($books as $book)
                    <tr id="book_row_{{ $book->id }}" class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">#{{ $book->id }}</td>
                        <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                            <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                        </td>
                        <td class="py-3 px-4">
                            @if($book->author)
                                <a href="{{ route('authors.show', $book->author) }}" class="text-slate-900 hover:text-indigo-600 font-medium">
                                    {{ $book->author->name }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">Unknown</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $book->published_date->format('M d, Y') }}</td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('books.show', $book) }}" class="text-slate-600 hover:text-slate-900">View</a>
                            <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button onclick="deleteBookAjax({{ $book->id }}, '{{ addslashes($book->title) }}')" class="text-rose-600 hover:text-rose-900">Delete (AJAX)</button>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="py-8 text-center text-slate-500">
                            No books found.
                            @if($search)
                                Try resetting your search.
                            @else
                                <a href="{{ route('books.create') }}" class="text-indigo-600 underline">Add one now</a>.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>

<script>
function toggleAjaxForm() {
    const card = document.getElementById('ajaxFormCard');
    card.classList.toggle('hidden');
}

function showBanner(message, type = 'success') {
    const banner = document.getElementById('ajaxBanner');
    const text = document.getElementById('ajaxBannerText');
    banner.className = `p-4 rounded-md flex items-center justify-between border ${
        type === 'success' 
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800' 
            : 'bg-rose-50 border-rose-200 text-rose-800'
    }`;
    text.innerText = message;
    banner.classList.remove('hidden');
}

function clearErrors() {
    document.getElementById('error_title').classList.add('hidden');
    document.getElementById('error_author_id').classList.add('hidden');
    document.getElementById('error_published_date').classList.add('hidden');
}

async function submitBookAjax(e) {
    e.preventDefault();
    clearErrors();
    const btn = document.getElementById('ajaxSubmitBtn');
    btn.disabled = true;
    btn.innerText = 'Saving...';

    const form = document.getElementById('ajaxBookForm');
    const formData = new FormData(form);

    try {
        const response = await fetch("{{ route('books.store') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showBanner(data.message, 'success');
            form.reset();

            // Prepend new row to table without reloading page
            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('booksTbody');
            const newRowHtml = `
                <tr id="book_row_${data.book.id}" class="bg-emerald-50/50 hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-mono text-slate-400">#${data.book.id}</td>
                    <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                        <a href="${data.show_url}">${data.book.title}</a>
                    </td>
                    <td class="py-3 px-4">
                        <a href="${data.author_url}" class="text-slate-900 hover:text-indigo-600 font-medium">
                            ${data.author_name}
                        </a>
                    </td>
                    <td class="py-3 px-4">${data.formatted_published_date}</td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="${data.show_url}" class="text-slate-600 hover:text-slate-900">View</a>
                        <a href="${data.edit_url}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <button onclick="deleteBookAjax(${data.book.id}, '${data.book.title}')" class="text-rose-600 hover:text-rose-900">Delete (AJAX)</button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('afterbegin', newRowHtml);
        } else if (response.status === 422) {
            const errors = data.errors || {};
            if (errors.title) {
                const el = document.getElementById('error_title');
                el.innerText = errors.title[0];
                el.classList.remove('hidden');
            }
            if (errors.author_id) {
                const el = document.getElementById('error_author_id');
                el.innerText = errors.author_id[0];
                el.classList.remove('hidden');
            }
            if (errors.published_date) {
                const el = document.getElementById('error_published_date');
                el.innerText = errors.published_date[0];
                el.classList.remove('hidden');
            }
        }
    } catch (err) {
        showBanner('An unexpected error occurred while saving book via AJAX.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Save via AJAX';
    }
}

async function deleteBookAjax(id, title) {
    if (!confirm(`Are you sure you want to delete '${title}' via AJAX?`)) return;

    try {
        const response = await fetch(`/books/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();
        if (response.ok && data.success) {
            showBanner(data.message, 'success');
            const row = document.getElementById(`book_row_${id}`);
            if (row) row.remove();
        }
    } catch (err) {
        showBanner('Failed to delete book via AJAX.', 'error');
    }
}
</script>
@endsection
