# Specification: Project Fresh Start

## 1. Overview
Reset the project to a clean state by removing all custom backend functionality while preserving the frontend layout, styles, components, and all documentation.

## 2. Goals
- Revert the backend to a "scaffolding only" state.
- Clear the database schema and all associated data.
- Remove all backend testing artifacts.
- Preserve the frontend UI structure and styling.
- Retain all project documentation (`*.md` files).

## 3. Functional Requirements
### 3.1 Backend Removal
- **Delete** all custom models (except `User`).
- **Delete** all custom controllers in `app/Http/Controllers/Api`.
- **Delete** all custom jobs in `app/Jobs`.
- **Delete** all custom services in `app/Services`.
- **Delete** any custom service providers or middleware added during development.
- **Wipe** custom routes from `routes/web.php` and `routes/api.php`, reverting them to default scaffolding.

### 3.2 Database & Migrations
- **Delete** all migrations in `database/migrations` except for the default Laravel migrations (users, cache, jobs, etc.).
- **Reset** the database to the default state by running a fresh migration after cleanup.

### 3.3 Tests & Configuration
- **Delete** all unit and feature tests in `tests/Unit` and `tests/Feature` that were added for custom functionality.
- **Keep** basic testing scaffolding if applicable.

### 3.4 Preservation (Out of Scope)
- **Do NOT delete** any files in `resources/js/Components` or `resources/css`.
- **Do NOT delete** any Svelte components or frontend layout files.
- **Do NOT delete** any `*.md` files (e.g., `README.md`, `GEMINI.md`, or Conductor documentation).

## 4. Acceptance Criteria
- [ ] Backend contains only standard Laravel scaffolding.
- [ ] Database is reset to the default Laravel schema.
- [ ] Custom backend tests are removed.
- [ ] Frontend layout and styles remain intact and functional.
- [ ] All Markdown documentation is preserved.
