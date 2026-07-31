# Brava CMS

**Brava CMS** is a modular, high-performance, and reusable Headless CMS built with **Laravel 13** and **Tailwind CSS v4**, specifically designed to power modern company profile websites and web applications via a clean REST API (e.g., Next.js frontend).

---

## Key Features & Architecture

- **Headless & Reusable:** Serves as a standalone backend CMS and REST API. Does not couple frontend templates; easily reusable across multiple client projects.
- **Dynamic API Caching & Auto-Invalidation:** All public API endpoints are cached for high performance (`Cache::flexible`). The custom `App\Traits\ClearsApiCache` trait is attached to all CMS models to automatically flush and invalidate caches whenever an administrator creates, updates, or deletes content. Successful `GET /api/*` responses additionally include `Cache-Control` headers for browser/CDN caching.
- **SEO-Ready API:** Detail endpoints expose a consistent `seo` block (meta title/description, OpenGraph, `robots` directives, schema type, and `canonical_url`), image fields are returned as absolute URLs, and a `GET /api/sitemap` endpoint lists all publicly indexable content for frontend sitemap generation.
- **Media Library & Compression:** Centralized file management with automatic image resizing and compression (`Intervention/Image`) down to 1920px max width.
- **Portfolio Management:** Mandatory Cover Photo (`photo`) and strict gallery limits (maximum 4 detail images) enforced at both Form Request and UI levels.
- **Blog & SEO Management:** Automated `published_at` timestamp management upon status transitions, complete with embedded per-entity SEO tags (OpenGraph, Schema.org, meta titles/descriptions).
- **Testimonials & FAQs:** Streamlined testimonials supporting company/organization names (`client_name` with `company` alias) and reorderable FAQs.
- **Settings Module:** Grouped configuration management (`general`, `company`, `contact`, `social`, `seo`) with reliable checkbox and data type preservation.
- **Team Module:** Internal CMS support for team profiles with email uniqueness checks and conditional authentication fields.
- **Google Analytics 4 (GA4) Dashboard:** Integrated admin dashboard reporting (`/admin`) with hybrid "plug-and-play" architecture—automatic fallback to dynamic dummy analytics when GA4 credentials are not set, and smart caching (`Cache::flexible`) for 30 minutes fresh / 60 minutes stale.

---

## Documentation Roadmap

| Document | Purpose |
|---|---|
| [`docs/API.md`](docs/API.md) | Comprehensive REST API specification, JSON response structures, caching TTLs, and rate limiting rules. |
| [`docs/SCHEMA.md`](docs/SCHEMA.md) | Complete database table schemas, column types, and relationships. |
| [`docs/AI_CONTEXT.md`](docs/AI_CONTEXT.md) | Architectural overview, design decisions, and conventions for AI coding assistants. |
| [`docs/ANALYTICS.md`](docs/ANALYTICS.md) | Integration plan for Google Analytics 4 (GA4) inside the admin dashboard. |
| [`docs/GA4_SETUP_GUIDE.md`](docs/GA4_SETUP_GUIDE.md) | Step-by-step manual guide for Google Analytics 4 (GA4) activation and Google Cloud Service Account setup. |
| [`docs/AI_BEHAVIOUR.md`](docs/AI_BEHAVIOUR.md) | Behavioral guidelines for AI coding assistants working on Brava CMS. |

---

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3+)
- **Authentication:** Session-based Laravel Breeze (for Admin Dashboard)
- **Frontend (Admin Panel):** Blade Templates + Tailwind CSS v4 + Vite
- **Database:** MySQL / SQLite (with Soft Deletes & eager loading N+1 prevention)
- **Testing:** Pest PHP (100% automated test coverage)

---

## Getting Started

1. **Clone & Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

2. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Set `APP_URL` (backend domain) and `FRONTEND_URL` (public website domain) — used for absolute image URLs and SEO canonical URLs.

3. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

4. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

5. **Start Development Server:**
   ```bash
   composer run dev
   # or
   php artisan serve
   npm run dev
   ```

---

## Testing & Code Quality

Brava CMS adheres strictly to PSR-12 and Laravel Pint style rules, with automated testing powered by Pest PHP.

```bash
# Run all automated tests
php artisan test --compact

# Check code formatting
vendor/bin/pint --format agent
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
