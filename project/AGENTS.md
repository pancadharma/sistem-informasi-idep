# Repository Guidelines

## Project Structure & Module Organization
- `app/`: Laravel app code (Controllers, Models, Services, Rules, Traits).
- `routes/`: HTTP endpoints (`web.php`, `api.php`).
- `resources/`: Frontend assets (Blade views, JS, SCSS) built via Vite.
- `database/`: Migrations, seeders, factories.
- `config/`, `lang/`: Framework and app configuration.
- `public/`: Web root for built assets; `storage/`: runtime files.
- `tests/`: PHPUnit tests (`Feature/`, `Unit/`).

## Build, Test, and Development Commands
- Install: `composer install` and `npm install`.
- Bootstrap env: `cp .env.example .env && php artisan key:generate`.
- Run DB (optional): `php artisan migrate --seed` (requires DB config).
- Dev server: `php artisan serve` and `npm run dev` (Vite with HMR).
- Production build: `npm run build`.
- Tests: `php artisan test` or `./vendor/bin/phpunit`.

## Coding Style & Naming Conventions
- PHP: PSR-12; format with Pint `./vendor/bin/pint`.
- JS/SCSS/Blade/PHP: Prettier + Blade/PHP plugins. Example: `npx prettier --write resources/**/*`.
- Indentation: spaces, size 4 (YAML: 2) per `.editorconfig`.
- Names: Classes `StudlyCase`, methods/vars `camelCase`, constants `UPPER_SNAKE_CASE`, routes use `kebab-case` URIs, controllers end with `Controller`.

## Testing Guidelines
- Framework: PHPUnit 10; test dirs `tests/Feature`, `tests/Unit`.
- File naming: `*Test.php`; use descriptive method names.
- Environment: uses `phpunit.xml` with `APP_ENV=testing`; prefer in-memory/isolated DB when applicable.
- Add tests for every bug fix and new feature.

## Commit & Pull Request Guidelines
- Commit style: Conventional Commits observed (e.g., `feat(dashboard): …`, `fix(routes): …`).
- Branches: `feat/…`, `fix/…`, `chore/…` tied to scope or issue.
- PRs: clear description, link issues (`Closes #123`), screenshots for UI, steps to reproduce/verify, and migration notes if schema changes.

## Security & Configuration Tips
- Never commit secrets; manage `.env`. Ensure `APP_KEY` is set.
- Run `php artisan storage:link` if media is served from storage.
- Review permissions/roles before seeding in non-dev environments.
