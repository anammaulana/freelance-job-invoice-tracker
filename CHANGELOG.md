# Changelog

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
