---
paths:
  - "routes/api.php"
  - "app/Http/Controllers/Api/**/*.php"
  - "app/Http/Resources/**/*.php"
---

# API Rules for Dunya-Alatfaal-Shop

- **API Routes**:
  - Keep API routes in `routes/api.php` and prefix them under `/api/` using appropriate group routing.
  - Apply the dedicated `ApiRateLimit` middleware to prevent public endpoint abuse.
- **Request Validation**:
  - Always validate incoming requests using dedicated FormRequest classes.
  - Ensure validation errors return a clean `422 Unprocessable Entity` response with validation message details formatted in JSON.
- **Response Format**:
  - Return responses in a standardized JSON envelope:
    ```json
    {
      "success": true,
      "data": {},
      "message": "Operated successfully"
    }
    ```
  - Always set the correct HTTP status code (200 OK, 201 Created, 401 Unauthenticated, 403 Forbidden, 404 Not Found, 422 Validation Error, 429 Too Many Requests).
- **Data Serialization**:
  - Do not return Eloquent models directly. Eager-load relations and format the payload using **Laravel API Resources** (`JsonResource` and `ResourceCollection`).
  - Keep database-internal names and sensitive columns hidden by defining precise mapping in the API resource's `toArray($request)` method.
