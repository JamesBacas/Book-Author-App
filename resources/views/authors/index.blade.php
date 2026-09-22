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
        <div class="flex items-center space-x-2">
            <button id="toggleAjaxFormBtn" onclick="toggleAjaxForm()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition">
                + Quick Add (AJAX)
            </button>
            <a href="{{ route('authors.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 transition">
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
            <span>⚡ Quick Add Author</span>
            <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded font-mono">AJAX Enabled</span>
        </h3>
        <form id="ajaxAuthorForm" onsubmit="submitAuthorAjax(event)" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @csrf
            <div>
                <label for="ajax_name" class="block text-sm font-medium text-slate-700">Author Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="ajax_name" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g. Gabriel García Márquez"
                />
                <p id="error_name" class="mt-1 text-xs text-rose-600 hidden"></p>
            </div>
            <div>
                <label for="ajax_birth_date" class="block text-sm font-medium text-slate-700">Birth Date</label>
                <input 
                    type="date" 
                    name="birth_date" 
                    id="ajax_birth_date" 
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
                <p id="error_birth_date" class="mt-1 text-xs text-rose-600 hidden"></p>
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
        <form method="GET" action="{{ route('authors.index') }}" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Search by author name or birth date (e.g. 1965)..." 
                class="w-full sm:w-96 px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
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
            <tbody id="authorsTbody" class="divide-y divide-slate-200 text-slate-700">
                @forelse($authors as $author)
                    <tr id="author_row_{{ $author->id }}" class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono text-slate-400">#{{ $author->id }}</td>
                        <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                            <a href="{{ route('authors.show', $author) }}">{{ $author->name }}</a>
                        </td>
                        <td class="py-3 px-4">{{ $author->birth_date->format('M d, Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $author->books_count }} {{ \Illuminate\Support\Str::plural('book', $author->books_count) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('authors.show', $author) }}" class="text-slate-600 hover:text-slate-900">View</a>
                            <a href="{{ route('authors.edit', $author) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button onclick="deleteAuthorAjax({{ $author->id }}, '{{ addslashes($author->name) }}')" class="text-rose-600 hover:text-rose-900">Delete (AJAX)</button>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
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
    document.getElementById('error_name').classList.add('hidden');
    document.getElementById('error_birth_date').classList.add('hidden');
}

async function submitAuthorAjax(e) {
    e.preventDefault();
    clearErrors();
    const btn = document.getElementById('ajaxSubmitBtn');
    btn.disabled = true;
    btn.innerText = 'Saving...';

    const form = document.getElementById('ajaxAuthorForm');
    const formData = new FormData(form);

    try {
        const response = await fetch("{{ route('authors.store') }}", {
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

            const tbody = document.getElementById('authorsTbody');
            const newRowHtml = `
                <tr id="author_row_${data.author.id}" class="bg-emerald-50/50 hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-mono text-slate-400">#${data.author.id}</td>
                    <td class="py-3 px-4 font-semibold text-indigo-600 hover:text-indigo-900">
                        <a href="${data.show_url}">${data.author.name}</a>
                    </td>
                    <td class="py-3 px-4">${data.formatted_birth_date}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            0 books
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="${data.show_url}" class="text-slate-600 hover:text-slate-900">View</a>
                        <a href="${data.edit_url}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <button onclick="deleteAuthorAjax(${data.author.id}, '${data.author.name}')" class="text-rose-600 hover:text-rose-900">Delete (AJAX)</button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('afterbegin', newRowHtml);
        } else if (response.status === 422) {
            const errors = data.errors || {};
            if (errors.name) {
                const el = document.getElementById('error_name');
                el.innerText = errors.name[0];
                el.classList.remove('hidden');
            }
            if (errors.birth_date) {
                const el = document.getElementById('error_birth_date');
                el.innerText = errors.birth_date[0];
                el.classList.remove('hidden');
            }
        }
    } catch (err) {
        showBanner('An unexpected error occurred while saving author via AJAX.', 'error');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Save via AJAX';
    }
}

async function deleteAuthorAjax(id, name) {
    if (!confirm(`Are you sure you want to delete '${name}' via AJAX?`)) return;

    try {
        const response = await fetch(`/authors/${id}`, {
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
            const row = document.getElementById(`author_row_${id}`);
            if (row) row.remove();
        }
    } catch (err) {
        showBanner('Failed to delete author via AJAX.', 'error');
    }
}
</script>
@endsection
