---
paths:
  - "app/Models/**/*.php"
  - "database/migrations/**/*.php"
  - "database/seeders/**/*.php"
  - "database/factories/**/*.php"
---

# Database & Model Rules for Dunya-Alatfaal-Shop

- **Models**:
  - Always explicitly declare the `$fillable` property.
  - **Never** use `$guarded = []` on critical financial and transaction models (`Order`, `Transaction`, `Coupon`, `User`).
  - Cast decimal pricing structures, dates (`delivered_date`, `canceled_date`), and booleans to their native PHP types using Laravel model casting.
- **Migrations**:
  - Ensure all database schemas have appropriate indexes for columns frequently used in filtering or searching (e.g. `SKU`, `slug`, foreign keys like `category_id`, `brand_id`, status flags).
  - Explicitly define relationships using foreign keys. Use cascading updates and deletes only when mathematically/logically safe.
  - Keep transaction-related dates nullable by default, allowing status transitions.
- **Data Integrity**:
  - Encapsulate multiple database writing operations (e.g., creating an order + items + transaction + updating stock) within a database transaction block (`DB::transaction`).
- **Seeders & Factories**:
  - Define model factories for core models (`User`, `Product`, `Order`, `Category`) using Faker.
  - Implement realistic seeders to generate consistent sample data during local initialization.
