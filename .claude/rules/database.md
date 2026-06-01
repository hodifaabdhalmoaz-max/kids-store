---
paths:
  - "app/Models/**/*.php"
  - "database/migrations/**/*.php"
  - "database/seeders/**/*.php"
  - "database/factories/**/*.php"
---

# Database & Model Rules

- Always declare `$fillable` explicitly; never use `$guarded = []` on `Order`, `Transaction`, `Coupon`, `User`.
- Cast decimal prices, dates (`delivered_date`, `canceled_date`), and booleans to native PHP types via model casts.
- Add indexes on frequently filtered/searched columns (`SKU`, `slug`, foreign keys, status flags).
- Define foreign keys with cascading updates/deletes only when logically safe; keep transaction dates nullable.
- Wrap multi-write operations (order + items + transaction + stock update) in `DB::transaction()`.
- Define model factories for `User`, `Product`, `Order`, `Category` using Faker; implement realistic seeders.
