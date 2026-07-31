# Freelance Job & Invoice Tracker

Laravel 13 web application for freelancers to manage clients, projects, invoices, payments, dashboard metrics, income reports, v2 role-based access control, and v2 project workflow foundation. This README documents verified behavior through v2 Sprint 2.

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

### v2 Sprint 1

- Architecture baseline documented in `DECISIONS.md`.
- Flexible RBAC foundation using database-backed roles and permissions.
- Seeded roles:
  - `Admin`
  - `Finance`
  - `Project Manager`
  - `Viewer`
- Demo account is assigned the `Admin` role during seeding.
- Existing stable modules are protected by permission middleware.
- Admin can access existing stable modules.
- Viewer can view allowed modules but cannot create, update, or delete.
- Finance can access dashboard, invoice, payment, income report, and report export areas.
- Project Manager can access dashboard, client, and project areas.
- Blade navigation and action buttons follow backend permissions.

### v2 Sprint 2

- Project workflow foundation on project detail pages.
- Milestone CRUD baseline:
  - create milestones;
  - view milestones;
  - update milestone title, target date, weight, and description;
  - soft delete milestones.
- Task CRUD baseline:
  - create tasks with optional milestone link;
  - view tasks;
  - update task title, optional milestone, assignee, priority, due date, description, progress, and status;
  - soft delete tasks.
- Task status is limited to:
  - `Backlog`
  - `To Do`
  - `In Progress`
  - `Review`
  - `Done`
  - `Cancelled`
- Task priority is limited to `Low`, `Medium`, `High`, and `Urgent`.
- Project progress is stored in `projects.progress`.
- Project progress is recalculated by `ProjectWorkflowService` after milestone or task changes.
- Lightweight project activity timeline records milestone/task create, update, and delete events.
- Admin and Project Manager can manage project workflow data.
- Viewer can read project workflow data but cannot create, update, or delete it.
- Finance is blocked from project workflow access and writes under the current permission mapping.

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
- `progress` stores the verified v2 Sprint 2 project workflow progress percentage.

### Project Milestones

- A milestone must belong to a project.
- `title` is required.
- `target date` is required and must be a valid date.
- `weight` is required and must be an integer from 1 to 100.
- `description` is optional.
- `progress` is stored as an integer percentage from 0 to 100.
- Deleted milestones are soft-deleted.

### Project Tasks

- A task must belong to a project.
- A task can optionally belong to a project milestone from the same project.
- `title` is required.
- `assignee` is optional.
- `priority` is required and must be one of `Low`, `Medium`, `High`, or `Urgent`.
- `due date` is optional and must be a valid date when provided.
- `description` is optional.
- `progress` is required and must be an integer from 0 to 100.
- `status` is required and must be one of `Backlog`, `To Do`, `In Progress`, `Review`, `Done`, or `Cancelled`.
- Deleted tasks are soft-deleted.

### Project Progress

- If active milestones exist, project progress uses the weighted average of milestone progress.
- Milestone progress is the average progress of its active tasks.
- A milestone without active tasks contributes `0%`.
- If no active milestones exist, project progress uses the average progress of active project tasks.
- If no workflow records exist, project progress is `0%`.
- The calculated value is rounded and stored in `projects.progress`.

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

v2 Sprint 1 adds these RBAC tables:

- `roles`: stores role names and slugs.
- `permissions`: stores permission names and slugs.
- `permission_role`: stores role-permission assignments.
- `role_user`: stores user-role assignments.

v2 Sprint 2 updates project workflow storage:

- `projects.progress`: stores the calculated project progress percentage.
- `project_milestones`: stores milestone title, optional description, target date, weight, calculated progress, timestamps, and soft delete state.
- `project_tasks`: stores task title, optional milestone link, optional assignee, priority, optional due date, optional description, progress, status, timestamps, and soft delete state.
- `project_activities`: stores lightweight project workflow timeline events for milestone/task create, update, and delete actions.

Sprint 1 adds these application tables:

- `clients`: stores freelancer client contact and company information.
- `projects`: stores project records, status, dates, and project value. Each project belongs to one client.

Sprint 2 adds these application tables:

- `invoices`: stores project invoice records, generated invoice number, issue date, due date, amount, notes, and status. Each invoice belongs to one project. Project deletion is restricted when invoices exist.
- `payments`: stores payment records for invoices, including payment date, amount, optional method, optional reference, and optional notes. Payments are deleted when their invoice is deleted.

Sprint 3 adds no new database tables or external package dependencies.

v2 Sprint 1 adds no external package dependencies.

## RBAC Mapping

- Admin: all current permissions.
- Finance: `dashboard.view`, invoice view/create/update/delete, payment create/update/delete, report view/export.
- Project Manager: `dashboard.view`, client view/create/update/delete, project view/create/update/delete, project workflow view/manage.
- Viewer: `dashboard.view`, `clients.view`, `projects.view`, project workflow view, `invoices.view`, `reports.view`.
- Finance: no project workflow view/manage permission in the current mapping.

## Test Report

Final verified command results after v2 Sprint 2:

```bash
php artisan migrate:fresh --seed --no-interaction
```

Result: passed.

RBAC migration rollback check: passed.

Project workflow migration rollback check: passed.

```bash
php artisan test
```

Result after v2 Sprint 2: passed with 40 tests and 272 assertions.

```powershell
.\vendor\bin\pint --test
```

Result: passed.

```bash
npm run build
```

Result: passed. The build showed an optional `fontaine` notice and still exited successfully.

QA verdict after v2 Sprint 2: PASS.

Defects found: none.

## Known Limitations

- Excel export now generates a fuller `.xlsx` OpenXML package with workbook metadata, styles, worksheet dimension, XML validation coverage, and worksheet content checks.
- RBAC is implemented for current Blade modules only.
- Project workflow is a baseline CRUD and progress calculation implementation.
- Project activity timeline is lightweight workflow history only, not a full audit log.
- Drag-and-drop kanban is not included.
- Task attachment upload and document management are not included.
- Expense management and finance report enhancement are not included in v2 Sprint 2.
- Notifications, external storage, public API, mobile app, and advanced workflow automation are not included.
- Audit logs and advanced role-management UI are not included.
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
