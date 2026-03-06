# Track: Implement IMAP Authentication

## Overview
Implement user authentication using `webklex/laravel-imap` against a configurable remote IMAP host. This feature will allow users to log in using their email credentials, which will be verified by a remote IMAP server, and then persisted to the local database.

## Functional Requirements
-   **Login Page:** A basic login form (using Svelte 5 and Tailwind CSS 4) for users to enter their email and password.
-   **IMAP Authentication:** Authenticate user credentials against the configured IMAP host using the `webklex/laravel-imap` library.
-   **User Persistence:** Upon successful IMAP authentication, create or update a local `User` record in the database.
-   **Configuration:** The IMAP host, port, and encryption must be configurable via `.env` and `config/imap.php`.
-   **Session Management:** Maintain a secure user session upon successful login.
-   **Logout:** Provide functionality for users to securely log out.

## Non-Functional Requirements
-   **Security:** Do not store the user's IMAP password in the local database.
-   **Error Handling:** Provide clear feedback for connection failures or invalid credentials.
-   **Responsiveness:** The login UI must be responsive and follow the project's design goals.

## Acceptance Criteria
-   [ ] Users can log in with valid IMAP credentials.
-   [ ] Local user record is created or updated on successful IMAP login.
-   [ ] Appropriate error messages are displayed for failed login attempts.
-   [ ] Logged-in users can access protected routes.
-   [ ] Users can log out and their session is terminated.
-   [ ] IMAP configuration is correctly loaded from `.env`.

## Out of Scope
-   IMAP folder management.
-   Email fetching and viewing.
-   IMAP password reset (managed by the remote IMAP provider).
