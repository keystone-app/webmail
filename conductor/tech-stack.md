# Technology Stack: Laravel Webmail

## Language and Frameworks
- **Backend:** PHP 8.5 with [Laravel 12](https://laravel.com/).
- **Frontend:** [Svelte 5](https://svelte.dev/) with [Tailwind CSS 4](https://tailwindcss.com/), [svelte-routing](https://github.com/EmilTholin/svelte-routing), and Vite.
- **Core Logic:** IMAP authentication and message retrieval.
- **Background Tasks:** [Laravel Queues](https://laravel.com/docs/12.x/queues) for asynchronous IMAP synchronization.

## Data Management
- **Database:** [MySQL](https://www.mysql.com/).
- **Migrations:** Laravel schema migrations.

## Testing and Quality
- **Unit/Feature Testing:** [PHPUnit 11](https://phpunit.de/).
- **Component Testing:** [Vitest](https://vitest.dev/) with [Svelte Testing Library](https://testing-library.com/docs/svelte-testing-library/intro).
- **Code Style:** [Laravel Pint](https://laravel.com/docs/12.x/pint).
- **Type Safety:** PHP strict types where applicable.

## Environment and Infrastructure
- **Development Environment:** [Laravel Sail](https://laravel.com/docs/12.x/sail) (Docker).
- **Package Managers:** Composer for PHP, NPM for JavaScript.
