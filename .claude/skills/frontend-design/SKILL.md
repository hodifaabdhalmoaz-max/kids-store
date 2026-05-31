---
name: frontend-design
description: Apply the exact kids-store friendly design and styling standards to any UI component or landing page
user-invocable: true
---

# Kids-Store Frontend Design Standards (Dunya-Alatfaal-Shop)

Apply these design principles to ensure a playful, warm, premium, and kid-friendly environment while maintaining accessibility and readability.

## 1. Color Palette (Soft, Friendly Pastels)
- **Primary Accent**: Warm Coral/Orange (`#FF8C61`) -- used for primary buttons, call-to-actions, and highlights.
- **Secondary Accent**: Gentle Sky Blue/Teal (`#64C9CF` or `#4ECDC4`) -- used for category tags, badges, and secondary highlights.
- **Supportive Accent**: Pastel Sunny Yellow (`#FFE17D`) -- used for rating stars, discounts, and celebratory icons.
- **Background**: Soft Warm Cream/White (`#FDFBF7`) -- avoids sterile flat whites to give a cozy, friendly feel.
- **Surfaces/Cards**: Pure White (`#FFFFFF`) backed by soft, shallow shadows: `box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02), 0 1px 8px rgba(0, 0, 0, 0.01)`.

## 2. Typography & Text Hierarchy
- **Fonts**: 
  - Arabic: **Tajawal** or **Cairo** (high readability with organic curves).
  - English: **Outfit** or **Quicksand** (rounded, friendly geometric sans-serif).
- **Sizing**:
  - Hero Headers: 36px - 48px (bold, playful alignment).
  - Section Headers: 24px - 28px.
  - Card/Product Titles: 16px - 18px (semi-bold).
  - Body Text: 14px - 15px (clean grey `#4A4A4A` for high readability, never harsh pure black).

## 3. Spacing & Borders (Soft & Round)
- **Corner Radii**:
  - Cards & Modals: `20px` or `24px` (extra rounded corners to represent safety and child-friendliness).
  - Buttons & Badges: `12px` or pill-shaped (`50px`).
  - Input Fields: `12px` or `16px`.
- **Card Padding**:
  - Standard Product Card: `16px` to `20px`.
  - Details/Review Sections: `24px` to `32px`.
- **Section Spacing**:
  - Generous vertical gap of `64px` to `96px` to prevent clutter and allow clean breathing room.

## 4. Playful Micro-Interactions & Hover States
- **Smooth Transitions**: Always append `transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)`.
- **Card Hovers**: Apply a gentle lift and scale effect: `transform: translateY(-6px)` and increase shadow opacity slightly.
- **Button Clicks**: Soft scale-down feedback on click: `transform: scale(0.96)`.
- **Badges**: Cheerful tags for offers (e.g. "خصم %20" or "عرض خاص") should use bouncy hover rotations (`transform: rotate(-2deg)`).
- **Icons**: Soft line-drawing SVG icons (toys, stars, shopping bags) with warm colored backgrounds.
