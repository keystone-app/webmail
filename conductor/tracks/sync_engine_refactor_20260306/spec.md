# Specification: Async IMAP Metadata Sync Engine

## 1. Overview
Replace the current synchronous IMAP synchronization model with a high-performance background synchronization layer. This refactor aims to achieve sub-100ms UI responsiveness while managing high scale (50TB legacy storage, 30k concurrent users) by decoupling the IMAP protocol from the user request lifecycle.

## 2. System Architecture
- **The Metadata Store (Laravel + MySQL):** Stores email headers, flags, and folder structures.
- **The Sync Worker (Laravel Queue):** Executes core IMAP synchronization logic (fetch envelopes, manage UIDs) asynchronously using PHP.
- **The IDLE Sidecar (Go):** A dedicated Go binary to maintain 30k concurrent IMAP `IDLE` connections, triggering "Micro-Syncs" via a Laravel API hook.
- **The Real-time Notifier (Laravel Reverb):** Pushes updates from the database to the Svelte 5 frontend using WebSockets.

## 3. Database Schema Updates
- **New Table: `mail_accounts`**
  - Columns: `id` (UUID, PK), `user_id` (INT, FK to Users), `imap_uidvalidity` (BIGINT), `last_sync_at` (TIMESTAMP).
- **Modified Table: `emails` (Fresh Sync)**
  - Columns: `id` (BIGINT, PK), `uid` (INT, INDEX), `account_id` (UUID, FK), `message_id` (STRING, INDEX), `subject` (TEXT), `from_email` (STRING, INDEX), `is_seen` (BOOLEAN), `has_attachments` (BOOLEAN), `thread_id` (STRING, INDEX).

## 4. Sync Lifecycle
### Phase A: Initial "Deep Sync"
- Triggered upon first login.
- Prioritizes `INBOX`, followed by `SENT`, then other folders.
- Uses configurable batch sizes (default: 500) for `UID FETCH`.
- Frontend displays the most recent 50 emails optimistically.

### Phase B: Delta Sync (The "Thundering Herd" Solution)
- Triggered during peak hours or periodic intervals.
- Compares `ServerMaxUID` with `DBMaxUID` to fetch missing ranges.
- Periodically checks for flag changes (`\Seen`, `\Answered`).

### Phase C: IDLE Persistence
- Go Sidecar maintains persistent `IDLE` connections.
- On `EXISTS` or `FETCH` notifications, Go calls a Laravel API route to trigger a targeted Micro-Sync.

## 5. Performance & Constraints
- **Atomic Transactions:** Update local database first, then dispatch background jobs to update IMAP host.
- **Memory Management:** Never store email `BODY` in MySQL. Fetch on demand and cache in Redis for 24 hours.
- **Encoding:** Rigorous use of `UTF-8` for all IMAP commands and parsing.
- **Failure Recovery:** Purge and re-sync on `UIDVALIDITY` change. Exponential backoff for worker retries.

## 6. Acceptance Criteria
- [ ] Sub-100ms UI responsiveness for message list rendering.
- [ ] Successful migration (wipe and re-sync) to the new schema.
- [ ] Go sidecar correctly maintains 100+ concurrent connections in dev (scaling to 30k in production).
- [ ] Real-time updates delivered via Reverb when new mail arrives.
- [ ] Robust handling of `UIDVALIDITY` changes and network timeouts.

## 7. Out of Scope
- Implementing the Svelte 5 UI components (this track covers the backend engine only).
- Global search indexing (will be handled in a separate track).
- Multi-factor authentication for IMAP.
