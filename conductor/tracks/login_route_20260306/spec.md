# Specification: Login Route Implementation (DB Auth & Per-User Encryption)

## 1. Overview
Implement a login system that authenticates users against the local database while preparing for secure, decryptable password storage for future IMAP integration. This track introduces per-user encryption keys.

## 2. Functional Requirements
### 2.1 Backend (Laravel)
- **Database Migration:** Add an `encryption_key` column to the `users` table.
- **Route:** Define `GET /login` (to serve the SPA) and `POST /login` (to handle data).
- **Authentication:**
    - Standard Laravel authentication against the `users` table (email/hash check).
- **Encryption Logic:**
    - On successful login, retrieve the user's unique `encryption_key`.
    - Encrypt the raw password provided in the login request using a custom encryption service that utilizes the user's specific `encryption_key`.
- **Session Management:**
    - Store the encrypted password in the session.
- **Error Handling:** Standard validation and authentication failure responses.

### 2.2 Frontend (Svelte 5)
- **Route:** Dedicated `/login` route.
- **UI:** A clean login form (Email, Password) styled with Tailwind CSS 4.
- **Interactions:** Submit form via Fetch/Axios, handle errors, and redirect to `/home` on success.

## 3. Non-Functional Requirements
- **Security:** Passwords must be decryptable for future IMAP tracks but never stored in plain text.
- **Architecture:** Decouple encryption logic into a dedicated service.

## 4. Acceptance Criteria
- [ ] Migration successfully adds `encryption_key` to `users` table.
- [ ] User can log in using database credentials.
- [ ] A unique encryption key is used to secure the password in the session.
- [ ] User is redirected to `/home` upon success.
- [ ] Frontend displays errors for incorrect credentials.

## 5. Out of Scope
- Actual IMAP communication.
- Automated generation of `encryption_key` for existing users (assume manual seeding or future track for registration).
- SMTP settings.
