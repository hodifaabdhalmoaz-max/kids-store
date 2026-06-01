---
paths:
  - "routes/api.php"
  - "app/Http/Controllers/Api/**/*.php"
  - "app/Http/Resources/**/*.php"
---

# API Rules

- Keep API routes in `routes/api.php` under `/api/` prefix with `ApiRateLimit` middleware.
- Validate requests using dedicated FormRequest classes; return `422` with JSON validation details.
- Return standardized JSON: `{ "success": bool, "data": {}, "message": "..." }` with correct HTTP status codes.
- Never return Eloquent models directly — use Laravel API Resources (`JsonResource` / `ResourceCollection`) with eager-loaded relations.
- Hide internal column names and sensitive data in the resource's `toArray()` method.
