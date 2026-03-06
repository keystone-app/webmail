# Implementation Plan: Implement Message Compose

## Phase 1: Backend Infrastructure (SMTP & Models) [checkpoint: 8ea8840]
- [x] Task: SMTP Configuration da7c5b4
    - [x] Update `.env.example` and `.env` with SMTP variables.
    - [x] Verify `config/mail.php` is correctly set up for multiple drivers if needed.
- [x] Task: Drafts Model and Migration bf0110e
    - [x] Create `Draft` model and migration (fields: user_id, to, cc, bcc, subject, body, attachments_metadata).
    - [x] Write unit tests for the `Draft` model.
- [x] Task: Drafts API Endpoints 1ef84d6
    - [x] Write failing feature tests for saving and updating drafts.
    - [x] Implement `Api\DraftController` with `store` and `update` methods.
- [x] Task: Conductor - User Manual Verification 'Backend Infrastructure' (Protocol in workflow.md)

## Phase 2: Frontend Composer UI [checkpoint: 2cb2848]
- [x] Task: Install Editor Dependencies da4c331
    - [x] Run `npm install @tiptap/core @tiptap/starter-kit @tiptap/pm svelte-tiptap`.
- [x] Task: Create Composer Component ce9445f
    - [x] Create `resources/js/Components/Mail/Composer.svelte` modal component.
    - [x] Implement fields for To, CC (toggle), BCC (toggle), and Subject.
- [x] Task: Integrate Tiptap Editor 75aba14
    - [x] Embed the rich text editor into the `Composer.svelte` component.
    - [x] Ensure formatting toolbar is functional.
- [x] Task: Auto-save Draft Logic fc87d55
    - [x] Implement debounced auto-save logic to the `Api\DraftController`.
- [x] Task: Conductor - User Manual Verification 'Frontend Composer UI' (Protocol in workflow.md)

## Phase 3: Sending Logic & Integration
- [x] Task: Mailer Service and API b485c4e
    - [x] Write failing feature tests for the sending endpoint.
    - [x] Implement `Api\MessageController@send` to handle final submission.
- [x] Task: Attachment Handling 52d7522
    - [x] Implement backend logic to attach uploaded files to the outgoing mail.
    - [ ] Implement frontend progress bar for uploads.
- [x] Task: IMAP "Save to Sent" Integration e222eac
    - [x] Implement logic to append the sent message to the user's IMAP "Sent" folder.
- [x] Task: End-to-End Polish 6ecb7dc
    - [x] Add success/error notifications for sending and saving.
    - [x] Verify keyboard shortcuts (e.g., Ctrl+Enter to send).
- [x] Task: Conductor - User Manual Verification 'Sending Logic & Integration' (Protocol in workflow.md)
