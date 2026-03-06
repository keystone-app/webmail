# Implementation Plan: Refactor IMAP Sync Job

## Phase 1: Foundation & Abstraction

- [ ] Task: Create `ImapConnectionManager` service.
    - [ ] Write tests for connection management (connect, reconnect, error handling).
    - [ ] Implement `ImapConnectionManager` using `webklex/laravel-imap`.
- [ ] Task: Create `ImapMessageParser` service.
    - [ ] Write tests for parsing various message structures (text, HTML, attachments, inline images).
    - [ ] Implement `ImapMessageParser` logic extracted from current `ImapSyncJob`.
- [ ] Task: Create `ImapMessageRepository` service.
    - [ ] Write tests for storing messages and managing UIDs (mirror sync/deletion).
    - [ ] Implement `ImapMessageRepository` to handle database interactions.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Foundation & Abstraction' (Protocol in workflow.md)

## Phase 2: Core Refactoring

- [ ] Task: Integrate new services into `ImapSyncJob`.
    - [ ] Update `ImapSyncJobTest` to use mocks or verify integration.
    - [ ] Refactor `ImapSyncJob.php` to use the new service abstractions.
- [ ] Task: Enhance Error Handling & Retries.
    - [ ] Write tests for resilient sync (handling `Connection reset by peer`, etc.).
    - [ ] Implement retry logic and granular logging in the job.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Core Refactoring' (Protocol in workflow.md)

## Phase 3: Final Verification

- [ ] Task: Verification & Performance check.
    - [ ] Run full automated test suite and ensure coverage > 80%.
    - [ ] Perform manual end-to-end sync verification.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Final Verification' (Protocol in workflow.md)
