# Implementation Plan: Improve Layout and Design UI/UX

## Phase 1: Authentication UI and App Shell Polish [checkpoint: 9ba773a]
- [x] Task: Polishing the Login screen d70f427
    - [x] Write failing unit/component tests for Login design updates.
    - [x] Refactor `Login.svelte` with high-contrast elements and improved layout.
- [x] Task: Refine Sidebar and Toolbar 777528d
    - [x] Write failing tests for Sidebar active states and Toolbar layout.
    - [x] Update `Sidebar.svelte` and `Toolbar.svelte` with Heroicons and improved spacing.
- [ ] Task: Conductor - User Manual Verification 'Authentication UI and App Shell Polish' (Protocol in workflow.md)

## Phase 2: Email List and Contextual Actions
- [x] Task: Implement Loading Skeletons 00e2667
    - [x] Write failing tests for loading state display.
    - [x] Create `EmailSkeleton.svelte` and integrate into `EmailList.svelte`.
- [x] Task: Enhance Email List items and actions af1cf31
    - [x] Write failing tests for hover actions and high-contrast typography.
    - [x] Update `EmailList.svelte` with improved row design and contextual buttons (Archive/Delete placeholder).
- [ ] Task: Conductor - User Manual Verification 'Email List and Contextual Actions' (Protocol in workflow.md)

## Phase 3: Message View and UX Patterns
- [ ] Task: Refine Reading Pane Modal
    - [ ] Write failing tests for Modal typography and action buttons.
    - [ ] Update `ReadingPane.svelte` and the modal container in `App.svelte` with improved design.
- [ ] Task: Implement Keyboard Shortcuts
    - [ ] Write failing tests for 'j', 'k', and 'esc' key events.
    - [ ] Implement global keyboard listener in `App.svelte` to handle navigation and modal closure.
- [ ] Task: Conductor - User Manual Verification 'Message View and UX Patterns' (Protocol in workflow.md)
