# Freelance Job & Invoice Tracker

Laravel 13 web application for freelancers to manage clients, projects, invoices, payments, expenses, dashboard metrics, finance reports, v2 role-based access control, v2 project workflow foundation, and v2 document attachment foundation. This README documents verified behavior through v2 Sprint 4.

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
- Finance can access dashboard, invoice, payment, expense, finance report, and report export areas.
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

### v2 Sprint 3

- Document metadata and polymorphic attachment foundation.
- Supported attachment targets:
  - Project;
  - Project Task;
  - Client;
  - Invoice;
  - Payment.
- Expense attachment was prepared by the polymorphic design and is implemented through the expense detail document panel in v2 Sprint 4.
- Document metadata stores original filename, stored path, storage disk, MIME type, file size, uploaded-by user, timestamps, and attachable target.
- Uploaded files use Laravel local Storage disk under the `documents` directory.
- Downloads go through authenticated controller routes instead of exposing raw filesystem paths.
- Delete removes the document metadata and the stored local file.
- Upload validation rejects unsupported file types and files larger than 5 MB.
- Allowed upload extensions:
  - `pdf`
  - `jpg`
  - `jpeg`
  - `png`
  - `webp`
  - `txt`
  - `csv`
  - `doc`
  - `docx`
  - `xls`
  - `xlsx`
- Admin can manage documents.
- Project Manager can manage project, task, and client-related documents.
- Viewer has read-only document access where the parent record is permitted.
- Finance can manage invoice and payment documents, and is blocked from unauthorized project/task document writes.

### v2 Sprint 4

- Expense tracking CRUD with authenticated pages:
  - list expenses;
  - create expenses;
  - view expense details;
  - update expenses;
  - soft delete expenses.
- Expense fields include optional project, required category, required expense date, required amount, required description, and optional vendor/payee.
- Expense records can optionally relate to a project. Project deletion sets the related expense `project_id` to `null`.
- Expense detail pages reuse the existing document attachment flow for expense documents.
- Dashboard finance summary includes total expenses and net profit in addition to existing income metrics.
- Finance report shows filtered income, filtered expense total, and net profit for the selected date range.
- Finance report includes a payment table, expense table, and existing invoice status recap.
- The existing XLSX export remains payment-only and does not include expense rows or net profit rows.
- Admin and Finance can manage expenses.
- Viewer can read expenses and reports where permitted but cannot create, update, delete, upload, or delete expense documents.
- Project Manager is blocked from expense and finance report access/writes under the current permission mapping.

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
  - total income from recorded payments;
  - total expenses;
  - net profit.
- Dashboard overdue invoice list for unpaid and non-cancelled invoices whose due date has passed.
- Dashboard latest payments list showing the five most recent payments.
- Authenticated income report page.
- Income report payment filter by `start_date` and `end_date` based on payment date.
- Filtered income total.
- Invoice status recap grouped by status.
- Excel export for the income report as `income-report.xlsx`.
- The XLSX export remains based on filtered payment rows only.

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

### Expenses

- An expense may optionally belong to an existing project.
- `category` is required and has a maximum length of 120 characters.
- `expense date` is required and must be a valid date.
- `amount` is required, must be numeric, must be at least `0.01`, and cannot exceed `999999999.99`.
- `description` is required and has a maximum length of 2000 characters.
- `vendor/payee` is optional and has a maximum length of 160 characters.
- Deleted expenses are soft-deleted.
- If the related project is deleted, the expense remains and its project relation is cleared.

### Documents

- A document must be attached to one supported parent record.
- Supported implemented parent records are Project, Project Task, Client, Invoice, Payment, and Expense.
- Expense documents use the same upload, download, delete, validation, and local storage behavior as the existing document flow.
- `document` upload is required when creating a document attachment.
- Maximum upload size is 5 MB.
- Allowed upload extensions are `pdf`, `jpg`, `jpeg`, `png`, `webp`, `txt`, `csv`, `doc`, `docx`, `xls`, and `xlsx`.
- Documents are stored on Laravel's `local` disk under the `documents` directory.
- Stored file paths are not exposed as public links.
- Download requires authenticated access and parent-record read permission.
- Delete requires authenticated access and parent-record write permission.
- Deleting a document removes both metadata and the local stored file.

## Dashboard And Report Behavior

- `/dashboard` is available only after login.
- `/dashboard` shows client count, active project count, unpaid invoice total, total income, total expenses, net profit, overdue invoices, and five latest payments.
- `/reports/income` is available only after login.
- The income report filters payments by payment date using optional `start_date` and `end_date` query parameters.
- The income report filters expenses by expense date using the same optional `start_date` and `end_date` query parameters.
- The income total is calculated from payments matching the selected date filter.
- The expense total is calculated from non-deleted expenses matching the selected date filter.
- Net profit is calculated as filtered income minus filtered expenses.
- The invoice status recap lists all supported invoice statuses with count and total invoice amount per status.
- `/reports/income/export` downloads the filtered report as `income-report.xlsx`.
- The Excel file contains payment date, client, project, invoice number, method, reference, amount, and total rows.
- The Excel export remains payment-only; it does not export expenses, expense totals, or net profit.

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

v2 Sprint 3 adds document attachment storage:

- `documents`: stores polymorphic `attachable_type` and `attachable_id`, uploaded-by user, original filename, stored path, disk, MIME type, size, and timestamps.
- `documents.uploaded_by_user_id` references `users` and restricts deleting a user while uploaded documents reference that user.
- The migration rollback and reapply flow was verified by QA.

v2 Sprint 4 adds expense storage:

- `expenses`: stores nullable project relation, category, expense date, amount, description, optional vendor/payee, timestamps, and soft delete state.
- `expenses.project_id` references `projects`, cascades project key updates, and is set to `null` if the related project is deleted.
- `expenses` includes indexes for date/category and vendor lookups.
- The expense migration rollback and reapply flow was verified by QA.

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
- Finance: `dashboard.view`, invoice view/create/update/delete, payment create/update/delete, expense view/create/update/delete, document view/manage, report view/export. Document write access is limited by parent record rules to invoice, payment, and expense documents.
- Project Manager: `dashboard.view`, client view/create/update/delete, project view/create/update/delete, project workflow view/manage, document view/manage. Document write access applies to project, task, and client-related documents.
- Viewer: `dashboard.view`, `clients.view`, `projects.view`, project workflow view, `invoices.view`, `expenses.view`, document view, `reports.view`. Viewer can download documents only where the parent record is readable and cannot upload or delete.
- Project Manager: no expense or report permission in the current mapping, so Project Manager is blocked from expense and finance report access/writes.
- Finance: no project workflow view/manage permission in the current mapping, so Finance is blocked from project/task document writes.

## Test Report

Final verified command results after v2 Sprint 4:

```bash
php artisan migrate:fresh --seed --no-interaction
```

Result: passed.

RBAC migration rollback check: passed.

Project workflow migration rollback check: passed.

Document attachment migration rollback and reapply check: passed.

Expense migration rollback and reapply check: passed.

```bash
php artisan test
```

Result after v2 Sprint 4: passed with 53 tests and 380 assertions.

```powershell
.\vendor\bin\pint --test
```

Result: passed.

```bash
npm run build
```

Result: passed. The build showed an optional `fontaine` notice and still exited successfully.

QA verdict after v2 Sprint 4: PASS.

Defects found: none.

QA note: Expense CRUD, soft delete, validation, optional project relation, polymorphic expense documents, finance report totals, Sprint 4 RBAC permissions, regression tests, Pint, and frontend build were verified.

## Known Limitations

- Excel export now generates a fuller `.xlsx` OpenXML package with workbook metadata, styles, worksheet dimension, XML validation coverage, and worksheet content checks.
- RBAC is implemented for current Blade modules only.
- Project workflow is a baseline CRUD and progress calculation implementation.
- Project activity timeline is lightweight workflow history only, not a full audit log.
- Document attachment is a baseline upload/download/delete implementation only.
- Documents use Laravel local storage only.
- File delete removes metadata and the local stored file without a recovery workflow.
- Payment attachment UI is embedded on the invoice detail page.
- Expense tracking is a baseline CRUD implementation with optional project relation and expense document attachments.
- XLSX export remains payment-only and does not include expense rows, expense totals, or net profit.
- Preview generation, document versioning, OCR, antivirus scanning, public sharing links, drag-and-drop upload, and bulk upload are not included.
- Drag-and-drop kanban is not included.
- Recurring expenses, tax calculation, budgeting, approval workflow, reimbursement workflow, bank import, OCR receipt scanning, payment gateway integration, and external accounting integration are not included.
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
- Use expenses to record general expenses or expenses linked to a project.
- Use expense detail pages to attach expense documents through the existing document panel.
- Use the dashboard to review current metrics, total expenses, net profit, overdue invoices, and latest payments.
- Use the income report page to filter payments and expenses by date range.
- Use the report export when a payment-only `.xlsx` export is needed.
- Keep `.env` local and do not commit secrets or production credentials.
