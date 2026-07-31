# Project State

Project name: Freelance Job & Invoice Tracker

Project objective: Build a local Laravel web application for freelancers to manage clients, projects, invoices, payments, dashboard metrics, income reports, automated tests, and final documentation.

Approved requirement version: v1

Current sprint: Sprint 1 - Setup, Authentication, Client Management, and Project Management

Current status: IN PROGRESS

Completed sprints: None

Pending sprints:
- Sprint 1: Setup project, authentication, database, client management, and project management.
- Sprint 2: Invoice management, payment tracking, and invoice/payment business rules.
- Sprint 3: Dashboard, CSV reports, automated testing, QA fixes, and final documentation.

Approved constraints:
- Laravel with Blade.
- SQLite database.
- Tailwind CSS.
- Use Laravel migration, seeder, factory, validation, and Eloquent ORM.
- Use Controller-Service structure for invoice and payment business logic.
- No paid API or paid service.
- No Docker for the first version.
- Application must run with `php artisan serve`.
- Automated tests must use PHPUnit or Pest.
- Source code must be managed with Git.
- Each sprint must have a clear commit.
- No features outside approved scope without Owner approval.

Approved business rules:
- One user role only: Admin/Freelancer.
- All application pages require authentication.
- Clients cannot be deleted while they still have active projects.
- Project deadline cannot be earlier than start date.
- Invoices are created based on projects.
- Invoice numbers are generated automatically using `INV-YYYYMM-XXXX`.
- One project can have multiple invoices.
- Total invoice amount for a project cannot exceed the project value.
- An invoice can have multiple payments.
- Total payment amount cannot exceed invoice amount.
- Invoice status becomes `Partial` when partially paid.
- Invoice status becomes `Paid` when total payments reach invoice amount.

Decisions:
- 2026-07-31: Requirement v1 approved by Owner via `APPROVE REQUIREMENT`.
- 2026-07-31: Sprint 1 approved by Owner via `APPROVE SPRINT`.
- 2026-07-31: Owner selected Tailwind CSS for Sprint 1 UI.

Open issues:
- Project repository/path for implementation is not yet confirmed.
- Test framework choice is not finalized between PHPUnit and Pest.

Known risks:
- Three one-day sprints are tight for full CRUD, business rules, dashboard, reports, tests, QA, and documentation.
- Invoice/payment rules need careful automated coverage to prevent financial data inconsistencies.
- Sprint 3 contains multiple activities and may need strict scope control.

Last Owner approval: 2026-07-31 - Sprint 1 plan approved.

Next required Owner action: Wait for Sprint 1 implementation, QA, documentation, and Sprint Review.
