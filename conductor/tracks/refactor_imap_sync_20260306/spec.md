# Specification: Refactor IMAP Sync Job

## Overview
The current `ImapSyncJob` implementation has grown complex and handles multiple responsibilities (connecting, fetching, parsing, storing, attachment handling, and cache management). This track aims to refactor the job to improve code quality, performance, reliability, and extensibility by introducing cleaner abstractions and better separation of concerns.

## Functional Requirements
1.  **Modular Logic**: Break down the monolithic `handle` method into smaller, specialized services or helper classes (e.g., an IMAP Connection manager, a Message Parser, and a Message Repository).
2.  **Improved Attachment Handling**: Refactor the attachment processing logic to be more efficient and easier to maintain.
3.  **Robust Error Handling**: Implement more granular error handling and retry logic for network-level failures.
4.  **Extensibility**: Ensure the new structure easily supports future enhancements like multi-folder synchronization or different storage drivers.
5.  **Maintain Existing Functionality**: The core behavior (syncing emails, handling inline attachments, mirror synchronization/deletion) must remain intact.

## Non-Functional Requirements
-   **Readability**: The code should follow PSR-12 standards and use descriptive naming.
-   **Maintainability**: Reduced cyclomatic complexity in the main job class.
-   **Performance**: The refactored job should be at least as fast as the current implementation, with an eye toward reducing memory overhead during large syncs.

## Acceptance Criteria
-   The `ImapSyncJob` is refactored into a cleaner architectural pattern (e.g., using specialized service classes).
-   All existing unit and feature tests pass (`ImapSyncJobTest`).
-   The job correctly handles all edge cases previously supported (inline images, mirror sync).
-   Code coverage for the new abstractions is maintained or improved.

## Out of Scope
-   Changing the underlying IMAP library (`webklex/laravel-imap`).
-   Implementing new user-facing features (this is strictly a refactor).
