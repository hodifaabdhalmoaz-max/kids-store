---
name: deploy
argument-hint: [branch-name]
---

Deploy Dunya-Alatfaal-Shop to Hostinger Shared Hosting (target branch defaults to production/main if $ARGUMENTS is empty):

1. Compile and build front-end assets locally (Hostinger shared hosting usually lacks Node/NPM or resources to compile assets on-server):
   - Run `npm run build`
2. Run local tests and code styling checks to prevent breaking production:
   - Run `php artisan test` -- ensure all tests pass.
   - Run `./vendor/bin/pint` -- ensure consistent code formatting.
3. Commit compiled assets (like `public/build/manifest.json`, CSS, and JS) and push to the deployment branch:
   - `git add public/build/`
   - `git commit -m "build: compile assets for deployment"`
   - `git push origin ${ARGUMENTS:-main}`
4. SSH into Hostinger server (or run deployment scripts):
   - Run `git pull origin ${ARGUMENTS:-main}`
   - Run `composer install --no-dev --optimize-autoloader`
   - Run database migrations: `php artisan migrate --force`
5. Cache settings for production performance:
   - Run `php artisan config:cache`
   - Run `php artisan route:cache`
   - Run `php artisan view:cache`
6. Run the OPCache clear/reset script (Hostinger shared hosting caches PHP files; running `opcache_reset()` via web/artisan is required to reflect changes instantly).
7. Verify deployment:
   - Run a request to the health check endpoint `/admin/test-revenue` or home page.
