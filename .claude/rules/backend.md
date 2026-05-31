---
paths:
  - "app/Http/Controllers/*.php"
  - "app/Http/Controllers/Admin/**/*.php"
  - "app/Services/**/*.php"
  - "app/Repositories/**/*.php"
  - "app/Http/Middleware/**/*.php"
  - "app/Providers/**/*.php"
---

# Backend Rules for Dunya-Alatfaal-Shop

- **Architecture Standards**:
  - Follow the **Controller-Service-Repository** pattern. Keep controllers light.
  - Controllers must only handle validation, delegation, and formatting the response.
  - Business logic (calculations, transactions, order creation, notifications) belongs in **Services** (`OrderService`, `CartService`, `ImageService`, etc.).
  - Database queries should be routed through **Repositories** to abstract Eloquent query-building.
- **Database Eager Loading**:
  - Eager-load relations (`category`, `brand`, `colors`, `sizes`) using `with()` to prevent **N+1 query problems** in paginated or loops listings.
- **Security & Authorization**:
  - Restrict access to admin features using the `['auth', AuthAdmin::class]` middleware.
  - Restrict user dashboards using the `['auth']` middleware stack.
  - Do not bypass security checks in user requests; scope queries using `Auth::id()` or `Auth::user()` directly to prevent IDOR vulnerabilities.
- **Rate Limiting**:
  - Wrap routes in the custom `smart.throttle` middleware using the appropriate type:
    - `public` for public catalog pages (120-240 req/min).
    - `search` for search queries (40 req/min).
    - `cart` for shopping cart manipulations (60 req/min).
    - `checkout` for placing orders (30 req/min).
- **Caching**:
  - Use `CacheService` to handle key generation, tags, and fallback logic for high-traffic pages (e.g. search filters, categories, homepage sliders, featured items).
  - Explicitly clear/forget related cache keys when models are updated or deleted (e.g. forgetting `search_filters` upon product store).
- **Image Uploads**:
  - Always handle uploaded files using the `ImageService` to automatically convert images to `.webp`, coverage-crop, and generate proper thumbnails.
