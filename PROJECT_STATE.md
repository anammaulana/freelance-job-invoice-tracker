# Project State

Project name: Freelance Job & Invoice Tracker

Project objective: Build a local Laravel web application for freelancers to manage clients, projects, invoices, payments, expenses, dashboard metrics, finance reports, automated tests, and final documentation.

Approved requirement version: v2 enhancement

Current sprint: v2 Sprint 5 - Audit Log Hardening

Current status: QA

Completed sprints:
- Sprint 1: Setup project, authentication, database, client management, and project management.
- Sprint 2: Invoice management, payment tracking, and invoice/payment business rules.
- Sprint 3: Dashboard, Excel reports, automated testing, QA fixes, and final documentation.
- v2 Sprint 1: Architecture baseline and RBAC foundation.
- v2 Sprint 2: Project workflow foundation.
- v2 Sprint 3: Document and attachment foundation.
- v2 Sprint 4: Expense and finance enhancement.

Pending sprints:
- v2 Sprint 5: Audit log hardening implemented by Lincon and moved to QA verification.
- v2 future sprints: documentation hardening.

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
- Income report export must use Excel instead of CSV.
- v2 remains modular monolith on Laravel 13, Blade, Tailwind CSS, and SQLite.
- v2 modules should follow Controller -> Service -> Repository when needed -> Model.
- Complex business logic must not live in controllers.
- One v2 sprint must be deliverable and testable within one day.
- Every v2 sprint requires Owner approval before implementation and QA before result approval.

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
- v2 roles: Admin, Finance, Project Manager, Viewer.
- v2 project workflow uses milestone plus simple task kanban, without complex drag-and-drop in initial v2 scope.
- v2 documents can attach to client, project, invoice, payment, expense, or task.
- v2 important data changes must be audit logged.

Decisions:
- 2026-07-31: Requirement v1 approved by Owner via `APPROVE REQUIREMENT`.
- 2026-07-31: Sprint 1 approved by Owner via `APPROVE SPRINT`.
- 2026-07-31: Owner selected Tailwind CSS for Sprint 1 UI.
- 2026-07-31: Sprint 1 implementation completed by Lincon in commit `884a642 Implement Sprint 1 foundation`.
- 2026-07-31: Sprint 1 moved to QA verification.
- 2026-07-31: Sprint 1 QA returned `FAIL`; correction task assigned for required-field validation defects.
- 2026-07-31: Sprint 1 QA defects fixed by Lincon in commit `2c15230 Fix sprint 1 validation coverage`.
- 2026-07-31: Sprint 1 moved back to QA retest.
- 2026-07-31: Sprint 1 QA retest returned `PASS`.
- 2026-07-31: Sprint 1 moved to documentation.
- 2026-07-31: Sprint 1 documentation completed by Sara in commit `8c9c2d1 docs: document sprint 1 setup and scope`.
- 2026-07-31: Sprint 1 Review prepared; waiting for Owner result approval.
- 2026-07-31: Owner requested Sprint 1 revision via `REVISION RESULT`.
- 2026-07-31: Owner requested public GitHub repository setup with branch flow `development`, `staging`, and production `main`.
- 2026-07-31: Local Git branches `development`, `staging`, and `main` prepared from the Sprint 1 reviewed state.
- 2026-07-31: GitHub remote configured: `https://github.com/anammaulana/freelance-job-invoice-tracker.git`.
- 2026-07-31: Branches `main`, `staging`, and `development` pushed to GitHub.
- 2026-07-31: Sprint 1 result approved by Owner via `APPROVE RESULT` with requirement change: replace CSV export with Excel export.
- 2026-07-31: Sprint 2 plan approved by Owner via `APPROVE SPRINT`.
- 2026-07-31: Owner requested Sprint 2 revision via `REVISION RESULT`; remaining QA note must be resolved before result approval.
- 2026-07-31: Sprint 2 result approved by Owner via `APPROVE RESULT`.
- 2026-07-31: Sprint 3 plan approved by Owner via `APPROVE SPRINT`.
- 2026-07-31: Owner requested Sprint 3 revision via `REVISION RESULT` because Excel export still errored.
- 2026-07-31: Sprint 3 result approved by Owner via `APPROVE RESULT`.
- 2026-07-31: v2 enhancement requirement approved by Owner via `APPROVE REQUIREMENT`.
- 2026-07-31: v2 Sprint 1 plan approved by Owner via `APPROVE SPRINT`.
- 2026-07-31: v2 Sprint 1 feature branch created: `feature/v2-sprint-1-rbac-foundation`.
- 2026-07-31: v2 Sprint 1 implementation completed by Lincon in commit `b46bee5 Implement v2 sprint 1 RBAC foundation`.
- 2026-07-31: v2 Sprint 1 QA completed by Nadella with verdict `PASS WITH NOTES`.
- 2026-07-31: v2 Sprint 1 documentation completed by Sara in commit `e87ab9c docs: document v2 sprint 1 QA handoff`.
- 2026-07-31: v2 Sprint 1 Review prepared; waiting for Owner result approval.
- 2026-07-31: Owner requested v2 Sprint 1 review revision via `REVISION RESULT`; review format must include acceptance criteria status, database impact, breaking changes, performance impact, security review, technical debt, risk update, sprint metrics, next sprint recommendation, and Owner checklist.
- 2026-08-01: v2 Sprint 1 result approved by Owner via `APPROVE RESULT`.
- 2026-08-01: v2 Sprint 2 plan prepared; waiting for Owner sprint approval.
- 2026-08-01: v2 Sprint 2 plan approved by Owner via `APPROVE SPRINT`.
- 2026-08-01: v2 Sprint 2 feature branch created: `feature/v2-sprint-2-project-workflow`.
- 2026-08-01: v2 Sprint 2 implementation completed by Lincon and QA completed by Nadella with verdict `PASS`.
- 2026-08-01: v2 Sprint 2 documentation completed by Sara; Sprint Review still requires Owner result approval.
- 2026-08-01: v2 Sprint 2 Review prepared; waiting for Owner result approval.
- 2026-08-01: v2 Sprint 2 result approved by Owner via `APPROVE RESULT`; Owner requested v2 Sprint 3 planning for Document & Attachment Foundation before implementation.
- 2026-08-01: v2 Sprint 3 plan prepared; waiting for Owner sprint approval.
- 2026-08-01: v2 Sprint 3 plan approved by Owner via `APPROVE SPRINT`.
- 2026-08-01: v2 Sprint 3 feature branch created: `feature/v2-sprint-3-documents-attachments`.
- 2026-08-01: v2 Sprint 3 implementation completed by Lincon in commit `5ba8692 feat: add document attachment foundation`; Sprint 3 moved to QA verification.
- 2026-08-01: v2 Sprint 3 QA completed by Nadella with verdict `PASS WITH NOTES`.
- 2026-08-01: v2 Sprint 3 documentation completed by Sara; Sprint Review is ready for Scofield preparation.
- 2026-08-01: v2 Sprint 3 result approved by Owner via `APPROVE RESULT`.
- 2026-08-01: v2 Sprint 4 plan prepared for Expense & Finance Enhancement; waiting for Owner sprint approval.
- 2026-08-01: v2 Sprint 4 plan approved by Owner via `APPROVE SPRINT`.
- 2026-08-01: v2 Sprint 4 feature branch created: `feature/v2-sprint-4-expense-finance`.
- 2026-08-01: v2 Sprint 4 implementation completed by Lincon in commit `8075175 feat: add v2 sprint 4 expenses and finance`; Sprint 4 moved to QA verification.
- 2026-08-01: v2 Sprint 4 QA completed by Nadella with verdict `PASS`.
- 2026-08-01: v2 Sprint 4 documentation completed; Sprint Review prepared and waiting for Owner result approval.
- 2026-08-01: v2 Sprint 4 result approved by Owner via `APPROVE RESULT`.
- 2026-08-01: v2 Sprint 5 plan prepared for Audit Log Hardening; waiting for Owner sprint approval.
- 2026-08-01: v2 Sprint 5 plan approved by Owner via `APPROVE SPRINT`.
- 2026-08-01: v2 Sprint 5 feature branch created: `feature/v2-sprint-5-audit-log-hardening`.
- 2026-08-01: v2 Sprint 5 implementation completed by Lincon in commit `6cca713 feat: add v2 sprint 5 audit logging`; Sprint 5 moved to QA verification.

Open issues:
- No blocking open issues for v2 Sprint 5 QA handoff.
- QA note carried forward: `npm run build` passes with optional `fontaine` warning.
- Advanced document workflows remain future scope and were not verified in v2 Sprint 3.

Known risks:
- Three one-day sprints are tight for full CRUD, business rules, dashboard, reports, tests, QA, and documentation.
- Invoice/payment rules need careful automated coverage to prevent financial data inconsistencies.
- Sprint 3 contains multiple activities and may need strict scope control.
- v2 permission matrix will become more complex as project workflow, documents, expenses, and audit modules are added.
- v2 Sprint 2 must avoid complex drag-and-drop and focus on testable milestone/task workflow.
- v2 Sprint 3 file upload validation must prevent unsafe MIME types, oversized files, and path handling issues.
- v2 Sprint 3 attachment relations must stay simple to fit one-day scope.
- v2 Sprint 4 expense management must not accidentally change approved invoice/payment business rules.
- v2 Sprint 4 finance reports must clearly separate income, expenses, and net profit calculations.
- v2 Sprint 5 audit logging must stay tamper-resistant without overbuilding a full compliance module.
- v2 Sprint 5 must avoid logging secrets, uploaded file contents, or sensitive full payloads.

Last Owner approval: 2026-08-01 - v2 Sprint 5 plan approved.

Next required Owner action: Wait for v2 Sprint 5 QA, documentation, and Sprint Review.

v2 approved requirement:
- Objective: Enhance the existing application into a fuller freelance management system with project workflow, finance, documents, audit, and access control.
- Architecture baseline: modular monolith; Laravel 13; Blade; Tailwind CSS; SQLite; Controller -> Service -> Repository when needed -> Model; no complex business logic in controllers.
- RBAC scope: Admin, Finance, Project Manager, Viewer with flexible permissions.
- Workflow scope: milestones, weighted progress, tasks with statuses Backlog/To Do/In Progress/Review/Done/Cancelled, assignee, priority, due date, description, progress, attachment, and project activity timeline.
- Finance/document/audit scope: expense management, finance report enhancement, document attachment metadata, and audit log for important changes.
- Git workflow: one sprint uses one feature branch and clear commits using `feat:`, `fix:`, `refactor:`, `docs:`, `test:`, or `chore:`.
- Testing standard: unit tests for important services, feature tests for endpoint/web flows, regression tests for impacted stable features, QA evidence per sprint.
- Documentation scope: README, CHANGELOG, PROJECT_STATE.md, DECISIONS.md, Sprint Report, and simple ERD for important database changes.
- Performance/UI/security baseline: dashboard target under 2 seconds for reasonable local data, pagination/search for large tables, avoid N+1 queries, Tailwind consistency, empty/loading states, endpoint authorization, input validation, upload MIME/size limits, non-editable audit logs.
- Excluded scope: complex drag-and-drop kanban, real-time collaboration, public API, Docker, multi-tenant SaaS, external storage, email/WhatsApp notification, payment gateway, e-signature, production deployment automation, and mobile app.

v2 Sprint 1 plan:
- Sprint title: Architecture Baseline and RBAC Foundation.
- Sprint goal: Establish the v2 technical baseline and flexible role/permission foundation without changing stable feature behavior unnecessarily.
- Included scope: create DECISIONS.md, CHANGELOG.md baseline if missing, document module conventions, add roles/permissions schema, seed Admin/Finance/Project Manager/Viewer roles, assign demo user Admin role, enforce initial authorization on existing modules, add authorization helpers/policies/middleware as appropriate, add regression tests for existing stable flows under Admin, add basic forbidden-access tests for non-authorized roles.
- Excluded scope: milestones, task kanban, documents, expenses, audit log implementation, finance report enhancement, complex UI redesign, drag-and-drop, external services, Docker, API, and production deployment.
- Programmer tasks: S1-V2-PROG-01 implement architecture/documentation baseline files; S1-V2-PROG-02 implement RBAC data model, seeders, authorization checks, and tests; S1-V2-PROG-03 fix Sprint 1 QA defects only within approved scope.
- QA tasks: S1-V2-QA-01 verify migration/seed, role permissions, protected routes, regression flows, tests, Pint, and build.
- Technical Writer tasks: S1-V2-DOC-01 update README/CHANGELOG/DECISIONS after QA PASS or PASS WITH NOTES.
- Dependencies: existing users table, auth middleware, stable client/project/invoice/payment/report modules.
- Risks: RBAC can accidentally block existing stable flows; permission mapping must be small enough for one-day scope; SQLite FK behavior must be verified.
- Acceptance criteria: migrations are reversible; roles and permissions are seeded; demo user has Admin access; Admin can access existing stable modules; Viewer cannot create/update/delete; Finance can access finance-related modules only where implemented; Project Manager can access project/client workflow-adjacent modules only where implemented; unauthorized requests receive expected forbidden/redirect behavior; existing v1 feature tests still pass; new RBAC tests pass; README/CHANGELOG/DECISIONS are updated after QA.

Sprint 3 plan:
- Sprint title: Dashboard, Excel Reports, QA, and Final Documentation.
- Sprint goal: Complete the final demonstrable application scope with dashboard metrics, income reporting with Excel export, broader regression coverage, QA fixes, and final documentation.
- Included scope: dashboard summary cards, overdue invoice list, latest payments list, income report filter by date range, invoice status recap, Excel export for income report, final automated/regression test coverage, QA fixes for Sprint 3 findings, README/test report/known limitations update, and final delivery readiness notes.
- Excluded scope: public API, Docker, multi-role authorization, paid services, external integrations, production deployment automation, and features outside the approved v1 scope.
- Programmer tasks: S3-PROG-01 implement dashboard metrics and report pages; S3-PROG-02 implement Excel export; S3-PROG-03 add/adjust automated tests and fix QA defects only within approved Sprint 3 scope.
- QA tasks: S3-QA-01 verify dashboard/report correctness, Excel export behavior, auth protection, regression flows for clients/projects/invoices/payments, SQLite fresh migration/seed, tests, formatting, and build.
- Technical Writer tasks: S3-DOC-01 update README with final setup, feature list, demo account, test report, known limitations, and handover notes after QA PASS or PASS WITH NOTES.
- Dependencies: Sprint 1 and Sprint 2 completed; existing invoice/payment data model available; Excel export package must be local/free and compatible with Laravel.
- Risks: Excel export may require adding a package; dashboard totals need precise financial query coverage; one-day Sprint 3 is tight because it combines final features, QA, and documentation.
- Acceptance criteria: Dashboard shows client count, active project count, unpaid invoice total, total income, overdue invoices, and five latest payments; income report filters by date range; invoice recap groups by status; Excel export downloads a valid `.xlsx` file; all new pages require authentication; feature tests cover core dashboard/report/export behavior; existing flows do not regress; README is updated after QA.

Sprint 3 implementation handoff:
- Programmer task: S3-PROG-01/S3-PROG-02/S3-PROG-03.
- Commit: `6777416 Implement sprint 3 dashboard and reports`.
- Implemented scope: dashboard metrics, overdue invoice list, latest payments list, income report filter, invoice status recap, local `.xlsx` export, dashboard/report nav links, and Sprint 3 feature tests.
- Dependency changes: none.
- Programmer verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 27 tests; `.\\vendor\\bin\\pint --test` passed; `npm run build` passed.

Sprint 3 QA result:
- QA task: S3-QA-01.
- Verdict: PASS WITH NOTES.
- Commands passed: `php artisan migrate:fresh --seed --no-interaction`, `php artisan test` with 27 tests and 158 assertions, `.\\vendor\\bin\\pint --test`, `npm run build`, and `git status --short`.
- Acceptance criteria result: dashboard metrics, overdue invoices, five latest payments, income report date filter, invoice status recap, `.xlsx` export validity, auth protection, feature tests, and regression coverage all passed.
- Defects found: None.
- Note: Excel export was verified as a valid OpenXML ZIP with worksheet content; no manual Excel desktop opening was performed. Build shows optional `fontaine` notice, but exits successfully.

Sprint 3 documentation:
- Documentation task: S3-DOC-01.
- Commit: `958df77 docs: document sprint 3 handover`.
- File changed: `README.md`.
- Documented: verified behavior Sprint 1-3, dashboard, income report, invoice status recap, Excel export, demo account, business rules, database impact, final test report, QA verdict, known limitations, and local demo handover notes.

Sprint 3 review:
- Sprint goal: Complete dashboard, Excel income reports, QA, and final documentation.
- Result: PASS WITH NOTES.
- Completed tasks: S3-PROG-01, S3-PROG-02, S3-PROG-03, S3-QA-01, S3-DOC-01.
- Deliverables: dashboard metrics, overdue invoice list, five latest payments, income report date filter, invoice status recap, Excel `.xlsx` export, feature tests, final README documentation, and project state update.
- Changed modules: dashboard, reports, Excel exporter service, routes/layout navigation, tests, README, and project state.
- QA result: PASS WITH NOTES.
- Documentation status: Completed.
- Known limitations: Excel export was not manually opened in desktop Microsoft Excel; optional `fontaine` build notice appears but build passes; public API, Docker, production deployment automation, external integrations, and multi-role authorization remain out of scope.
- Owner approval required before marking Sprint 3 complete and preparing final delivery summary.

Sprint 3 revision:
- Revision task: S3-REV-01.
- Owner request: Fix Excel export error before Sprint 3 result approval.
- Scope: Harden local `.xlsx` exporter and add stricter export tests.
- Changed files: `app/Services/SimpleXlsxExporter.php`, `tests/Feature/DashboardReportTest.php`, `README.md`, and `PROJECT_STATE.md`.
- Fix summary: Excel export now includes a fuller OpenXML package with `docProps`, workbook relationships, styles, worksheet dimension, sheet views, page margins, escaped XML content, and validation of required package parts.
- Verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 27 tests and 166 assertions; `.\\vendor\\bin\\pint --test` passed; `npm run build` passed.
- Result: PASS. Excel export revision is ready for Owner review.

Final delivery:
- Result: DONE.
- Approved completed scope: authentication, client management, project management, invoice management, payment tracking, dashboard, income report, Excel export, automated tests, QA verification, and README handover documentation.
- Final development commit: `919b9fa Fix sprint 3 Excel export compatibility`.
- Final verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 27 tests and 166 assertions; `.\\vendor\\bin\\pint --test` passed; `npm run build` passed.
- Branch status: `development` contains the approved final implementation.

Sprint 2 implementation handoff:
- Programmer task: S2-PROG-01
- Commit: `c854942 Implement Sprint 2 invoice and payment tracking`
- Branch: `development`, currently ahead of `origin/development` by 1 commit.
- Implemented scope: Invoice CRUD, Payment Tracking, invoice/payment services, invoice/payment validation, authenticated invoice/payment routes, Blade views, model relationships, factories, migrations, and feature tests.
- Business rules implemented: invoice number auto-generation using `INV-YYYYMM-XXXX`, invoice total cannot exceed project value, multiple payments per invoice, total payments cannot exceed invoice amount, and invoice status recalculation for Partial/Paid/Sent.
- Database impact: added `invoices` and `payments` tables; payments cascade when invoice is deleted.
- Programmer verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 22 tests and 110 assertions; `.\\vendor\\bin\\pint --test` passed; `npm run build` passed; `php artisan route:list --except-vendor` passed.
- Known out-of-scope for Sprint 2: dashboard, Excel export, income report, public API, Docker, and multi-role authorization.

Sprint 2 QA result:
- QA task: S2-QA-01
- Verdict: PASS WITH NOTES
- Commands passed: `php artisan migrate:fresh --seed --no-interaction`, `php artisan test` with 22 tests and 110 assertions, `.\\vendor\\bin\\pint --test`, `npm run build`, and `php artisan route:list --path=invoices -v`.
- Acceptance criteria result: invoice CRUD, invoice number generation, invoice validation, project invoice total limit, multiple payments, payment total limit, Partial/Paid status updates, status recalculation after payment update/delete, auth protection, SQLite migration/seed, automated tests, formatting, and build all passed.
- Defects found: None.
- Note: Explicit automated auth coverage exists for invoice index; nested payment auth protection was verified through route middleware evidence rather than a dedicated unauthenticated payment feature test.

Sprint 2 documentation:
- Documentation task: S2-DOC-01
- Commit: `1bc71e7 docs: document sprint 2 invoice payments`
- File changed: `README.md`
- Documented: verified Sprint 2 invoice CRUD, payment tracking, invoice/payment business rules, database impact, test report, and known limitations.

Sprint 2 review:
- Sprint goal: Implement invoice management, payment tracking, and invoice/payment business rules.
- Result: PASS WITH NOTES
- Completed tasks: S2-PROG-01, S2-QA-01, S2-DOC-01.
- Deliverables: invoice CRUD, payment tracking, invoice/payment services, migrations, factories, validation, Blade views, authenticated routes, feature tests, and README documentation.
- Changed modules: invoices, payments, project relationship, routes/layout navigation, tests, README, and project state.
- QA result: PASS WITH NOTES.
- Documentation status: Completed.
- Known limitations: dashboard metrics, Excel export, income reports, public API, Docker support, multi-role authorization, and production deployment documentation are not completed in Sprint 2.
- Owner approval required before marking Sprint 2 complete and preparing Sprint 3 plan.

Sprint 2 revision:
- Revision task: S2-REV-01
- Owner request: Resolve the part that is not yet aligned with the original plan before Sprint 2 result approval.
- Scope: Add dedicated automated authentication coverage for nested payment routes.
- Changed file: `tests/Feature/AuthenticationTest.php`.
- Verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 23 tests and 120 assertions; `.\\vendor\\bin\\pint --test` passed; `npm run build` passed.
- Result: PASS. Remaining QA note resolved; Sprint 2 returned to result approval.

Sprint 1 implementation handoff:
- Project folder: `C:\Users\anam.maulana\.openclaw\workspace\projects\freelance-job-invoice-tracker`
- Commit: `884a642 Implement Sprint 1 foundation`
- Implemented scope: Laravel 13 setup, Blade + Tailwind CSS layout, SQLite configuration, login/logout, demo user seeder, Client CRUD, Project CRUD, Sprint 1 validation and business rules.
- Verified by Programmer: `composer install --no-interaction --prefer-dist --no-progress --no-scripts`, `php artisan key:generate --force`, `php artisan migrate:fresh --seed --no-interaction`, `npm install --ignore-scripts`, `npm run build`, `./vendor/bin/pint --test`, `php artisan test`.
- Programmer test result: 11 tests, 50 assertions passed.
- Demo login: `demo@example.com` / `password`.
- Known out-of-scope for Sprint 1: invoice, payment, dashboard metrics, reports, Docker, API, and multi-role features.

Sprint 1 QA result:
- QA task: S1-QA-01
- Verdict: FAIL
- Commands passed: `composer install --no-interaction`, `npm install --ignore-scripts`, `php artisan migrate:fresh --seed`, `php artisan test`, `npm run build`, `php artisan route:list --except-vendor`, local smoke via `php artisan serve`.
- Failed acceptance criteria: Client required-field validation and Project `description` required-field validation.
- Required action: Lincon must fix validation rules and add/adjust automated tests before QA retest.

Sprint 1 correction handoff:
- Correction task: S1-PROG-FIX-01
- Commit: `2c15230 Fix sprint 1 validation coverage`
- Fixed files: `StoreClientRequest.php`, `UpdateClientRequest.php`, `StoreProjectRequest.php`, `UpdateProjectRequest.php`, `ClientCrudTest.php`, `ProjectCrudTest.php`.
- Fixed validation: Client create/update now requires `email`, `phone_number`, `company`, and `address`; Project create/update now requires `description`.
- Programmer verification: `php artisan test tests\Feature\ClientCrudTest.php tests\Feature\ProjectCrudTest.php` passed with 9 tests and 47 assertions; `php artisan test` passed with 14 tests and 62 assertions; `./vendor/bin/pint --test` passed.

Sprint 1 QA retest:
- QA task: S1-QA-RETEST-01
- Verdict: PASS
- Retested defects: S1-QA-D01 PASS; S1-QA-D02 PASS.
- Regression checks passed: demo login/logout, protected pages, Client CRUD, Client active-project delete block, Project CRUD, allowed Project statuses, deadline validation, SQLite migration/seed, Tailwind/Vite build.
- Commands passed: `php artisan migrate:fresh --seed`, `npm run build`, `php artisan test` with 14 tests and 62 assertions, `.\\vendor\\bin\\pint --test`.
- Remaining defects: None for Sprint 1.

Sprint 1 documentation:
- Documentation task: S1-DOC-01
- Commit: `8c9c2d1 docs: document sprint 1 setup and scope`
- File changed: `README.md`
- Documented: Laravel + SQLite setup, demo login, verified Sprint 1 features, Sprint 1 business rules, test report, known limitations and out-of-scope features.

Sprint 1 review:
- Sprint goal: Establish runnable Laravel foundation with authentication, Client CRUD, Project CRUD, SQLite database, Tailwind CSS UI, tests, QA verification, and documentation.
- Result: PASS
- Completed tasks: S1-PROG-01, S1-PROG-FIX-01, S1-QA-01, S1-QA-RETEST-01, S1-DOC-01.
- Deliverables: runnable Laravel app, SQLite migrations/seeders, demo login, Client Management, Project Management, automated tests, README documentation.
- Changed modules: authentication, clients, projects, layout/views, routes, tests, README.
- QA result: PASS after correction and retest.
- Documentation status: Completed.
- Known limitations: invoice, payment, dashboard, reports/CSV, Docker, API, and multi-role authorization are not implemented in Sprint 1.
- Owner approval required before marking Sprint 1 complete and preparing Sprint 2 plan.

v2 Sprint 1 implementation handoff:
- Tasks: S1-V2-PROG-01, S1-V2-PROG-02, S1-V2-PROG-03.
- Implemented scope: architecture baseline documents, database-backed roles and permissions, seeded Admin/Finance/Project Manager/Viewer roles, seeded permissions, demo Admin assignment, permission middleware, Gate integration, route-level authorization, permission-aware Blade navigation/actions, and RBAC feature tests.
- RBAC mapping: Admin all current permissions; Finance dashboard/invoices/payments/reports/export; Project Manager dashboard/clients/projects; Viewer dashboard/clients/projects/invoices/reports read-only.
- Verification: `php artisan migrate:fresh --seed --no-interaction` passed; `php artisan test` passed with 33 tests and 225 assertions; `.\\vendor\\bin\\pint --test` passed after formatting; `npm run build` passed with optional `fontaine` notice.
- Known limitations: RBAC management UI, audit log, future v2 project workflow, documents, expenses, public API, Docker, and production deployment are outside v2 Sprint 1.
- QA status: completed by Nadella with verdict `PASS WITH NOTES`.

v2 Sprint 1 QA result:
- QA task: S1-V2-QA-01.
- Verdict: PASS WITH NOTES.
- Commands passed: `git status --short --branch`, `php artisan migrate:fresh --seed --no-interaction`, `php artisan migrate:rollback --step=1 --no-interaction`, `php artisan test` with 33 tests and 225 assertions, `.\\vendor\\bin\\pint --test`, and `npm run build`.
- Acceptance criteria result: migrations reversible, roles/permissions seeded, demo Admin access, Admin stable module access, Viewer read-only restrictions, Finance finance-module access, Project Manager client/project access, unauthorized 403/redirect behavior, v1 regression coverage, RBAC tests, and documentation checks all passed.
- Defects found: None.
- Notes: `npm run build` passes with optional `fontaine` warning; permission mapping should remain a regression focus in future sprints.

v2 Sprint 1 documentation:
- Documentation task: S1-V2-DOC-01.
- Commit: `e87ab9c docs: document v2 sprint 1 QA handoff`.
- Files changed: `README.md`, `CHANGELOG.md`, and `DECISIONS.md`.
- Documented: QA verdict, RBAC role mapping, migration rollback evidence, automated test result, build note, and v2 Sprint 1 limitations.

v2 Sprint 1 review:
- Sprint goal: Establish the v2 technical baseline and flexible role/permission foundation without changing stable feature behavior unnecessarily.
- Result: PASS WITH NOTES.
- Completed tasks: S1-V2-PROG-01, S1-V2-PROG-02, S1-V2-PROG-03, S1-V2-QA-01, and S1-V2-DOC-01.
- Deliverables: architecture baseline docs, RBAC schema/models/seeders, permission middleware, Gate integration, protected routes, permission-aware UI actions/navigation, RBAC tests, regression evidence, and documentation updates.
- Changed modules: RBAC models/migration/seeder/support, User role helpers, app service provider, permission middleware, routes, Blade navigation/actions, tests, README, CHANGELOG, DECISIONS, and project state.
- QA result: PASS WITH NOTES.
- Documentation status: Completed.
- Known limitations: RBAC management UI, audit log, milestone/task workflow, documents, expenses, API, Docker, and deployment remain out of v2 Sprint 1 scope.
- Owner approval required before marking v2 Sprint 1 complete and preparing v2 Sprint 2 plan.

v2 Sprint 1 revised review:
- Revision reason: Owner requested a more complete sprint artifact before result approval.
- Result: PASS WITH NOTES.
- Acceptance criteria status: PASS - migrations are reversible; roles and permissions are seeded; demo user has Admin access; Admin can access existing stable modules; Viewer cannot create/update/delete; Finance can access finance-related modules within implemented scope; Project Manager can access project/client workflow-adjacent modules within implemented scope; unauthorized requests return expected forbidden/redirect behavior; existing v1 feature tests pass; new RBAC tests pass; README, CHANGELOG, and DECISIONS are updated after QA.
- Database impact: added `roles`, `permissions`, `role_user`, and `permission_role`; modified user behavior through role relationship helpers; rollback verification passed with `php artisan migrate:rollback --step=1 --no-interaction`.
- Breaking changes: no user-facing breaking changes found; new modules must attach permission middleware and permission-aware UI checks to stay aligned with the RBAC baseline.
- Performance impact: no significant performance impact found in local QA scope; permission cache optimization is not implemented yet.
- Security review: unauthorized users blocked; permission middleware tested; guest access redirect verified; role-limited access tested for Viewer, Finance, and Project Manager; Admin full-access behavior verified.
- Technical debt: RBAC management UI is not implemented; permission caching is not optimized; permission matrix must be expanded carefully as v2 modules are added.
- Current risks: Low for v2 Sprint 1 result approval; Medium for future sprints because permission mapping complexity will grow with workflow, document, expense, and audit modules.
- Sprint metrics: duration target 1 day; completed task groups 5; bugs found 0; bugs fixed 0; regression PASS; verification coverage 33 tests and 225 assertions; migration rollback PASS; Pint PASS; build PASS with optional `fontaine` notice.
- Recommended next sprint: v2 Sprint 2 - Project Workflow Foundation, covering milestone foundation, task foundation, simple status workflow, project progress calculation baseline, and activity timeline baseline if it fits one-day scope.
- Owner checklist: deliverables match approved Sprint 1 scope; QA returned PASS WITH NOTES; documentation updated; no blocking defects found; ready for Owner result decision.

v2 Sprint 2 plan:
- Sprint title: Project Workflow Foundation.
- Sprint goal: Add a stable milestone and task workflow foundation with simple status tracking, baseline progress calculation, and project activity timeline without complex drag-and-drop.
- Included scope: milestone database/model/CRUD; task database/model/CRUD; task status workflow using Backlog, To Do, In Progress, Review, Done, and Cancelled; task assignee, priority, due date, description, and progress fields; milestone target date and weight fields; project progress calculation baseline from milestones/tasks; lightweight project activity timeline for important workflow changes; route-level and UI authorization using the v2 RBAC baseline; feature tests and regression tests for affected flows; README/CHANGELOG/DECISIONS/PROJECT_STATE updates after QA.
- Excluded scope: drag-and-drop kanban, task attachment upload, document module, expense module, full audit log module, finance report enhancement, public API, Docker, external storage, notification, mobile app, production deployment, and advanced workflow automation.
- Deliverables: reversible migrations for milestones, tasks, and project activity events if needed; models and relationships; controllers/services following the approved architecture; Blade pages/forms for milestone and task management; project detail workflow summary; progress calculation service or model helper; authorization checks; feature/regression tests; documentation updates.
- Programmer tasks: S2-V2-PROG-01 implement milestone foundation; S2-V2-PROG-02 implement task workflow foundation; S2-V2-PROG-03 implement project progress/activity timeline baseline and tests; S2-V2-PROG-04 fix QA defects only within approved Sprint 2 scope.
- QA tasks: S2-V2-QA-01 verify migrations/rollback, milestone CRUD, task CRUD, task status workflow, progress calculation, activity timeline, RBAC behavior, regression flows, tests, Pint, and build.
- Technical Writer tasks: S2-V2-DOC-01 update README/CHANGELOG/DECISIONS after QA PASS or PASS WITH NOTES with database impact, workflow behavior, known limitations, and test evidence.
- Dependencies: completed v2 Sprint 1 RBAC baseline; existing users, clients, projects, auth middleware, and project pages.
- Risks: progress calculation can become ambiguous if milestone weight and task progress rules are not kept simple; RBAC route coverage can regress if new workflow routes are not consistently protected; activity timeline must not be treated as full audit log yet.
- Acceptance criteria: migrations are reversible; project milestones can be created, viewed, updated, and deleted or soft-deleted according to implemented business rules; milestone target date and weight are validated; project tasks can be created, viewed, updated, and deleted or soft-deleted according to implemented business rules; task status is limited to Backlog, To Do, In Progress, Review, Done, and Cancelled; task assignee, priority, due date, description, and progress are validated; project progress updates from milestone/task completion using a documented formula; activity timeline records important milestone/task changes in Sprint 2 scope; Admin can manage workflow data; Project Manager can manage workflow data; Viewer has read-only access; Finance has no unauthorized project workflow write access; existing stable modules still pass regression tests; README/CHANGELOG/DECISIONS are updated after QA.
- Definition of Done: Lincon completes only approved Sprint 2 implementation; automated tests pass or defects are fixed and retested; Nadella returns final QA verdict minimum PASS WITH NOTES; Sara updates documentation after QA; database impact and rollback behavior are recorded; known limitations are listed; Scofield sends Sprint Review for Owner result approval.

v2 Sprint 2 implementation handoff:
- Programmer tasks: S2-V2-PROG-01, S2-V2-PROG-02, S2-V2-PROG-03.
- Implemented scope: milestone CRUD baseline, task CRUD baseline, limited task status workflow, project progress calculation baseline, activity timeline baseline, RBAC protection for workflow routes/actions, and automated tests.
- Database impact: added `projects.progress`, `project_milestones`, `project_tasks`, and `project_activities`.
- Progress formula: weighted milestone task progress when milestones exist; average project task progress when no milestones exist; `0` when no workflow records exist.
- Known out-of-scope: drag-and-drop kanban, task attachments, document module, expense module, full audit log, finance report enhancement, public API, Docker, external storage, notifications, mobile app, production deployment, and advanced workflow automation.

v2 Sprint 2 QA result:
- QA task: S2-V2-QA-01.
- Verdict: PASS.
- Commands passed: `php artisan test` with 40 tests and 272 assertions, `.\\vendor\\bin\\pint --test`, `npm run build`, and rollback verification.
- Notes: `npm run build` passes with optional `fontaine` warning.
- Defects found: None.

v2 Sprint 2 documentation:
- Documentation task: S2-V2-DOC-01.
- Status: Completed.
- Files updated: `README.md`, `CHANGELOG.md`, `DECISIONS.md`, and `PROJECT_STATE.md`.
- Documented: verified workflow behavior, RBAC behavior, database impact, progress formula, activity timeline baseline, QA evidence, and known limitations.
- Owner approval status: Sprint 2 result is not approved yet.

v2 Sprint 2 review:
- Sprint goal: Add a stable milestone and task workflow foundation with simple status tracking, baseline progress calculation, and project activity timeline without complex drag-and-drop.
- Result: PASS.
- Completed tasks: S2-V2-PROG-01, S2-V2-PROG-02, S2-V2-PROG-03, S2-V2-QA-01, and S2-V2-DOC-01.
- Deliverables: milestone CRUD baseline, task CRUD baseline, simple task status workflow, project progress calculation baseline, lightweight activity timeline, RBAC-protected workflow actions, feature/regression tests, and documentation updates.
- Changed modules: project workflow migration, Project/Milestone/Task/Activity models, workflow controllers/requests/service, nested workflow routes, project detail and workflow Blade forms, RBAC permissions, README, CHANGELOG, DECISIONS, tests, and project state.
- QA result: PASS.
- Documentation status: Completed.
- Known limitations: timeline is baseline only and not full audit log; browser-based manual UI testing was not performed; drag-and-drop kanban, attachments, documents, expenses, notifications, public API, Docker, deployment, and advanced workflow automation remain out of scope.
- Owner approved result on 2026-08-01.

v2 Sprint 3 plan:
- Sprint title: Document & Attachment Foundation.
- Sprint goal: Add a secure, testable document metadata and attachment foundation that can attach files to approved business records without building external storage or advanced document workflows.
- Included scope: document metadata model/database; local storage baseline; upload validation for MIME type and file size; attachment relation to project, task, client, invoice, payment, and expense where the related module exists; document listing and download/view baseline; route-level and UI RBAC protection; automated feature/regression tests; README/CHANGELOG/DECISIONS/PROJECT_STATE updates after QA.
- Excluded scope: external/cloud storage, document versioning, e-signature, OCR, preview generation, full-text search, public sharing links, antivirus scanning integration, drag-and-drop upload UI, bulk upload, mobile upload flow, public API, Docker, deployment automation, and advanced audit log hardening.
- Deliverables: reversible migration for documents/attachments; model relationships; upload controller/request/service using local storage; Blade UI entry points where appropriate; permission middleware and permission-aware UI checks; validation for allowed file types and max size; tests for upload, relation, access control, invalid file rejection, and regression; documentation updates after QA.
- Programmer tasks: S3-V2-PROG-01 implement document metadata and attachment schema/relations; S3-V2-PROG-02 implement local upload/download baseline with validation and storage safety; S3-V2-PROG-03 implement RBAC protection, UI entry points, and automated tests; S3-V2-PROG-04 fix QA defects only within approved Sprint 3 scope.
- QA tasks: S3-V2-QA-01 verify migrations/rollback, upload validation, document metadata, attachment relations, storage behavior, RBAC behavior, regression flows, tests, Pint, and build.
- Technical Writer tasks: S3-V2-DOC-01 update README/CHANGELOG/DECISIONS after QA PASS or PASS WITH NOTES with database impact, storage behavior, validation rules, RBAC behavior, known limitations, and test evidence.
- Dependencies: completed v2 Sprint 1 RBAC baseline; completed v2 Sprint 2 project/task workflow; existing client, project, invoice, and payment modules.
- Risks: polymorphic attachment behavior can become too broad if every module gets complex UI; upload security must be conservative; expense attachment relation may need placeholder handling if expense module is not implemented yet.
- Acceptance criteria: migration is reversible; documents store metadata including original filename, stored path, disk, MIME type, size, uploaded-by user, and attachment target; upload validation rejects unsupported MIME types; upload validation rejects files above the approved size limit; uploaded files are stored using Laravel local storage without exposing unsafe paths; documents can attach to project and task records; documents can attach to existing client, invoice, and payment records where implemented UI/routes are included; expense attachment relation is prepared only if it does not require building the expense module; Admin can manage documents; Project Manager can manage project/task-related documents; Viewer has read-only document access where permitted; Finance cannot access unauthorized project/task document writes; unauthorized upload/download requests are blocked; automated tests cover upload, invalid upload, relation, RBAC, and regression behavior; README/CHANGELOG/DECISIONS are updated after QA.
- Definition of Done: Lincon completes only approved Sprint 3 implementation; automated tests pass or defects are fixed and retested; Nadella returns final QA verdict minimum PASS WITH NOTES; Sara updates documentation after QA; database impact, storage behavior, upload validation, and rollback behavior are recorded; known limitations are listed; Scofield sends Sprint Review for Owner result approval.

v2 Sprint 3 implementation handoff:
- Programmer tasks: S3-V2-PROG-01, S3-V2-PROG-02, S3-V2-PROG-03.
- Commit: `5ba8692 feat: add document attachment foundation`.
- Branch: `feature/v2-sprint-3-documents-attachments`.
- Implemented scope: document metadata model, polymorphic attachment target, local storage upload/download/delete baseline, upload validation, Blade document panels, RBAC protection, and automated tests.
- Attachment targets: Project, Project Task, Client, Invoice, and Payment. Expense attachment is prepared through polymorphic design only because the expense module is not implemented yet.
- Database impact: added `documents` table with attachable type/id, uploaded-by user, original filename, stored path, disk, MIME type, size, and timestamps.
- Storage behavior: uploaded files use Laravel local disk under `documents`; downloads go through controller routes instead of exposing raw filesystem paths.
- Validation rules: local disk, `documents` directory, 5 MB max size, allowed extensions `pdf`, `jpg`, `jpeg`, `png`, `webp`, `txt`, `csv`, `doc`, `docx`, `xls`, and `xlsx`.
- RBAC behavior: Admin has full document access; Project Manager manages project/task/client-related documents; Viewer has read-only access where parent record is permitted; Finance manages invoice/payment documents and is blocked from unauthorized project/task document access or writes.
- Programmer verification: `php artisan migrate:fresh --seed --no-interaction` PASS; `php artisan migrate:rollback --step=1 --no-interaction` PASS; `php artisan migrate --no-interaction` PASS; `php artisan test` PASS with 47 tests and 310 assertions; `.\\vendor\\bin\\pint --test` PASS; `npm run build` PASS with existing optional `fontaine` warning.
- Known limitations: no preview generation, public sharing, versioning, OCR, antivirus, bulk upload, or drag-and-drop; payment attachment UI is embedded on invoice detail; file deletion removes local file and metadata without recovery workflow.

v2 Sprint 3 QA result:
- QA task: S3-V2-QA-01.
- Verdict: PASS WITH NOTES.
- Commands passed: `php artisan migrate:fresh --seed --no-interaction`, rollback/reapply verification with `php artisan migrate:rollback --step=1 --no-interaction` and `php artisan migrate --no-interaction`, `php artisan test` with 47 tests and 310 assertions, `.\\vendor\\bin\\pint --test`, and `npm run build`.
- Acceptance criteria result: document metadata, polymorphic attachment relation, project/task/client/invoice/payment attachment targets, local storage behavior, upload/download/delete baseline, unsupported MIME rejection, max-size rejection, RBAC behavior, unauthenticated request blocking, regression tests, migration rollback, Pint, and build all passed.
- Defects found: None.
- Notes: `npm run build` passes with optional `fontaine` warning. Cloud storage, preview generation, versioning, OCR, antivirus scanning, drag-and-drop upload, bulk upload, public API, deployment automation, and expense CRUD were not in scope or verified.

v2 Sprint 3 documentation:
- Documentation task: S3-V2-DOC-01.
- Status: Completed.
- Files updated: `README.md`, `CHANGELOG.md`, `DECISIONS.md`, and `PROJECT_STATE.md`.
- Documented: verified document metadata behavior, polymorphic attachment targets, expense attachment limitation, local Laravel Storage behavior, upload/download/delete baseline, validation rules, RBAC mapping, database impact, rollback/reapply evidence, QA PASS WITH NOTES verdict, test evidence, and known limitations.
- Owner approval status: Sprint 3 result is not approved yet.

v2 Sprint 3 review:
- Sprint goal: Add a secure, testable document metadata and attachment foundation that can attach files to approved business records without building external storage or advanced document workflows.
- Result: PASS WITH NOTES.
- Completed tasks: S3-V2-PROG-01, S3-V2-PROG-02, S3-V2-PROG-03, S3-V2-QA-01, and S3-V2-DOC-01.
- Deliverables: document metadata schema, polymorphic attachment relation, local upload/download/delete baseline, document panels on supported detail pages, RBAC-protected document actions, upload validation, feature/regression tests, and documentation updates.
- Changed modules: document migration/model/service/controller/request, supported parent model relations and UI panels, document routes/RBAC permissions, document feature tests, README, CHANGELOG, DECISIONS, and project state.
- QA result: PASS WITH NOTES.
- Documentation status: Completed in commit `b4c00ae docs: document v2 sprint 3 attachments`.
- Known limitations: document storage is local-only; no preview generation, public sharing, versioning, OCR, antivirus scanning, drag-and-drop upload, bulk upload, public API, deployment automation, expense CRUD, or recovery workflow for deleted files; payment attachment UI is embedded on invoice detail.
- Owner approved result on 2026-08-01.

v2 Sprint 4 plan:
- Sprint title: Expense & Finance Enhancement.
- Sprint goal: Add a controlled expense management foundation and enhance finance reporting so freelancers can track income, expenses, and net profit without changing existing invoice/payment rules.
- Included scope: expense database/model/CRUD; expense category, date, amount, description, optional project link, and optional vendor/payee fields; document attachment support for expense records using the existing Sprint 3 document foundation; finance report enhancement for income, expenses, and net profit by date range; dashboard finance summary extension if it fits the one-day scope; route-level and UI RBAC protection; automated feature/regression tests; README/CHANGELOG/DECISIONS/PROJECT_STATE updates after QA.
- Excluded scope: recurring expenses, tax calculation, budgeting, approval workflow, reimbursement workflow, bank import, OCR receipt scanning, payment gateway integration, external accounting integrations, public API, Docker, deployment automation, and advanced audit log hardening.
- Deliverables: reversible migration for expenses; Expense model and relationships; controller/request/service following the approved architecture; Blade pages/forms for expense list/create/edit/show/delete; document panel integration for expense detail if expense show page exists; finance report calculations for income, expense total, and net profit; permission mapping for Admin and Finance manage access, Viewer read-only where approved, and Project Manager restrictions; feature/regression tests; documentation updates.
- Programmer tasks: S4-V2-PROG-01 implement expense schema, model, CRUD, validation, and relationships; S4-V2-PROG-02 integrate expense documents and RBAC-protected UI/routes; S4-V2-PROG-03 enhance finance report/dashboard calculations and automated tests; S4-V2-PROG-04 fix QA defects only within approved Sprint 4 scope.
- QA tasks: S4-V2-QA-01 verify migrations/rollback, expense CRUD, validation, document attachment to expenses, finance report calculations, RBAC behavior, regression flows, tests, Pint, and build.
- Technical Writer tasks: S4-V2-DOC-01 update README/CHANGELOG/DECISIONS after QA PASS or PASS WITH NOTES with database impact, finance calculation rules, RBAC behavior, known limitations, and test evidence.
- Dependencies: completed v2 Sprint 1 RBAC baseline; completed v2 Sprint 3 document attachment foundation; existing invoice/payment/report modules.
- Risks: finance calculations can become misleading if income and expense date filters are not documented; expense attachment support depends on the Sprint 3 document relation staying stable; RBAC mapping must avoid giving Project Manager finance write access accidentally.
- Acceptance criteria: migration is reversible; expenses can be created, viewed, updated, and deleted or soft-deleted according to implemented business rules; expense amount, date, category, and description are validated; expenses can optionally link to a project; expenses can have document attachments through the existing document foundation; finance report shows income total, expense total, and net profit for the selected date range; existing income report/export behavior does not regress; Admin can manage expenses; Finance can manage expenses and finance reports; Viewer has read-only access where permitted; Project Manager cannot perform unauthorized finance/expense writes; automated tests cover expense CRUD, validation, document relation, finance calculations, RBAC, and regression behavior; README/CHANGELOG/DECISIONS are updated after QA.
- Definition of Done: Lincon completes only approved Sprint 4 implementation; automated tests pass or defects are fixed and retested; Nadella returns final QA verdict minimum PASS WITH NOTES; Sara updates documentation after QA; database impact, finance calculation rules, rollback behavior, and known limitations are recorded; Scofield sends Sprint Review for Owner result approval.

v2 Sprint 4 implementation handoff:
- Programmer tasks: S4-V2-PROG-01, S4-V2-PROG-02, S4-V2-PROG-03.
- Implemented scope: expense CRUD with soft delete, required category/date/amount/description validation, optional project and vendor/payee fields, expense document attachments through the existing polymorphic document foundation, finance report income/expense/net profit totals by date range, dashboard finance summary extension, RBAC-protected routes/UI, and automated feature/regression tests.
- Database impact: added reversible `expenses` table with nullable `project_id`, category, expense date, amount, description, vendor, timestamps, soft deletes, and date/category/vendor indexes.
- Finance calculation rules: income total uses payments filtered by `payment_date`; expense total uses non-deleted expenses filtered by `expense_date`; net profit is income minus expenses for the selected range; existing income XLSX export remains payment-only.
- RBAC behavior: Admin can manage expenses; Finance can manage expenses, expense documents, and reports; Viewer can read expenses/reports where permitted but cannot write; Project Manager is blocked from expense and finance report access/writes.
- Programmer verification: `php artisan migrate:fresh --seed --no-interaction` PASS; `php artisan migrate:rollback --step=1 --no-interaction` PASS for Sprint 4 migration; `php artisan migrate --no-interaction` PASS; `php artisan test` PASS with 53 tests and 380 assertions; `.\\vendor\\bin\\pint --test` PASS; `npm run build` PASS with existing optional `fontaine` warning.
- Known limitations: no recurring expenses, tax calculation, budgeting, approval/reimbursement workflow, bank import, OCR receipt scanning, payment gateway integration, external accounting integration, public API, Docker/deployment automation, or advanced audit log hardening.

v2 Sprint 4 QA and documentation:
- QA verdict: PASS.
- QA evidence: `php artisan migrate:fresh --seed --no-interaction` PASS; expense migration rollback/reapply PASS; `php artisan test` PASS with 53 tests and 380 assertions; `vendor/bin/pint --test` PASS; `npm run build` PASS.
- QA verified: Expense CRUD/soft delete/validation/optional project relation, polymorphic expense documents via `DocumentController`, finance report income/expense/net profit totals, Sprint 4 RBAC permissions, and no unrelated scope.
- Documented files: `README.md`, `CHANGELOG.md`, `DECISIONS.md`, and `PROJECT_STATE.md`.
- Documented: verified Sprint 4 expense behavior, expense document attachment behavior, finance report income/expense/net profit rules, payment-only XLSX export limitation, RBAC mapping, database impact, test evidence, and known limitations.

v2 Sprint 4 review:
- Sprint goal: Add controlled expense management and enhanced finance reporting for income, expenses, and net profit without changing existing invoice/payment rules.
- Result: PASS.
- Completed tasks: S4-V2-PROG-01, S4-V2-PROG-02, S4-V2-PROG-03, S4-V2-QA-01, and S4-V2-DOC-01.
- Deliverables: expense CRUD with soft delete, optional project relation, expense document attachments, finance report income/expense/net profit totals, dashboard finance summary extension, RBAC-protected expense/report access, automated tests, and documentation updates.
- Changed modules: expense migration/model/service/controller/requests/views/factory/tests, document attachment integration for expenses, dashboard/report services and views, RBAC permissions, README, CHANGELOG, DECISIONS, and project state.
- QA result: PASS.
- Documentation status: Completed.
- Known limitations: recurring expenses, tax calculation, budgeting, approval/reimbursement workflow, bank import, OCR receipt scanning, payment gateway integration, external accounting integration, public API, Docker/deployment automation, and advanced audit log hardening remain out of scope.
- Owner approval status: Approved by Owner on 2026-08-01.

v2 Sprint 5 plan:
- Sprint title: Audit Log Hardening.
- Sprint goal: Add a tamper-resistant, useful audit log foundation for important business data changes without introducing a complex compliance system.
- Included scope: audit log database/model/service; automatic audit logging for important create/update/delete/soft-delete actions on clients, projects, invoices, payments, expenses, documents, project milestones, and project tasks; actor, action, target, timestamp, and summarized before/after metadata; read-only audit log list/detail for Admin; filtered audit access for relevant roles where permitted; endpoint authorization; automated feature/regression tests; README/CHANGELOG/DECISIONS/PROJECT_STATE updates after QA.
- Excluded scope: full compliance certification, immutable external ledger, cryptographic signing, SIEM integration, alerting, real-time event streaming, webhook export, public API, log retention automation, user activity analytics, production monitoring, Docker/deployment automation, and logging secrets or uploaded file contents.
- Deliverables: reversible migration for audit logs; AuditLog model and audit service/helper; integration into approved modules; read-only Blade UI for audit review; permission mapping; tests for log creation, read-only behavior, authorization, sensitive data exclusion, and regression; documentation updates after QA.
- Programmer tasks: S5-V2-PROG-01 implement audit log schema, model, and audit service; S5-V2-PROG-02 integrate audit logging into approved business modules; S5-V2-PROG-03 implement read-only audit UI, RBAC, and automated tests; S5-V2-PROG-04 fix QA defects only within approved Sprint 5 scope.
- QA tasks: S5-V2-QA-01 verify migrations/rollback, audit records for key module changes, read-only behavior, sensitive data exclusion, RBAC behavior, regression flows, tests, Pint, and build.
- Technical Writer tasks: S5-V2-DOC-01 update README/CHANGELOG/DECISIONS after QA PASS or PASS WITH NOTES with audit scope, database impact, RBAC behavior, sensitive data policy, known limitations, and test evidence.
- Dependencies: completed v2 Sprint 1 RBAC baseline; completed v2 Sprint 2 workflow foundation; completed v2 Sprint 3 documents foundation; completed v2 Sprint 4 expense and finance foundation.
- Risks: audit logging can become noisy if every field change is logged without summarization; sensitive payloads must be excluded; integration across many modules may exceed a one-day sprint if scope is not kept to important create/update/delete actions.
- Acceptance criteria: migration is reversible; audit logs capture actor, action, target type/id, timestamp, and safe summarized changes; important create/update/delete/soft-delete actions are logged for clients, projects, invoices, payments, expenses, documents, milestones, and tasks; audit records are read-only through the application; Admin can view audit logs; non-authorized roles cannot view audit logs; permitted role access does not expose unauthorized module details; logs do not store passwords, tokens, uploaded file contents, or full sensitive payloads; existing module behavior does not regress; automated tests cover log creation, authorization, read-only behavior, sensitive data exclusion, and regression behavior; README/CHANGELOG/DECISIONS are updated after QA.
- Definition of Done: Lincon completes only approved Sprint 5 implementation; automated tests pass or defects are fixed and retested; Nadella returns final QA verdict minimum PASS WITH NOTES; Sara updates documentation after QA; database impact, audit coverage, sensitive data exclusions, rollback behavior, and known limitations are recorded; Scofield sends Sprint Review for Owner result approval.

v2 Sprint 5 implementation handoff:
- Programmer task: S5-V2-PROG-01, S5-V2-PROG-02, S5-V2-PROG-03.
- Commit: `6cca713 feat: add v2 sprint 5 audit logging`.
- Implemented scope: `audit_logs` table, `AuditLog` model, `AuditLogService`, automatic audit logging for approved business modules, read-only Admin audit log list/detail UI, `audit-logs.view` RBAC permission, and sensitive audit summary filtering.
- Changed modules: audit log migration/model/service/controller/views/tests, app service provider model observers, RBAC permissions, app layout navigation, and web routes.
- Database impact: new reversible `audit_logs` table; rollback/reapply verified by Programmer.
- Programmer verification: `php artisan migrate:fresh --seed --no-interaction` PASS; audit migration rollback/reapply PASS; `php artisan test` PASS with 57 tests and 440 assertions; `vendor/bin/pint --test` PASS; `npm run build` PASS with optional `fontaine` notice only.
- Known limitations: audit log is an application/database audit foundation only; no external immutable ledger, signing, retention automation, alerts, or SIEM integration; unauthenticated system/background changes may record actor as `System`.
- Current QA task: S5-V2-QA-01 verify migrations/rollback, audit records for key module changes, read-only behavior, sensitive data exclusion, RBAC behavior, regression flows, tests, Pint, and build.
