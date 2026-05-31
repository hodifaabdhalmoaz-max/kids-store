---
paths:
  - "resources/views/**/*.blade.php"
  - "resources/css/**/*.css"
  - "resources/sass/**/*.scss"
  - "resources/js/**/*.js"
  - "public/css/**/*.css"
  - "public/js/**/*.js"
---

# Frontend Rules for Dunya-Alatfaal-Shop

- **Blade & Templates**:
  - Always inherit from `layouts.app` or the designated layout.
  - Maintain a clean heading structure (single `<h1>` per page with proper semantic subheadings `<h2>`, `<h3>`).
- **Styling & RTL**:
  - Code with **RTL (Right-to-Left)** layout as default/priority, ensuring spacing (margins/paddings) does not break when switching locales.
  - Avoid inline CSS styles; use custom styling in Sass/CSS components or responsive utilities.
- **Form Integrity**:
  - All state-mutating forms (POST/PUT/DELETE) **must** include the `@csrf` directive.
  - Use the `@method('PUT')`, `@method('PATCH')`, or `@method('DELETE')` directives when submitting non-POST update/delete actions.
- **Asset Loading**:
  - Use Vite directive `@vite(['resources/sass/app.scss', 'resources/js/app.js'])` for asset compilation.
  - Load public static media using the `asset()` helper.
- **Images**:
  - Ensure all product, brand, and category images are displayed via responsive tags, leveraging converted `.webp` assets.
  - Fall back to standard placeholder images if the model's image field is null.
- **Localization**:
  - Never hardcode Arabic or English texts directly in the views. Use `__('translation.key')` or `@lang('translation.key')` for translatable strings.
  - Translation files must be updated in both `lang/ar/` and `lang/en/` directories.
