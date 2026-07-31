# Decisions

## Architecture Baseline

- The application remains a modular monolith.
- The verified stack is Laravel 13, Blade, Tailwind CSS, and SQLite for local development.
- Module flow should follow `Controller -> Service -> Repository when needed -> Model`.
- Controllers should stay thin and must not contain complex business logic.
- Services hold business workflows such as invoice number generation, payment recalculation, dashboard summaries, and report calculations.
- Repositories are optional and should be added only when query or persistence logic becomes complex enough to justify the extra layer.

## RBAC Foundation

- RBAC uses first-party Laravel patterns with Eloquent models, migrations, seeders, Gate checks, and route middleware.
- Roles and permissions are stored in database tables so the permission model can be extended in future sprints.
- The Admin role is treated as a full-access role through `User::hasPermission()`.
- Non-admin roles receive explicit permissions through seeded role-permission mappings.
- Route middleware is the source of backend authorization for the current Blade modules.
- Blade navigation and action buttons use permission checks so visible actions match backend authorization.
- Finance is limited to dashboard, invoice, payment, income report, and report export permissions.
- Project Manager is limited to dashboard, client, and project permissions.
- Viewer is read-only for dashboard, clients, projects, invoices, and reports.
- RBAC management UI, audit logging, project workflow, documents, expenses, API, Docker, and production deployment are outside v2 Sprint 1.

## v2 Sprint 2 Project Workflow Baseline

- Project workflow remains part of the modular monolith and uses the existing Controller -> Service -> Model pattern.
- `ProjectWorkflowService` owns the project progress calculation so controllers do not hold workflow business logic.
- Project progress is persisted in `projects.progress` for simple display and reporting use in later sprints.
- When active milestones exist, project progress is calculated from weighted milestone progress.
- Milestone progress is calculated from the average progress of active tasks linked to that milestone.
- A milestone with no active tasks contributes `0%` to the weighted calculation.
- When no active milestones exist, project progress is calculated from the average progress of active project tasks.
- When no active milestones or active tasks exist, project progress is `0%`.
- `project_activities` is a lightweight workflow timeline for milestone/task create, update, and delete events.
- The activity timeline is not a full audit log and must not be treated as the future audit module.
- Task workflow is intentionally limited to `Backlog`, `To Do`, `In Progress`, `Review`, `Done`, and `Cancelled`.
- v2 Sprint 2 excludes drag-and-drop kanban, task attachments, document management, expense management, finance report enhancement, notifications, external storage, public API, mobile app, production deployment, and advanced workflow automation.

## v2 Sprint 3 Document Attachment Baseline

- Document management remains part of the modular monolith and uses first-party Laravel upload, validation, Storage, authorization, and Eloquent relationship patterns.
- Document metadata is stored in a single `documents` table with a polymorphic `attachable` relation.
- Verified attachment targets are Project, Project Task, Client, Invoice, and Payment.
- Expense attachment is prepared only by the polymorphic relation design because the expense module is not implemented yet.
- Uploaded files use Laravel's `local` disk and the `documents` directory.
- Raw stored paths must not be exposed as public links; downloads go through authenticated controller routes.
- Delete behavior removes both the document metadata and the local stored file.
- Upload validation stays conservative: one required file, 5 MB maximum size, and allowed extensions `pdf`, `jpg`, `jpeg`, `png`, `webp`, `txt`, `csv`, `doc`, `docx`, `xls`, and `xlsx`.
- RBAC combines general `documents.view`/`documents.manage` permissions with parent-record permission checks.
- Admin can manage all documents through the current permission model.
- Project Manager can manage documents attached to project, task, and client records.
- Finance can manage invoice and payment documents only; project/task document writes remain blocked because Finance has no project workflow manage permission.
- Viewer can read documents only where the parent record is readable and cannot upload or delete.
- v2 Sprint 3 excludes cloud storage, preview generation, document versioning, OCR, antivirus scanning, public sharing, drag-and-drop upload, bulk upload, public API, Docker, deployment automation, and expense CRUD.
