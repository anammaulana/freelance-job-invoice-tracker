# Changelog

## v2 Sprint 1 - RBAC Foundation

- Added architecture baseline decisions in `DECISIONS.md`.
- Added RBAC database tables for roles, permissions, role-user assignments, and role-permission assignments.
- Added seeded roles: Admin, Finance, Project Manager, and Viewer.
- Added extensible seeded permissions for dashboard, clients, projects, invoices, payments, reports, and report export.
- Assigned the demo user `demo@example.com` to Admin during seeding.
- Added permission middleware and Gate integration for existing authenticated modules.
- Added RBAC feature tests for Admin access, Viewer read-only restrictions, Finance access, Project Manager access, and unauthenticated or unauthorized behavior.
- Updated Blade navigation and action buttons to follow the same permissions as backend routes.
