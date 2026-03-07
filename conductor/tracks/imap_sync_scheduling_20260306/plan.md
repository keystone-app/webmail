# Implementation Plan: IMAP Sync Scheduling

## Phase 1: Core Scheduling Logic

- [ ] Task: Create `SyncSchedulerService`.
    - [ ] Write unit tests for basic 5-minute periodic scheduling.
    - [ ] Implement `SyncSchedulerService` with logic for initial, periodic, and immediate syncs.
- [ ] Task: Implement Sync Cancellation and Reset.
    - [ ] Write unit tests for cancelling future syncs and resetting timers.
    - [ ] Update `SyncSchedulerService` to handle `cancelFutureSyncs` and `resetSchedule` methods.
- [ ] Task: Failure & Retry Handling.
    - [ ] Write tests for failure scenarios (network timeout, IMAP server down).
    - [ ] Implement retry logic (30s delay) in `SyncSchedulerService`.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Core Scheduling Logic' (Protocol in workflow.md)

## Phase 2: Integration with User Actions

- [ ] Task: Implement Debounce Mechanism.
    - [ ] Write tests for action-triggered sync with 5s debounce.
    - [ ] Implement debounce logic for user actions (using Redis or database flags).
- [ ] Task: Hook User Actions into Scheduler.
    - [ ] Identify and modify existing controllers/actions to trigger sync.
    - [ ] Write tests to ensure user actions (Move, Delete, Send) trigger targeted syncs correctly.
- [ ] Task: Ensure 30s Window Logic.
    - [ ] Write tests for the "do not cancel if sync is in <30s" rule.
    - [ ] Refine `SyncSchedulerService` to respect this window.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Integration with User Actions' (Protocol in workflow.md)

## Phase 3: Final Verification & Performance

- [ ] Task: End-to-End Sync Testing.
    - [ ] Run automated test suite to verify full scheduling behavior.
    - [ ] Verify that Full Sync and Targeted Sync are triggered as expected.
- [ ] Task: Performance and Resource Audit.
    - [ ] Check queue load and ensure jobs are not piling up unnecessarily.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Final Verification & Performance' (Protocol in workflow.md)
