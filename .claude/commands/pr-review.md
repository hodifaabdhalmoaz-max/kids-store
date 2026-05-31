---
name: pr-review
argument-hint: [pr-number]
---

Perform a comprehensive review of GitHub Pull Request #$ARGUMENTS:

1. `gh pr checkout $ARGUMENTS` -- checkout the pull request branch locally.
2. `gh pr view $ARGUMENTS` -- read the PR title, body, and discussions to understand the objective.
3. Audit changed files against the project guidelines:
   - **Security**: Ensure new controllers use appropriate middleware (`smart.throttle`, `auth`, `AuthAdmin`). Check for XSS in Blade views and ensure forms have `@csrf`.
   - **Performance**: Eager-load relations to prevent N+1 queries. Check for proper cache utilization with `CacheService`.
   - **Code Quality**: Confirm logic resides inside Services rather than Controllers, and DB operations route through Repositories.
   - **Localization**: Verify that all user-facing strings are wrapped in `__('...')` or `@lang('...')`.
4. Run tests on the checked-out branch:
   - Run `php artisan test` to verify no regressions exist.
5. Post the review on GitHub using `gh pr review $ARGUMENTS`:
   - Group findings under Security, Performance, and Code Quality.
   - Tag issues as CRITICAL, WARNING, or SUGGESTION.
   - Approve the PR if clean, or request changes if any CRITICAL issue is found.
