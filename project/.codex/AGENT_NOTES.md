# Agent Notes

## Project Facts
- Stack: Laravel 10 (PHP 8.1), Vite, Bootstrap, AdminLTE, DataTables, Spatie (Permission, MediaLibrary, Activitylog), Sanctum, DomPDF, PhpWord.
- Key dirs: `app/`, `routes/`, `resources/`, `database/`, `public/`, `tests/`, `config/`, `storage/`.
- JS tooling: Vite (`npm run dev`, `npm run build`). PHP style via Pint.
- Test framework: PHPUnit 10 (`vendor/bin/phpunit`, config in `phpunit.xml`).

## Commands
- Install: `composer install` and `npm install`.
- Dev: `php artisan serve` and `npm run dev` (HMR).
- Build: `npm run build`.
- DB: `php artisan migrate --seed` (optional seeding).
- Style: `vendor/bin/pint`.

## Conventions
- PSR-12; classes `StudlyCase`, methods `camelCase`, tables plural `snake_case`.
- Routes/URIs kebab-case; Blade views under `resources/views` with kebab-case filenames.
- Conventional Commits (feat, fix, refactor) used in history.

## Decisions
- Keep diffs minimal and surgical; confirm before destructive actions or dependency changes.
- Follow Laravel flow: route → controller → service/repo → model → policy → form request → resource → view → tests.

## Open Questions
- None tracked yet. Capture requirements/edge cases here as they arise.

## Worklog
- 2025-08-31: Added `AGENTS.md` (Repository Guidelines) tailored to Laravel + Vite; included commands, style, tests, PR guidance, and security tips.
- 2025-08-31: Reviewed user-updated `AGENTS.md` (agent ops guide); aligned operating mode to plan-first, safe edits, and Laravel best practices.

## Next Steps
- Link `AGENTS.md` from `README.md` for visibility (optional).
- Add task-specific notes and diffs here on each change to speed future work.

