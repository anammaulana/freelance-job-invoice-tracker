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
