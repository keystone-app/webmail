# Specification: IMAP Sync Scheduling

## Overview
Refactor the IMAP synchronization logic to provide a more responsive and efficient user experience. The system should switch from a simple interval-based sync to a more dynamic, user-activity-driven scheduling model.

## Functional Requirements
- **Initial Sync:** Trigger a Full Account Sync immediately upon user login.
- **Periodic Sync:** Schedule a new sync to run every 5 minutes for each active user.
- **User Action Trigger:** Any user action that issues an IMAP command (e.g., message management, compose, folder management) should trigger an immediate targeted sync (syncing only affected folders).
- **Scheduling Logic:**
  - When a user action is detected, cancel any future scheduled syncs for that user.
  - Exception: If a sync is already running or is scheduled to run in the next 30 seconds, do not cancel or restart; let the existing flow continue.
  - Implement a 5-second debounce for user actions to handle bursts of activity.
  - Reset the 5-minute periodic schedule after any successful sync (periodic or action-triggered).
- **Failure Handling:**
  - If a sync fails due to network or server issues, do not reset the 5-minute schedule; instead, retry after 30 seconds or revert to the previous schedule.

## Non-Functional Requirements
- **Efficiency:** Reduce redundant sync operations by cancelling unnecessary scheduled tasks.
- **Responsiveness:** Ensure user changes are reflected in the UI and at the IMAP host with minimal latency.
- **Scalability:** The scheduling system must handle multiple concurrent users without exhausting server resources.

## Acceptance Criteria
- [ ] First sync (Full Account Sync) starts immediately after login.
- [ ] Regular sync occurs exactly 5 minutes after the last successful sync.
- [ ] User actions (move, delete, send, etc.) trigger a targeted sync after a 5-second debounce.
- [ ] Future syncs are correctly cancelled when a user action triggers an immediate sync.
- [ ] Actions occurring within 30 seconds of a scheduled sync do not trigger a new one.
- [ ] Failed syncs do not reset the 5-minute timer but trigger a retry after 30 seconds.

## Out of Scope
- Implementing the actual IMAP sync logic (this track refactors the *scheduling* only).
- Frontend UI updates (except where necessary to communicate sync status).
