# DVC Web Development Internship Assessment
**Position:** Web Development Intern — Digital Visibility Concepts

Name:- Prem kumar

Email:- premkumar9122om@gmail.com

Portfolio:- https://premkr.framer.website/

---

## Files
| Question | File |
|---|---|
| Q1 – Product Card | `question1/index.html` |
| Q2 – WP Testimonials Plugin | `question2/testimonials-plugin.php` |
| Q3 – Weather Dashboard | `question3/weather-dashboard.html` |

---

## Q1 – Responsive Product Card Component

**Approach:**
Built a mobile-first grid of product cards using semantic HTML5 (`<article>`, `<blockquote>`) with ARIA labels throughout. The design uses CSS custom properties for consistent theming (cream/gold/ink palette), `aspect-ratio` for the image wrapper, and CSS transitions for hover effects.

Quantity logic is self-contained per card — each card tracks its own `qty` variable and disables the decrement/increment buttons at the min/max boundaries. The "Add to Cart" click logs full details to the console and shows an animated success banner that auto-dismisses after 3 seconds.

**Assumptions:** Used placeholder Unsplash images. Broken-image fallback is demonstrated via `onerror` handler toggling a CSS class.

**Time spent:** ~2.5 hours

---

## Q2 – WordPress Testimonials Plugin

**Approach:**
A single-file plugin following WordPress coding standards. Structure:

- `register_post_type()` with Gutenberg (`show_in_rest: true`) and `dashicons-quote` menu icon
- Meta box using `add_meta_box()` with a nonce, sanitization (`sanitize_text_field`, `absint`), and capability check before saving
- `[testimonials]` shortcode accepting `count`, `orderby`, and `order` parameters, all sanitized with allowlists before use in `WP_Query`
- CSS and JS injected via `wp_add_inline_style` / `wp_add_inline_script` to avoid extra HTTP requests
- Slider built with vanilla JS — keyboard-navigable (ArrowLeft/ArrowRight), `aria-hidden` toggled per slide, and `aria-live` on the counter

**Assumptions:** Plugin targets WP 6.0+. The slider JS uses no external dependencies.

**Time spent:** ~3 hours

---

## Q3 – Weather Dashboard

**Approach:**
Single-file SPA using vanilla JS with `async/await` and `try/catch` throughout. API calls go to the OpenWeatherMap v2.5 endpoints (`/weather` and `/forecast`).

State management:
- Three mutually exclusive UI states (loading, error, content) toggled with `display` and a `.visible` class
- `aria-live` regions ensure screen readers announce loading and error states

Forecast logic: The free-tier `/forecast` endpoint returns 3-hour intervals. The code groups them by date and picks the reading closest to noon for each day, giving a clean 5-day view.

LocalStorage: wrapped in `try/catch` so the app degrades gracefully if storage is unavailable (private browsing, storage quota exceeded, etc.).

**Time spent:** ~1.5 hours

---

## Notes
- All code is cross-browser compatible (Chrome, Firefox, Safari, Edge)
- No external JS frameworks used across any solution
- All user-facing strings are accessible (proper ARIA labels, semantic roles, live regions)

  made by prem kumar
