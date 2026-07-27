# Brava CMS — API Specification

> REST API consumed by Next.js frontend. All responses follow a unified format.

---

## Base URL

```
http://localhost:8000/api
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
            "first": "http://localhost:8000/api/products?page=1",
            "last": "http://localhost:8000/api/products?page=5",
            "prev": null,
            "next": "http://localhost:8000/api/products?page=2"
        }
    }
}
```

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

| Param | Type | Description |
|-------|------|-------------|
| `page` | int | Page number (default: 1) |
| `per_page` | int | Items per page (default: 12, max: 50) |
| `fields` | string | Comma-separated field names for sparse response |
| `sort` | string | Field to sort by, prefix `-` for DESC (e.g. `-created_at`) |

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
> Public, no auth. Shared categories polymorphically by `type`.

#### `GET /api/categories?type=product`

| Param | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | Yes | `product`, `blog`, or `portfolio` |

Response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Web Development",
            "slug": "web-development",
            "description": "Web development projects",
            "sort_order": 0
        }
    ]
}
```

---

### Products

#### `GET /api/products`

| Param | Type | Description |
|-------|------|-------------|
| `category` | string | Filter by category slug |
| `featured` | bool | Only featured products |
| `search` | string | Search in title/description |

Response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Product Name",
            "slug": "product-name",
            "description": "Short description",
            "price": 150000.00,
            "featured_image": "/storage/products/img.jpg",
            "categories": [
                { "id": 1, "name": "Category", "slug": "category" }
            ],
            "created_at": "2026-07-27T10:00:00Z"
        }
    ],
    "meta": { "current_page": 1, "last_page": 3, "per_page": 12, "total": 30 }
}
```

#### `GET /api/products/{slug}`

Response:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Product Name",
        "slug": "product-name",
        "description": "Full description",
        "content": "<p>HTML content</p>",
        "price": 150000.00,
        "is_featured": true,
        "categories": [
            { "id": 1, "name": "Category", "slug": "category" }
        ],
        "media": [
            { "id": 1, "url": "/storage/products/img1.jpg", "alt_text": "Product image" }
        ],
        "published_at": "2026-07-27T10:00:00Z",
        "seo": {
            "meta_title": "Product Name | Brava CMS",
            "meta_description": "Product description for SEO",
            "meta_keywords": "keyword1, keyword2",
            "og_title": "Product Name",
            "og_description": "Product description",
            "og_image": "/storage/products/og.jpg",
            "canonical_url": "https://example.com/products/product-name",
            "robots_index": true,
            "robots_follow": true,
            "schema_type": "Product"
        }
    }
}
```

> **SEO note:** Next.js should read `seo` object for `<head>` meta tags and JSON-LD schema.

---

### Blogs

#### `GET /api/blogs`

| Param | Type | Description |
|-------|------|-------------|
| `category` | string | Filter by category slug |
| `featured` | bool | Only featured posts |
| `search` | string | Search in title/excerpt |
| `page` | int | Page number |
| `per_page` | int | Items per page (default: 10) |

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
            "og_image": "/storage/blogs/og.jpg",
            "schema_type": "Article"
        }
    }
}
```

---

### Portfolio

#### `GET /api/portfolio`

| Param | Type | Description |
|-------|------|-------------|
| `category` | string | Filter by category slug |
| `search` | string | Search in title/description |

Response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Project Name",
            "slug": "project-name",
            "description": "Short description",
            "client": "Client Name",
            "project_url": "https://example.com",
            "completed_at": "2026-06-15",
            "categories": [
                { "id": 1, "name": "Web Design", "slug": "web-design" }
            ],
            "featured_image": "/storage/portfolio/img.jpg"
        }
    ],
    "meta": { "current_page": 1, "last_page": 2, "per_page": 12, "total": 18 }
}
```

#### `GET /api/portfolio/{slug}`

Response similar structure with full data + `seo` object.

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
            "client_name": "John Doe",
            "client_position": "CEO",
            "company": "Tech Corp",
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

| Param | Type | Description |
|-------|------|-------------|
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

### Team

#### `GET /api/team`

Response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Jane Doe",
            "position": "CEO & Founder",
            "bio": "Jane has 10+ years of experience...",
            "avatar": "/storage/team/jane.jpg",
            "email": "jane@example.com",
            "phone": "+62 812 3456 7890"
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

## SEO Strategy for Next.js

### Per-Page SEO
Every detail endpoint returns a `seo` object. Next.js should map it to:
```tsx
// Example: pages/products/[slug].tsx
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

### Global Fallback
If `meta_title` is empty on an entity, fallback to `default_meta_title` from `/api/settings`.

---

## Security & Rate Limiting

All public API routes are protected by rate limiting in `RouteServiceProvider` or via route middleware:

| Endpoint | Limit | Window | Notes |
|----------|-------|--------|-------|
| `GET /api/*` | 60 requests | 1 minute | Read-only endpoints |
| `POST /api/contact` | 5 requests | 1 minute | Prevent spam |
| `POST /api/contact` | 20 requests | 1 hour | Hard ceiling per IP |

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

### Sparse Fieldsets
```
GET /api/products?fields=id,title,slug,price,featured_image
GET /api/blogs?fields=id,title,slug,excerpt,published_at,author
```

### Caching Strategy (Laravel)
| Endpoint | TTL | Strategy |
|----------|-----|----------|
| `/api/settings` | 1 hour | Cache::flexible([3600, 7200], ...) |
| `/api/categories` | 1 hour | Cache::flexible |
| `/api/products` | 15 min | Cache::flexible([900, 1800], ...) |
| `/api/products/{slug}` | 30 min | Cache::flexible |
| `/api/blogs` | 15 min | Cache::flexible |
| `/api/blogs/{slug}` | 30 min | Cache::flexible |
| `/api/testimonials` | 1 hour | Cache::flexible |
| `/api/faqs` | 1 hour | Cache::flexible |
| `/api/team` | 1 hour | Cache::flexible |
| `/api/portfolio` | 30 min | Cache::flexible |

Cache is invalidated on model `saved` / `deleted` events.

### N+1 Prevention
- Always use `with()` for relationships in controllers
- Enable `Model::preventLazyLoading()` in dev
- API Resources use `whenLoaded()` for optional relations

### Database Indexes
Every `slug`, `is_active`, `status`, `published_at`, `sort_order`, and `type` column is indexed. See `docs/SCHEMA.md`.
