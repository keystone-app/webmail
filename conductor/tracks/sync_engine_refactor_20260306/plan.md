# Implementation Plan: Async IMAP Metadata Sync Engine

## Phase 1: Database & Foundation [checkpoint: 30f37be]

- [x] Task: Create `mail_accounts` table and update `emails` schema. [30f37be]
    - [x] Create migration for `mail_accounts` (UUID, user_id, imap_uidvalidity, last_sync_at).
    - [x] Create migration to update `emails` (account_id, message_id, subject, from_email, is_seen, has_attachments, thread_id).
    - [x] Wipe current data: Add a cleanup step to clear the existing `emails` table.
- [x] Task: Create `MailAccount` model and update `Email` model. [30f37be]
    - [x] Define relationships and UUID traits for `MailAccount`.
    - [x] Update `Email` model with new fields and indices.
- [x] Task: Conductor - User Manual Verification 'Phase 1: Database & Foundation' (Protocol in workflow.md) 30f37be

## Phase 2: Core Sync Engine (Laravel)

- [ ] Task: Implement `DeepSyncService`.
    - [ ] Write tests for initial sync (fetch UIDs, batch fetch envelopes, priority folders).
    - [ ] Implement `DeepSyncService` with configurable batching and folder prioritization.
- [ ] Task: Implement `DeltaSyncService`.
    - [ ] Write tests for delta sync (ServerMaxUID vs DBMaxUID).
    - [ ] Implement logic to detect and fetch missing UID ranges.
- [ ] Task: Implement `ImapFlagManager`.
    - [ ] Write tests for flag synchronization (\Seen, \Answered).
    - [ ] Implement atomic DB-first update with background IMAP push.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: Core Sync Engine (Laravel)' (Protocol in workflow.md)

## Phase 3: Go Sidecar & Real-time Integration

- [ ] Task: Develop Go IDLE Sidecar.
    - [ ] Implement IMAP `IDLE` client in Go.
    - [ ] Create Laravel API endpoint for sidecar notifications.
    - [ ] Integrate Go sidecar to call the API hook on `EXISTS`/`FETCH`.
- [ ] Task: Integrate Laravel Reverb for real-time updates.
    - [ ] Configure Reverb and define broadcasting events for new messages and flag updates.
    - [ ] Write integration tests for real-time delivery.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Go Sidecar & Real-time Integration' (Protocol in workflow.md)

## Phase 4: Failure Recovery & Optimization

- [ ] Task: Implement `UIDValidity` Handling.
    - [ ] Write tests for `UIDVALIDITY` change detection and re-sync.
    - [ ] Implement purge-and-sync logic in `SyncWorker`.
- [ ] Task: Redis Caching for Email Bodies.
    - [ ] Implement on-demand body fetching with 24-hour Redis caching.
    - [ ] Ensure `BODY` is never stored in the main DB.
- [ ] Task: Conductor - User Manual Verification 'Phase 4: Failure Recovery & Optimization' (Protocol in workflow.md)

## Phase 5: Final End-to-End Verification

- [ ] Task: Full System Integration Test.
    - [ ] Run automated tests for all phases combined.
    - [ ] Perform manual verification with a real IMAP account.
- [ ] Task: Conductor - User Manual Verification 'Phase 5: Final End-to-End Verification' (Protocol in workflow.md)
