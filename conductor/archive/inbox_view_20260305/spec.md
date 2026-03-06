# Track: Implement Inbox View (Svelte SPA)

## Overview
Implement the primary Inbox view as a Single Page Application (SPA) using Svelte 5 and Tailwind CSS 4 within the authenticated `/home` route. The system will use an asynchronous queue to sync emails from the IMAP server to the local database, allowing the frontend to quickly retrieve and display emails from the local database.

## Functional Requirements
-   **Frontend Architecture:** Establish a Svelte client-side router within the main application view to handle navigation (e.g., viewing different folders or specific emails) without full page reloads.
-   **UI Components:**
    -   **Sidebar Navigation:** A sidebar listing standard folders (Inbox, Sent, Trash, etc.).
    -   **Top Toolbar:** A header area for global actions (e.g., Refresh, Search).
    -   **Email List:** A responsive list displaying email summaries (sender, subject, date, unread status).
    -   **Reading Pane:** A dedicated area to view the full content of an email selected from the list.
-   **Backend Sync (Async):** Implement a queued Laravel Job that connects to the IMAP server via `webklex/laravel-imap`, fetches new messages, and stores them in a local `emails` database table.
-   **API Endpoints:** Create Laravel API routes (e.g., `/api/emails`) for the Svelte frontend to fetch the synced emails from the local database.

## Non-Functional Requirements
-   **Performance:** The UI must be highly responsive; fetching data from the local database ensures fast load times compared to synchronous IMAP calls.
-   **Styling:** Adhere to the minimalist, mobile-first design goals using Tailwind CSS 4.
-   **State Management:** Utilize Svelte 5 runes (`$state`, `$derived`, etc.) for managing UI state (e.g., selected email, current folder).

## Acceptance Criteria
-   [ ] Svelte SPA routing is configured and functional within the authenticated area.
-   [ ] The main layout includes the Sidebar, Toolbar, Email List, and Reading Pane.
-   [ ] A Laravel Job successfully syncs emails from an IMAP server to a local database table.
-   [ ] The Svelte frontend successfully fetches and displays emails from the local API.
-   [ ] Clicking an email in the list displays its full content in the Reading Pane.

## Out of Scope
-   Sending new emails (Composer).
-   Advanced email search (basic local filtering is acceptable).
-   Complex folder management (creating/deleting folders on the IMAP server).
-   Real-time WebSockets/SSE updates (a manual "refresh" button or polling is sufficient for this track).
