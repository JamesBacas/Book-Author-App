# Laravel 12 Book & Author CRUD Application

A full-featured, standard Laravel 12 web application built for managing Books and Authors with full CRUD functionality, SQLite database, Eloquent relationships, Form Request validation, Tailwind CSS Blade views, and search capability.

---

## Features

- **Resource Controllers**: RESTful routes and resource controllers (`AuthorController`, `BookController`) implementing all 7 CRUD actions (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`).
- **Eloquent Relationships**:
  - `Author` model `hasMany(Book::class)`
  - `Book` model `belongsTo(Author::class)` with cascading delete on foreign key.
- **Form Request Validation**:
  - `StoreAuthorRequest` & `UpdateAuthorRequest`: `name` (required, string, max:255), `birth_date` (required, date).
  - `StoreBookRequest` & `UpdateBookRequest`: `title` (required, string, max:255), `author_id` (required, exists:authors,id), `published_date` (required, date).
- **Blade Views & Layout**: Clean, modern UI styled with Tailwind CSS, header navigation, flash notifications, pre-populated edit forms, and contextual actions.
- **Search Filtering**:
  - Authors search by name (`?search=...`).
  - Books search by title (`?search=...`).
- **Factories & Seeders**: Realistic sample data using Faker (`AuthorFactory`, `BookFactory`, `DatabaseSeeder`).
- **Automated Feature Tests**: Comprehensive PHPUnit/Pest test coverage for both Author and Book CRUD endpoints.

---

## Requirements

- **PHP**: ^8.2 or PHP 8.5+
- **Composer**: ^2.0
- **SQLite3** extension enabled

---

## Setup Instructions

### 1. Clone the Repository & Navigate to Directory
```bash
git clone <repository-url> book-author-app
cd book-author-app
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
Ensure your `.env` file uses SQLite:
```env
DB_CONNECTION=sqlite
# DB_DATABASE is automatically set to database/database.sqlite by default in Laravel 12
```

Generate the application key:
```bash
php artisan key:generate
```

### 4. Initialize Database & Run Migrations with Seeders
Create the SQLite database file if it does not exist:
```bash
touch database/database.sqlite
```

Run database migrations and seed realistic sample data:
```bash
php artisan migrate:fresh --seed
```

### 5. Run the Local Development Server
Start the local server:
```bash
php artisan serve
```

Visit the application in your web browser:
[http://127.0.0.1:8000](http://127.0.0.1:8000) (redirects to `/authors`).

---

## Running Automated Tests

To execute the automated feature test suite:
```bash
php artisan test
```

---

## Codebase Structure Overview

- `app/Models/Author.php` - Author Eloquent model (`hasMany` Books).
- `app/Models/Book.php` - Book Eloquent model (`belongsTo` Author).
- `app/Http/Controllers/AuthorController.php` - Author resource controller.
- `app/Http/Controllers/BookController.php` - Book resource controller.
- `app/Http/Requests/` - Form request validation classes.
- `database/migrations/` - Database table schemas with FK constraints.
- `database/factories/` - Model factories using Faker.
- `database/seeders/DatabaseSeeder.php` - Populates sample authors & books.
- `resources/views/` - Blade templates (`layouts/app.blade.php`, `authors/`, `books/`).
- `tests/Feature/` - Integration tests for Authors and Books CRUD.
