# Freelance Job & Invoice Tracker

Laravel 13 web application for freelancers to manage clients, projects, invoices, payments, dashboard metrics, and income reports. This README documents verified behavior through Sprint 3.

## Tech Stack

- Laravel 13
- PHP 8.3+
- Blade
- Tailwind CSS with Vite
- SQLite for local development
- PHPUnit feature tests

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
php artisan migrate:fresh --seed --no-interaction
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

## Verified Features

### Sprint 1

- User login and logout.
- Authentication protection for application pages.
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

### Sprint 2

- Authenticated invoice management pages:
  - create invoices;
  - list invoices;
  - view invoice details;
  - update invoices;
  - delete invoices.
- Invoice numbers are generated automatically using the format `INV-YYYYMM-XXXX`.
- One project can have multiple invoices.
- Authenticated payment tracking from invoice details:
  - create multiple payments for one invoice;
  - update payments;
  - delete payments.
- Payment changes automatically recalculate invoice status for `Partial`, `Paid`, and back to `Sent` when no payments remain after updating or deleting paid/partial payments.

### Sprint 3

- Authenticated dashboard page.
- Dashboard summary metrics:
  - total clients;
  - active projects;
  - unpaid invoice total;
  - total income from recorded payments.
- Dashboard overdue invoice list for unpaid and non-cancelled invoices whose due date has passed.
- Dashboard latest payments list showing the five most recent payments.
- Authenticated income report page.
- Income report payment filter by `start_date` and `end_date` based on payment date.
- Filtered income total.
- Invoice status recap grouped by status.
- Excel export for the income report as `income-report.xlsx`.

## Business Rules

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

### Invoices

- An invoice must belong to an existing project.
- `issue date` is required.
- `due date` is required and cannot be earlier than `issue date`.
- `amount` is required and must be greater than zero.
- `status` is required and must be one of:
  - `Draft`
  - `Sent`
  - `Partial`
  - `Paid`
  - `Overdue`
  - `Cancelled`
- Invoice numbers are created by the application and must remain unique.
- The total invoice amount for one project cannot exceed the project value.
- An invoice amount cannot be reduced below the total payments already recorded for that invoice.

### Payments

- A payment must belong to an existing invoice.
- `payment date` is required.
- `amount` is required and must be greater than zero.
- `method`, `reference`, and `notes` are optional.
- One invoice can have multiple payments.
- Total payments for one invoice cannot exceed the invoice amount.
- Creating, updating, or deleting a payment recalculates the invoice status:
  - no payment on a previously `Partial` or `Paid` invoice sets status back to `Sent`;
  - partial payment sets status to `Partial`;
  - full payment sets status to `Paid`.

## Dashboard And Report Behavior

- `/dashboard` is available only after login.
- `/dashboard` shows client count, active project count, unpaid invoice total, total income, overdue invoices, and five latest payments.
- `/reports/income` is available only after login.
- The income report filters payments by payment date using optional `start_date` and `end_date` query parameters.
- The income report total is calculated from payments matching the selected date filter.
- The invoice status recap lists all supported invoice statuses with count and total invoice amount per status.
- `/reports/income/export` downloads the filtered report as `income-report.xlsx`.
- The Excel file contains payment date, client, project, invoice number, method, reference, amount, and total rows.

## Database Impact

Sprint 1 adds these application tables:

- `clients`: stores freelancer client contact and company information.
- `projects`: stores project records, status, dates, and project value. Each project belongs to one client.

Sprint 2 adds these application tables:

- `invoices`: stores project invoice records, generated invoice number, issue date, due date, amount, notes, and status. Each invoice belongs to one project. Project deletion is restricted when invoices exist.
- `payments`: stores payment records for invoices, including payment date, amount, optional method, optional reference, and optional notes. Payments are deleted when their invoice is deleted.

Sprint 3 adds no new database tables or external package dependencies.

## Test Report

Final verified command results after Sprint 3:

```bash
php artisan migrate:fresh --seed --no-interaction
```

Result: passed.

```bash
php artisan test
```

Result: passed with `27 tests, 158 assertions`.

```powershell
.\vendor\bin\pint --test
```

Result: passed.

```bash
npm run build
```

Result: passed. The build showed an optional `fontaine` notice and still exited successfully.

QA verdict: `PASS WITH NOTES`.

Defects found: none.

## Known Limitations

- Excel export was validated as an OpenXML ZIP with worksheet content, but was not manually opened in desktop Microsoft Excel during QA.
- The application uses a single Admin/Freelancer role only.
- Public API is not included.
- Docker support is not included.
- Production deployment automation is not included.
- External integrations and paid services are not included.

## Local Demo Handover Notes

- Use `php artisan migrate:fresh --seed --no-interaction` before a clean local demo.
- Login with `demo@example.com` and `password`.
- Create clients and projects first, then create invoices from projects and record payments from invoice details.
- Use the dashboard to review current metrics, overdue invoices, and latest payments.
- Use the income report page to filter payments by date range and export the filtered result to `.xlsx`.
- Keep `.env` local and do not commit secrets or production credentials.
