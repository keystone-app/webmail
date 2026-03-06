# Implementation Plan: Implement Inbox View (Svelte SPA)

## Phase 1: Database and Backend Sync Logic [checkpoint: a57bd90]
- [x] Task: Create Email Model and Migration 8cded50
    - [x] Create `Email` Eloquent model and migration (fields: user_id, imap_uid, folder, subject, from, to, date, body, is_read).
    - [x] Write tests ensuring the `Email` model has the correct relationships (belongs to User).
- [x] Task: Implement IMAP Sync Job 35cc54d
    - [x] Write failing tests for an `ImapSyncJob` that fetches emails and saves them to the DB.
    - [x] Implement `ImapSyncJob` using `webklex/laravel-imap`.
    - [x] Dispatch the job upon successful user login.
- [x] Task: Create API Endpoints 7352a82
    - [x] Write feature tests for `/api/emails` and `/api/emails/{id}` endpoints.
    - [x] Implement `Api\EmailController` to return paginated emails and individual email details for the authenticated user.
- [ ] Task: Conductor - User Manual Verification 'Database and Backend Sync Logic' (Protocol in workflow.md)

## Phase 2: Frontend SPA Infrastructure
- [x] Task: Install Svelte Router 4140236
    - [x] Run `npm install svelte-routing` (or similar appropriate router).
- [x] Task: Setup Main App Layout e180052
    - [x] Create `resources/js/Pages/Mail/App.svelte` as the main layout component.
    - [x] Update `resources/js/app.js` to mount the new SPA layout instead of just Login on the `/home` route.
- [x] Task: Create Basic Components 20d13a4
    - [x] Create placeholder components for `Sidebar.svelte`, `Toolbar.svelte`, `EmailList.svelte`, and `ReadingPane.svelte`.
    - [x] Assemble these components into a responsive layout using Tailwind CSS 4 within `App.svelte`.
- [ ] Task: Conductor - User Manual Verification 'Frontend SPA Infrastructure' (Protocol in workflow.md)

## Phase 3: Frontend Data Integration
- [ ] Task: Fetch and Display Email List
    - [ ] Implement logic in `EmailList.svelte` to fetch data from `/api/emails` on mount.
    - [ ] Display the fetched list with sender, subject, and date.
- [ ] Task: Implement Reading Pane
    - [ ] Setup client-side routing so clicking an email updates the URL and selected state.
    - [ ] Implement logic in `ReadingPane.svelte` to fetch and display the full body of the selected email via `/api/emails/{id}`.
- [ ] Task: Conductor - User Manual Verification 'Frontend Data Integration' (Protocol in workflow.md)
