# Specification: Fix Sent Messages Storage

## Overview
Sent messages are currently not being reliably saved to the IMAP Sent folder (specifically `INBOX.enviadas` on some hosts) and are not appearing in the local database.

## Functional Requirements
1.  **IMAP Sent Folder targeting**: Update the message sending logic to specifically attempt saving to `INBOX.enviadas` as a priority, while maintaining fallbacks for common names like `Sent`, `INBOX.Sent`, etc.
2.  **On-demand Synchronization**: Modify the `ImapSyncJob` to support synchronizing a specific folder.
3.  **Post-send Sync Trigger**: Automatically dispatch an `ImapSyncJob` for the Sent folder immediately after a successful message send and IMAP append.
4.  **Database Storage via Sync**: Sent messages will be stored in the local `emails` table through the standard synchronization process triggered after sending.

## Non-Functional Requirements
-   **Reliability**: Failed IMAP append or sync should be logged but should not prevent the user from receiving a "Success" response if the email was actually sent via SMTP.
-   **Performance**: The synchronization should run in the background (queued) to avoid delaying the API response.

## Acceptance Criteria
-   Sending an email successfully appends it to the `INBOX.enviadas` folder on the IMAP host (if it exists).
-   A background job is dispatched to sync the Sent folder after sending.
-   The sent message appears in the `emails` table after the sync job completes.
-   The `ImapSyncJob` can now handle folders other than `INBOX`.

## Out of Scope
-   Mass synchronization of all historical sent messages.
-   UI changes for the Sent folder.
