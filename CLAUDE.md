# Project Brain: Dunya-Alatfaal-Shop

This project is a premium e-commerce storefront for kids' products, designed to provide a playful, secure, and fast browsing experience.

## Stack
- **Core Framework**: Laravel 11 & PHP 8.2+
- **Database**: MySQL (Eloquent ORM)
- **Frontend**: Blade Templating + SASS/SCSS + Vanilla JS (bundled via Vite)
- **Caching**: Redis / File cache (abstracted via `CacheService`)
- **Commerce Tools**: `surfsidemedia/shoppingcart` (Shopping Cart) & Intervention Image (Image Processing)
- **Production Server**: Hostinger Shared Hosting (Production Environment)

## Commands
- **Local Dev Server**: `php artisan serve`
- **Asset Watcher**: `npm run dev`
- **Asset Compiler**: `npm run build`
- **Run Test Suite**: `php artisan test`
- **Lint & Format Code**: `./vendor/bin/pint` (run without arguments to format, or `--test` to check)
- **Static Analysis**: `./vendor/bin/phpstan analyse`
- **Clear Cache & Config**: `php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear`
- **Database Migrations**: `php artisan migrate` (or `php artisan migrate --force` in production)

## Conventions & Rules
- **Architecture**: Always follow the **Controller-Service-Repository** pattern.
  - Keep Controllers clean: they should only validate request inputs, invoke Services, and return Views/JSON.
  - Place core business operations (e.g. order creation, notifications) inside **Service** classes.
  - Interface with the database via **Repositories** to keep queries organized and reusable.
- **Security & Rate Limiting**:
  - Restrict access to admin directories with the `AuthAdmin` middleware, and user dashboards with `auth`.
  - All public, cart, search, and checkout routes must carry a `smart.throttle:[profile]` middleware guard.
  - Never allow raw user-provided input in SQL query strings; always bind parameters.
- **Performance**:
  - Eager-load model relationships (e.g. `category`, `brand`) using `with()` to prevent **N+1 query loops** on pages rendering lists.
  - Cache heavy operations (home sliders, stats aggregates, filtering arrays) via `CacheService`.
- **Media Uploads**:
  - Standardize image processing: all uploads must route through `ImageService` to automatically convert files to `.webp` format and generate thumbnails.
- **Localization**:
  - Make all user-facing copy translatable by wrapping text in `__('...')` or `@lang('...')`.
  - Keep both English and Arabic translations synchronized inside `lang/en/` and `lang/ar/` folders.
