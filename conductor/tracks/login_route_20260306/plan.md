# Implementation Plan: Login Route (DB Auth & Per-User Encryption)

## Phase 1: Database & Backend Foundation [checkpoint: f7910fa]

- [x] Task: Update `users` table schema. [9b8fa7d]
    - [x] Create a migration to add `encryption_key` column to `users` table.
    - [x] Update `User` model to include `encryption_key` in fillable attributes.
- [x] Task: Implement `EncryptionService`. [2a9cad9]
    - [x] Write unit tests for `EncryptionService` (test encryption/decryption using per-user keys).
    - [x] Create `App\Services\EncryptionService` to handle logic.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Database & Backend Foundation' (Protocol in workflow.md)

## Phase 2: Backend Authentication Logic

- [ ] Task: Implement Login Controller.
    - [ ] Write feature tests for `POST /login` (success with session storage, failure scenarios).
    - [ ] Create `Auth\LoginController` with login logic.
- [ ] Task: Define Routes.
    - [ ] Add `GET /login` and `POST /login` routes to `routes/web.php`.
    - [ ] Add logout route for completeness.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Backend Authentication Logic' (Protocol in workflow.md)

## Phase 3: Frontend Login Interface

- [ ] Task: Create Login Page Component.
    - [ ] Write Vitest tests for `Login.svelte` (form rendering, input handling).
    - [ ] Implement `resources/js/Pages/Auth/Login.svelte` using Svelte 5 and Tailwind CSS 4.
- [ ] Task: Integrate Frontend Routing.
    - [ ] Update `resources/js/app.js` to include the `/login` route using `svelte-routing`.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Frontend Login Interface' (Protocol in workflow.md)

## Phase 4: Integration & UX Polish

- [ ] Task: Implement Auth Middleware/Redirects.
    - [ ] Update middleware to redirect unauthenticated users to `/login`.
    - [ ] Write integration test for "Intended Destination" redirect if applicable.
- [ ] Task: Conductor - User Manual Verification 'Phase 4: Integration & UX Polish' (Protocol in workflow.md)
