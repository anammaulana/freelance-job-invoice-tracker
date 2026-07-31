# Changelog

## v2 Sprint 4 - Expense & Finance Enhancement

- Documented verified expense CRUD behavior: list, create, view, update, and soft delete.
- Documented expense validation for optional project, required category/date/amount/description, and optional vendor/payee.
- Documented expense document attachment through the existing polymorphic document flow.
- Documented enhanced finance reporting for filtered income, filtered expenses, and net profit.
- Clarified that `income-report.xlsx` export remains payment-only and does not include expenses or net profit.
- Documented Sprint 4 RBAC behavior: Admin and Finance manage expenses, Viewer reads only, and Project Manager is blocked from expense/report access.
- Documented database impact for the reversible `expenses` table and verified migration rollback/reapply evidence.
- Documented QA PASS evidence: `php artisan migrate:fresh --seed --no-interaction` passed, expense migration rollback/reapply passed, `php artisan test` passed with 53 tests and 380 assertions, Pint passed, and `npm run build` passed.
- Added known limitations for no recurring expenses, tax calculation, budgeting, approval/reimbursement workflow, bank import, OCR receipt scanning, payment gateway integration, external accounting integration, public API, Docker/deployment automation, or advanced audit log hardening.

## v2 Sprint 3 - Document & Attachment Foundation

- Added verified document metadata documentation for original filename, stored path, disk, MIME type, size, uploaded-by user, timestamps, and polymorphic attachment target.
- Documented supported attachment targets: Project, Project Task, Client, Invoice, and Payment.
- Documented that expense attachment is prepared only through the polymorphic design; no expense module, expense CRUD, or expense document UI is implemented.
- Documented Laravel local Storage disk behavior under the `documents` directory.
- Documented upload, download, and delete baseline behavior, including controller-based downloads and local file deletion.
- Documented upload validation: required file, 5 MB maximum size, and allowed extensions `pdf`, `jpg`, `jpeg`, `png`, `webp`, `txt`, `csv`, `doc`, `docx`, `xls`, and `xlsx`.
- Documented RBAC behavior: Admin full document access, Project Manager project/task/client document management, Viewer read-only parent-permitted access, and Finance invoice/payment document management only.
- Documented database impact for the `documents` table and verified rollback/reapply evidence.
- Documented QA PASS WITH NOTES evidence: `php artisan migrate:fresh --seed --no-interaction` passed, document migration rollback/reapply passed, `php artisan test` passed with 47 tests and 310 assertions, Pint passed, and `npm run build` passed with optional `fontaine` warning.
- Added known limitations for local-only storage, no preview/versioning/OCR/antivirus/public sharing/drag-and-drop/bulk upload, and no expense CRUD.

## v2 Sprint 2 - Project Workflow Foundation

- Added verified milestone CRUD baseline documentation for title, target date, weight, description, progress, and soft delete behavior.
- Added verified task CRUD baseline documentation for optional milestone link, assignee, priority, due date, description, progress, limited status workflow, and soft delete behavior.
- Documented project progress storage in `projects.progress`.
- Documented the `ProjectWorkflowService` progress formula:
  - weighted milestone task progress when milestones exist;
  - average project task progress when no milestones exist;
  - `0` when no workflow records exist.
- Documented lightweight `project_activities` timeline behavior for milestone/task create, update, and delete events.
- Documented RBAC behavior for project workflow: Admin and Project Manager manage workflow, Viewer read-only, Finance blocked from project workflow access/writes under current permissions.
- Documented database impact for `projects.progress`, `project_milestones`, `project_tasks`, and `project_activities`.
- Documented QA PASS evidence: `php artisan test` passed with 40 tests and 272 assertions, Pint passed, `npm run build` passed with optional `fontaine` warning, and rollback verification passed.

## v2 Sprint 1 - RBAC Foundation

- Added architecture baseline decisions in `DECISIONS.md`.
- Added RBAC database tables for roles, permissions, role-user assignments, and role-permission assignments.
- Added seeded roles: Admin, Finance, Project Manager, and Viewer.
- Added extensible seeded permissions for dashboard, clients, projects, invoices, payments, reports, and report export.
- Assigned the demo user `demo@example.com` to Admin during seeding.
- Added permission middleware and Gate integration for existing authenticated modules.
- Added RBAC feature tests for Admin access, Viewer read-only restrictions, Finance access, Project Manager access, and unauthenticated or unauthorized behavior.
- Updated Blade navigation and action buttons to follow the same permissions as backend routes.
- Documented QA result as PASS WITH NOTES with `php artisan test` passing 33 tests and 225 assertions.
- Documented successful RBAC migration rollback verification.
- Noted that `npm run build` may show an optional `fontaine` warning while still passing.
