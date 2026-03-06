# Track: Implement Message Compose

## Overview
Implement a comprehensive message compose feature allowing users to write and send emails. This includes a rich text editor, support for CC/BCC, attachments, and automatic draft saving. The system will use a separate SMTP configuration for sending emails.

## Functional Requirements
- **Compose Interface:** A centered modal overlay that can be triggered from the main application toolbar or sidebar.
- **Rich Text Editor:** Integrate a modern rich text editor (e.g., Tiptap) for the message body, supporting basic formatting (bold, italic, lists, links).
- **Recipient Management:** Fields for "To", "CC", and "BCC" with basic email validation.
- **Attachment Support:** Ability to upload files from the local machine and include them in the sent email.
- **Drafts Support:** 
    - Automatically save the message as a draft in the local database as the user types.
    - Ability to resume a draft from the "Drafts" folder.
- **Backend Sending:**
    - Configure SMTP settings (host, port, encryption, etc.) separately in `.env`.
    - Implement a secure API endpoint to send the completed message.
    - Ensure sent messages are optionally (or by default) saved to the IMAP "Sent" folder.

## Non-Functional Requirements
- **Responsive Design:** The compose modal must work well on both desktop and mobile screens.
- **Immediate Feedback:** Provide visual cues when a message is being sent, saved as a draft, or when an error occurs.
- **Security:** Sanitize rich text input to prevent XSS before sending or storing.

## Acceptance Criteria
- [ ] Users can open a compose modal from the UI.
- [ ] Rich text formatting works correctly in the editor.
- [ ] CC and BCC recipients are correctly handled by the backend.
- [ ] Attachments are successfully sent with the email.
- [ ] Drafts are saved automatically and can be reopened.
- [ ] Emails are successfully delivered via the configured SMTP server.

## Out of Scope
- Address book/contact autocomplete (can be a separate track).
- Scheduling emails for later delivery.
- Recipient "read receipts".
