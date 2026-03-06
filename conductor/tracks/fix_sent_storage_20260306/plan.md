# Implementation Plan: Fix Sent Messages Storage

## Phase 1: Core Logic Enhancements

- [x] Task: Refactor `ImapSyncJob` to support custom folders. [1967e85]
    - [ ] Create/Update `ImapSyncJobTest.php` to verify syncing from a custom folder (e.g., 'INBOX.enviadas').
    - [ ] Implement `folder` parameter and logic in `ImapSyncJob.php`.
- [x] Task: Update `MessageController` for improved Sent folder targeting. [d345b57]
    - [ ] Update `MessageSendApiTest.php` to verify prioritizing `INBOX.enviadas`.
    - [ ] Refactor `MessageController@saveToImapSent` to include `INBOX.enviadas` in the search list and prioritize it.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Core Logic Enhancements' (Protocol in workflow.md)

## Phase 2: Integration & Triggering

- [ ] Task: Dispatch sync job after sending.
    - [ ] Update `MessageSendApiTest.php` to assert that `ImapSyncJob` is dispatched for the Sent folder after a successful send.
    - [ ] Implement the `ImapSyncJob::dispatch()` call in `MessageController@send`.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Integration & Triggering' (Protocol in workflow.md)

## Phase 3: Final Verification

- [ ] Task: End-to-end verification.
    - [ ] Perform a manual test of sending an email and verifying its appearance in the database after the background sync.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Final Verification' (Protocol in workflow.md)
