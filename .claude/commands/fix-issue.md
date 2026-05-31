---
name: fix-issue
argument-hint: [issue-number]
---

Fix GitHub issue #$ARGUMENTS:
1. `gh issue view $ARGUMENTS` -- read the entire issue and understand its context.
2. Find relevant source files:
   - Search in `app/Http/Controllers/`, `app/Services/`, `app/Models/`, and `app/Http/Middleware/`.
   - For UI issues, check `resources/views/`, `resources/css/`, and `resources/js/`.
   - For database issues, check `database/migrations/` and `database/seeders/`.
3. Implement the minimal fix:
   - Do not modify files outside the scope of the issue.
   - Keep business logic inside Service classes instead of Controllers.
   - For image-related tasks, use `ImageService` exclusively.
   - For order/transaction tasks, use `OrderService` and `OrderRepository`.
   - Clear caches after the fix using `php artisan config:clear && php artisan cache:clear`.
4. Write a regression test in `tests/Feature/` or `tests/Unit/`:
   - Ensure the test fails before the fix and passes after it.
   - Use the `RefreshDatabase` trait if database transactions are involved.
5. `php artisan test` -- verify that all tests run green.
6. Commit: `git add -p` and then `git commit -m "fix: description (closes #$ARGUMENTS)"`
