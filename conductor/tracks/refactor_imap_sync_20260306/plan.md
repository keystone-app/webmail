# Implementation Plan: Refactor IMAP Sync Job

## Phase 1: Foundation & Abstraction [checkpoint: 355fefb]

- [x] Task: Create `ImapConnectionManager` service. [a8a5faf]
    - [x] Write tests for connection management (connect, reconnect, error handling).
    - [x] Implement `ImapConnectionManager` using `webklex/laravel-imap`.
- [x] Task: Create `ImapMessageParser` service. [9009c51]
    - [x] Write tests for parsing various message structures (text, HTML, attachments, inline images).
    - [x] Implement `ImapMessageParser` logic extracted from current `ImapSyncJob`.
- [x] Task: Create `ImapMessageRepository` service. [94b4177]
    - [x] Write tests for storing messages and managing UIDs (mirror sync/deletion).
    - [x] Implement `ImapMessageRepository` to handle database interactions.
- [x] Task: Conductor - User Manual Verification 'Phase 1: Foundation & Abstraction' (Protocol in workflow.md)

## Phase 2: Core Refactoring [checkpoint: cbecddb]

- [x] Task: Integrate new services into `ImapSyncJob`. [1b1e1b3]
    - [x] Update `ImapSyncJobTest` to use mocks or verify integration.
    - [x] Refactor `ImapSyncJob.php` to use the new service abstractions.
- [x] Task: Enhance Error Handling & Retries. [0ac84b1]
    - [x] Write tests for resilient sync (handling `Connection reset by peer`, etc.).
    - [x] Implement retry logic and granular logging in the job.
- [x] Task: Conductor - User Manual Verification 'Phase 2: Core Refactoring' (Protocol in workflow.md)

## Phase 3: Final Verification

- [ ] Task: Verification & Performance check.
    - [ ] Run full automated test suite and ensure coverage > 80%.
    - [ ] Perform manual end-to-end sync verification.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Final Verification' (Protocol in workflow.md)
