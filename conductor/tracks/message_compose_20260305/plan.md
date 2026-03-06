# Implementation Plan: Implement Message Compose

## Phase 1: Backend Infrastructure (SMTP & Models)
- [x] Task: SMTP Configuration da7c5b4
    - [x] Update `.env.example` and `.env` with SMTP variables.
    - [x] Verify `config/mail.php` is correctly set up for multiple drivers if needed.
- [x] Task: Drafts Model and Migration bf0110e
    - [x] Create `Draft` model and migration (fields: user_id, to, cc, bcc, subject, body, attachments_metadata).
    - [x] Write unit tests for the `Draft` model.
- [x] Task: Drafts API Endpoints 1ef84d6
    - [x] Write failing feature tests for saving and updating drafts.
    - [x] Implement `Api\DraftController` with `store` and `update` methods.
- [ ] Task: Conductor - User Manual Verification 'Backend Infrastructure' (Protocol in workflow.md)

## Phase 2: Frontend Composer UI
- [ ] Task: Install Editor Dependencies
    - [ ] Run `npm install @tiptap/core @tiptap/starter-kit @tiptap/pm svelte-tiptap`.
- [ ] Task: Create Composer Component
    - [ ] Create `resources/js/Components/Mail/Composer.svelte` modal component.
    - [ ] Implement fields for To, CC (toggle), BCC (toggle), and Subject.
- [ ] Task: Integrate Tiptap Editor
    - [ ] Embed the rich text editor into the `Composer.svelte` component.
    - [ ] Ensure formatting toolbar is functional.
- [ ] Task: Auto-save Draft Logic
    - [ ] Implement debounced auto-save logic to the `Api\DraftController`.
- [ ] Task: Conductor - User Manual Verification 'Frontend Composer UI' (Protocol in workflow.md)

## Phase 3: Sending Logic & Integration
- [ ] Task: Mailer Service and API
    - [ ] Write failing feature tests for the sending endpoint.
    - [ ] Implement `Api\MessageController@send` to handle final submission.
- [ ] Task: Attachment Handling
    - [ ] Implement backend logic to attach uploaded files to the outgoing mail.
    - [ ] Implement frontend progress bar for uploads.
- [ ] Task: IMAP "Save to Sent" Integration
    - [ ] Implement logic to append the sent message to the user's IMAP "Sent" folder.
- [ ] Task: End-to-End Polish
    - [ ] Add success/error notifications for sending and saving.
    - [ ] Verify keyboard shortcuts (e.g., Ctrl+Enter to send).
- [ ] Task: Conductor - User Manual Verification 'Sending Logic & Integration' (Protocol in workflow.md)
