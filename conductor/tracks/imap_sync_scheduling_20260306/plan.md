# Implementation Plan: IMAP Sync Scheduling

## Phase 1: Core Scheduling Logic [checkpoint: f8fd646]

- [x] Task: Create `SyncSchedulerService`. [a2a4384]
    - [x] Write unit tests for basic 5-minute periodic scheduling.
    - [x] Implement `SyncSchedulerService` with logic for initial, periodic, and immediate syncs.
- [x] Task: Implement Sync Cancellation and Reset. [a2a4384]
    - [x] Write unit tests for cancelling future syncs and resetting timers.
    - [x] Update `SyncSchedulerService` to handle `cancelFutureSyncs` and `resetSchedule` methods.
- [x] Task: Failure & Retry Handling. [a2a4384]
    - [x] Write tests for failure scenarios (network timeout, IMAP server down).
    - [x] Implement retry logic (30s delay) in `SyncSchedulerService`.
- [x] Task: Conductor - User Manual Verification 'Phase 1: Core Scheduling Logic' (Protocol in workflow.md) f8fd646

## Phase 2: Integration with User Actions [checkpoint: 973c05f]

- [x] Task: Implement Debounce Mechanism. [973c05f]
    - [x] Write tests for action-triggered sync with 5s debounce.
    - [x] Implement debounce logic for user actions (using Redis or database flags).
- [x] Task: Hook User Actions into Scheduler. [973c05f]
    - [x] Identify and modify existing controllers/actions to trigger sync.
    - [x] Write tests to ensure user actions (Move, Delete, Send) trigger targeted syncs correctly.
- [x] Task: Ensure 30s Window Logic. [973c05f]
    - [x] Write tests for the "do not cancel if sync is in <30s" rule.
    - [x] Refine `SyncSchedulerService` to respect this window.
- [x] Task: Conductor - User Manual Verification 'Phase 2: Integration with User Actions' (Protocol in workflow.md) 973c05f

## Phase 3: Final Verification & Performance

- [ ] Task: End-to-End Sync Testing.
    - [ ] Run automated test suite to verify full scheduling behavior.
    - [ ] Verify that Full Sync and Targeted Sync are triggered as expected.
- [ ] Task: Performance and Resource Audit.
    - [ ] Check queue load and ensure jobs are not piling up unnecessarily.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Final Verification & Performance' (Protocol in workflow.md)
