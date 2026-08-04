# Brava CMS — API Specification

> REST API consumed by Next.js frontend. All responses follow a unified format.

---

## Base URL

```
Base URL: `https://brava.id` for production, `http://localhost:3000` for local Next.js.
```

## Response Format

### Success (single)

```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": { }
}
```

### Success (collection)

```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [ ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 12,
        "from": 1,
        "to": 12,
        "total": 50,
        "links": {
            "first": "http://localhost:8000/api/services?page=1",
            "last": "http://localhost:8000/api/services?page=5",
            "prev": null,
            "next": "http://localhost:8000/api/services?page=2"
        }
    }
}
```

> **Note:** Image fields (`featured_image`, `photo`, `og_image`, `avatar`, `url`, etc.) are returned as **absolute URLs** based on `APP_URL`. Canonical URLs are built from the `FRONTEND_URL` env variable.

### Validation Error

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### Not Found

```json
{
    "success": false,
    "message": "Resource not found"
}
```

## Global Query Parameters

| Param        | Type   | Description                                                   |
| ------------ | ------ | ------------------------------------------------------------- |
| `page`     | int    | Page number (default: 1)                                      |
| `per_page` | int    | Items per page (default: 12, max: 50)                         |
| `fields`   | string | Comma-separated field names for sparse response               |
| `sort`     | string | Field to sort by, prefix`-` for DESC (e.g. `-created_at`) |

---

## Endpoints

### Settings

> Public, no auth. Returns all active site settings keyed by group.

#### `GET /api/settings`

Response:

```json
{
    "success": true,
    "message": "Settings retrieved successfully",
    "data": {
        "general": {
            "site_name": "Brava CMS",
            "site_description": "A reusable Headless CMS",
            "logo": "/storage/logo.png",
            "favicon": "/storage/favicon.ico"
        },
        "seo": {
            "default_meta_title": "Brava CMS",
            "default_meta_description": "...",
            "default_og_image": "/storage/og-default.jpg",
            "google_analytics_id": "G-XXXXXXXXXX",
            "google_verification": "...",
            "organization_schema": "{...}"
        },
        "social": {
            "instagram_url": "https://instagram.com/...",
            "facebook_url": "https://facebook.com/..."
        },
        "contact": {
            "address": "Jl. Contoh No. 123",
            "email": "info@example.com",
            "phone": "+62 812 3456 7890"
        }
    }
}
```

> **SEO note:** Next.js should use `seo` group for global meta defaults.

---

### Categories

> Public, no auth.

#### `GET /api/categories`

> The `type` query parameter is **required**. Categories are filtered by content type (e.g. `blog`, `portfolio`). A request without `type` returns `422` with `Type parameter is required`.

| Param    | Type   | Description                                                                   |
| -------- | ------ | ----------------------------------------------------------------------------- |
| `type` | string | **Required.** Category content type. Supported: `blog`, `portfolio` |

Example: `GET /api/categories?type=blog`

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Technology",
            "slug": "technology",
            "description": "Tech industry insights",
            "sort_order": 1
        }
    ]
}
```

---

### Services

#### `GET /api/services`

| Param        | Type   | Description                  |
| ------------ | ------ | ---------------------------- |
| `search`   | string | Search in title/description  |
| `per_page` | int    | Items per page (default: 12) |

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Corporate Website Package",
            "slug": "corporate-website-package",
            "description": "Short description",
            "photo": "/storage/services/img.jpg"
        }
    ],
    "meta": { "current_page": 1, "last_page": 1, "per_page": 12, "total": 3 }
}
```

> Services have no detail page; the list is the only `services` endpoint.

---

### Blogs

#### `GET /api/blogs`

| Param        | Type   | Description                  |
| ------------ | ------ | ---------------------------- |
| `category` | string | Filter by category slug      |
| `featured` | bool   | Only featured posts          |
| `search`   | string | Search in title/excerpt      |
| `page`     | int    | Page number                  |
| `per_page` | int    | Items per page (default: 10) |

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Blog Post Title",
            "slug": "blog-post-title",
            "excerpt": "Short excerpt...",
            "featured_image": "/storage/blogs/img.jpg",
            "author": {
                "id": 1,
                "name": "Admin"
            },
            "categories": [
                { "id": 1, "name": "Technology", "slug": "technology" }
            ],
            "published_at": "2026-07-27T10:00:00Z",
            "created_at": "2026-07-27T10:00:00Z"
        }
    ],
    "meta": { "current_page": 1, "last_page": 5, "per_page": 10, "total": 48 }
}
```

#### `GET /api/blogs/{slug}`

Response:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Blog Post Title",
        "slug": "blog-post-title",
        "excerpt": "Short excerpt...",
        "content": "<p>Full HTML content</p>",
        "featured_image": "/storage/blogs/img.jpg",
        "author": {
            "id": 1,
            "name": "Admin"
        },
        "categories": [
            { "id": 1, "name": "Technology", "slug": "technology" }
        ],
        "published_at": "2026-07-27T10:00:00Z",
        "seo": {
            "meta_title": "Blog Post Title | Brava CMS",
            "meta_description": "SEO description",
            "og_title": "Blog Post Title",
            "og_description": "SEO description",
            "og_image": "https://brava.id/storage/blogs/og.jpg",
            "robots_index": true,
            "robots_follow": true,
            "schema_type": "Article",
            "canonical_url": "https://brava.id/blogs/blog-post-title"
        },
        "updated_at": "2026-07-27T10:00:00Z"
    }
}
```

---

### Portfolio

#### `GET /api/portfolio`

| Param        | Type   | Description                  |
| ------------ | ------ | ---------------------------- |
| `search`   | string | Search in title/description  |
| `per_page` | int    | Items per page (default: 12) |

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "TechCorp Corporate Website",
            "slug": "techcorp-corporate-website",
            "description": "Short description",
            "client": "TechCorp Indonesia",
            "photo": "/storage/portfolio/cover.jpg",
            "photo_alt": "TechCorp Cover Photo",
            "featured_image": "/storage/portfolio/cover.jpg",
            "completed_at": "2026-05-15",
            "service": {
                "id": 1,
                "title": "Corporate Website Package",
                "slug": "corporate-website-package"
            }
        }
    ],
    "meta": { "current_page": 1, "last_page": 1, "per_page": 12, "total": 3 }
}
```

#### `GET /api/portfolio/{slug}`

Response:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "TechCorp Corporate Website",
        "slug": "techcorp-corporate-website",
        "description": "Full description",
        "specifications": [
            { "key": "Material", "value": "Lacoste CVC" },
            { "key": "Teknik Logo", "value": "Bordir" }
        ],
        "features": ["Nyaman digunakan", "Warna tahan lama"],
        "client": "TechCorp Indonesia",
        "photo": "/storage/portfolio/cover.jpg",
        "photo_alt": "TechCorp Cover Photo",
        "featured_image": "/storage/portfolio/cover.jpg",
        "completed_at": "2026-05-15",
        "service": {
            "id": 1,
            "title": "Corporate Website Package",
            "slug": "corporate-website-package"
        },
        "media": [
            {
                "id": 10,
                "url": "/storage/portfolio/gallery1.jpg",
                "alt_text": "Detail View 1"
            }
        ]
    }
}
```

---

### Testimonials

#### `GET /api/testimonials`

Response (no pagination — returns all active, sorted by `sort_order`):

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "client_name": "TechCorp Indonesia",
            "company": "TechCorp Indonesia",
            "content": "Great service! Highly recommended.",
            "rating": 5,
            "avatar": "/storage/testimonials/avatar.jpg"
        }
    ]
}
```

---

### FAQs

#### `GET /api/faqs`

| Param        | Type   | Description            |
| ------------ | ------ | ---------------------- |
| `category` | string | Filter by FAQ category |

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "question": "What services do you offer?",
            "answer": "<p>We offer...</p>",
            "category": "General"
        }
    ]
}
```

---

### Contact Form

#### `POST /api/contact`

Request:

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+62 812 3456 7890",
    "subject": "Inquiry",
    "message": "I would like to know more about your services."
}
```

Response:

```json
{
    "success": true,
    "message": "Thank you for your message. We will get back to you soon."
}
```

---

### Sitemap

#### `GET /api/sitemap`

Returns all publicly indexable content as a single list — used by Next.js to generate `sitemap.xml`. Cached and auto-invalidated on content changes.

Response:

```json
{
    "success": true,
    "data": [
        {
            "type": "blog",
            "slug": "blog-post-title",
            "loc": "https://brava.id/blogs/blog-post-title",
            "lastmod": "2026-07-27T10:00:00Z"
        },
        {
            "type": "portfolio",
            "slug": "techcorp-corporate-website",
            "loc": "https://brava.id/portfolio/techcorp-corporate-website",
            "lastmod": "2026-07-27T10:00:00Z"
        },
        {
            "type": "promo",
            "slug": "40-diskon-untuk-pemesanan-seragam-perusahaan",
            "loc": "https://brava.id/promos/40-diskon-untuk-pemesanan-seragam-perusahaan",
            "lastmod": "2026-07-30T10:00:00Z"
        },
        {
            "type": "service",
            "slug": "corporate-website-package",
            "loc": "https://brava.id/services/corporate-website-package",
            "lastmod": "2026-07-27T10:00:00Z"
        },
        {
            "type": "category",
            "slug": "technology",
            "loc": "https://brava.id/blog?category=technology",
            "lastmod": "2026-07-27T10:00:00Z"
        }
    ]
}
```

> Only published blogs and active promos/portfolio/services are included. Drafts, archived, and inactive content are excluded. URLs are built from the `FRONTEND_URL` env variable.

---

## SEO Strategy for Next.js

### Per-Page SEO

Every detail endpoint returns a `seo` object where applicable. Next.js should map it to:

```tsx
// Example: pages/blogs/[slug].tsx
<Head>
  <title>{data.seo.meta_title}</title>
  <meta name="description" content={data.seo.meta_description} />
  <meta property="og:title" content={data.seo.og_title} />
  <meta property="og:description" content={data.seo.og_description} />
  <meta property="og:image" content={data.seo.og_image} />
  <meta name="robots" content={`${data.seo.robots_index ? 'index' : 'noindex'}, ${data.seo.robots_follow ? 'follow' : 'nofollow'}`} />
  <link rel="canonical" href={data.seo.canonical_url} />
  <script type="application/ld+json">{JSON.stringify(schemaJSON)}</script>
</Head>
```

`schemaJSON` for blogs should use `data.seo.schema_type` (e.g. `Article`, `BlogPosting`) with `data.published_at` → `datePublished` and `data.updated_at` → `dateModified`. Generate `sitemap.xml` from `GET /api/sitemap`.

### Global Fallback

If `meta_title` is empty on an entity, fallback to `default_meta_title` from `/api/settings`.

---

## Security & Rate Limiting

All public API routes are protected by rate limiting in `RouteServiceProvider` or via route middleware:

| Endpoint              | Limit       | Window   | Notes               |
| --------------------- | ----------- | -------- | ------------------- |
| `GET /api/*`        | 60 requests | 1 minute | Read-only endpoints |
| `POST /api/contact` | 5 requests  | 1 minute | Prevent spam        |
| `POST /api/contact` | 20 requests | 1 hour   | Hard ceiling per IP |

### Implementation

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // all GET endpoints
});

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1');
```

### Contact Form Protection

- Rate limit: 5/minute per IP
- Optional: Honeypot hidden field (implement in Form Request)
- No CAPTCHA for MVP — add later if spam becomes an issue

### CORS

CORS is wide-open for GET requests (Next.js needs it). For production, restrict `allowed_origins` to the actual frontend domain.

```php
// config/cors.php
'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
'allowed_methods' => ['GET', 'POST'],
```

---

## Performance Optimizations

### HTTP Cache Headers

Every successful `GET /api/*` response includes:

```
Cache-Control: public, max-age=900, s-maxage=900
```

- `max-age=900` (15 min): browser/Next.js cache
- `s-maxage=900`: CDN/shared cache — matches the internal Laravel cache TTL, so no stale-data gap when content changes
- `POST` requests and non-API routes are never publicly cached.

### Sparse Fieldsets

```
GET /api/services?fields=id,title,slug,description
GET /api/blogs?fields=id,title,slug,excerpt,published_at,author
```

### Caching Strategy (Laravel)

| Endpoint                  | TTL    | Strategy                           |
| ------------------------- | ------ | ---------------------------------- |
| `/api/settings`         | 1 hour | Cache::flexible([3600, 7200], ...) |
| `/api/categories`       | 1 hour | Cache::flexible                    |
| `/api/services`         | 15 min | Cache::flexible([900, 1800], ...)  |
| `/api/services`       | 15 min | Cache::flexible                    |
| `/api/blogs`            | 15 min | Cache::flexible                    |
| `/api/blogs/{slug}`     | 30 min | Cache::flexible                    |
| `/api/testimonials`     | 1 hour | Cache::flexible                    |
| `/api/faqs`             | 1 hour | Cache::flexible                    |
| `/api/portfolio`        | 30 min | Cache::flexible                    |
| `/api/promos/highlight` | 15 min | Cache::flexible([900, 1800], ...)  |
| `/api/promos`           | 15 min | Cache::flexible([900, 1800], ...)  |

---

### Promos & Special Offers

#### `GET /api/promos/highlight`

Returns the single active highlighted promo (hero banner & modal popup). Automatically falls back to the latest active promo if the highlighted promo has expired.

Response:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "40% Diskon Untuk Pemesanan Seragam Perusahaan",
        "slug": "40-diskon-untuk-pemesanan-seragam-perusahaan",
        "badge_text": "PROMO TERBATAS",
        "discount_info": "40%",
        "description": "Dapatkan potongan harga untuk pemesanan seragam...",
        "image": "http://localhost:8000/storage/promos/seragam-promo.jpg",
        "image_alt": "Diskon Seragam Perusahaan 40%",
        "valid_from": "2026-07-01 00:00:00",
        "valid_until": "2026-09-30 23:59:59",
        "wa_template": "Halo Brava, saya ingin klaim Diskon 40% Pemesanan Seragam...",
        "is_highlighted": true,
        "seo": {
            "meta_title": "40% Diskon Untuk Pemesanan Seragam Perusahaan",
            "meta_description": "Dapatkan potongan harga untuk pemesanan seragam...",
            "og_title": "40% Diskon Untuk Pemesanan Seragam Perusahaan",
            "og_description": "Dapatkan potongan harga untuk pemesanan seragam...",
            "og_image": "http://localhost:8000/storage/promos/seragam-promo.jpg",
            "og_image_alt": "Diskon Seragam Perusahaan 40%",
            "robots_index": true,
            "robots_follow": true,
            "schema_type": "SpecialAnnouncement",
            "canonical_url": "http://localhost:3000/promos/40-diskon-untuk-pemesanan-seragam-perusahaan"
        }
    }
}
```

#### `GET /api/promos`

Returns paginated list of active non-highlighted promos (`where('is_highlighted', false)`).

| Param        | Type | Description                            |
| ------------ | ---- | -------------------------------------- |
| `per_page` | int  | Items per page (default: 12, max: 100) |
| `page`     | int  | Page number                            |

Cache is automatically flushed via the `App\Traits\ClearsApiCache` trait on model `saved` / `deleted` events across all CMS models.

### N+1 Prevention

- Always use `with()` for relationships in controllers
- Enable `Model::preventLazyLoading()` in dev
- API Resources use `whenLoaded()` for optional relations

### Database Indexes

Every `slug`, `is_active`, `status`, `sort_order` column is indexed. See `docs/SCHEMA.md`.
