# Implementation Plan: Project Fresh Start

## Phase 1: Backend Cleanup [checkpoint: df9d70a]

- [x] Task: Remove custom models and logic. [91c1e72]
    - [x] Delete files in `app/Models/` (except `User.php`).
    - [x] Delete files in `app/Http/Controllers/Api/`.
    - [x] Delete files in `app/Jobs/`.
    - [x] Delete files in `app/Services/`.
- [x] Task: Revert routes and providers. [6a4b9d7]
    - [x] Clean up `routes/web.php` and `routes/api.php` to remove custom endpoints.
    - [x] Remove custom providers from `app/Providers/` if any.
- [x] Task: Conductor - User Manual Verification 'Phase 1: Backend Cleanup' (Protocol in workflow.md) df9d70a

## Phase 2: Database & Test Cleanup

- [x] Task: Remove custom migrations. [8ff1c20]
    - [x] Delete custom migration files in `database/migrations/`.
    - [x] Keep only the core Laravel migrations.
- [x] Task: Reset Database. [8ff1c20]
    - [x] Run `./vendor/bin/sail artisan migrate:fresh` to reset the schema.
- [x] Task: Remove custom tests. [f155354]
    - [x] Delete files in `tests/Unit/` and `tests/Feature/` related to removed logic.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Database & Test Cleanup' (Protocol in workflow.md)

## Phase 3: Verification & Integrity

- [ ] Task: Sanity Check.
    - [ ] Ensure the application still boots.
    - [ ] Run `npm run build` to verify frontend assets are unaffected.
    - [ ] Run remaining tests (if any) to ensure baseline stability.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Verification & Integrity' (Protocol in workflow.md)
