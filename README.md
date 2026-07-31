# Freelance Job & Invoice Tracker

Laravel 13 application for tracking freelance clients and projects. This README documents the verified Sprint 1 scope only.

## Tech Stack

- Laravel 13
- PHP 8.3+
- Blade
- Tailwind CSS with Vite
- SQLite for local development

## Local Setup

1. Install PHP dependencies.

```bash
composer install
```

2. Install frontend dependencies.

```bash
npm install
```

3. Create the local environment file.

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

4. Generate the application key.

```bash
php artisan key:generate
```

5. Confirm SQLite is configured in `.env`.

```dotenv
DB_CONNECTION=sqlite
```

Do not commit real secrets or production credentials to `.env`.

6. Create the SQLite database file if it does not exist.

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

7. Run migrations and seed demo data.

```bash
php artisan migrate:fresh --seed
```

8. Build frontend assets.

```bash
npm run build
```

9. Start the local server.

```bash
php artisan serve
```

Open the local URL shown by Artisan, usually `http://127.0.0.1:8000`.

## Demo Account

- Email: `demo@example.com`
- Password: `password`

## Sprint 1 Features

- User login and logout.
- Authentication protection for app pages.
- Client management:
  - create clients;
  - view clients;
  - update clients;
  - delete clients when allowed.
- Project management:
  - create projects;
  - view projects;
  - update projects;
  - delete projects.

## Sprint 1 Business Rules

### Clients

- `name` is required.
- `email` is required and must be a valid email address.
- `phone_number` is required.
- `company` is required.
- `address` is required.
- A client cannot be deleted when it has active projects.

### Projects

- A project must belong to a client.
- `name` is required.
- `description` is required.
- `start date` is required.
- `deadline` is required.
- `deadline` cannot be earlier than `start date`.
- `project value` is required.
- `status` is required and must be one of:
  - `Draft`
  - `Active`
  - `Completed`
  - `Cancelled`

## Testing

Verified Sprint 1 command results:

```bash
php artisan test
```

Result: `14 tests, 62 assertions`.

```powershell
.\vendor\bin\pint --test
```

Result: passed.

```bash
npm run build
```

Result: passed.

## Known Limitations

The following items are out of scope for Sprint 1 and are not documented as completed features:

- Invoice management.
- Payment tracking.
- Dashboard metrics.
- Reports or CSV export.
- Docker support.
- Public API.
- Multi-role authorization.
