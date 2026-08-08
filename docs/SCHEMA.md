# Database Schema

## Table: `users`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | |
| email | string(255) | unique |
| email_verified_at | timestamp | nullable |
| password | string(255) | |
| remember_token | string(100) | nullable |
| role | string(255) | enum: super_admin, admin, staff; default admin |
| position | string(255) | nullable |
| avatar | string(255) | nullable |
| is_active | boolean | default true, indexed |
| timestamps | | |

## Table: `settings`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| key | string(255) | unique |
| value | text | nullable, translatable keys stored as JSON `{"id": "...", "en": null}` |
| group | string(50) | general, seo, social, contact, system, adsense |
| type | string(50) | text, textarea, image, color, boolean |
| created_at | timestamp | |
| updated_at | timestamp | |

## Table: `media`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | original file name |
| file_name | string(255) | hashed/stored file name |
| mime_type | string(127) | e.g. image/jpeg |
| size | integer | bytes |
| disk | string(50) | default public |
| path | string(255) | relative path only |
| alt_text | string(255) | nullable |
| sort_order | integer | default 0 |
| collection | string(50) | nullable: portfolio, featured, logo |
| mediable_type | string(255) | nullable, morphs |
| mediable_id | bigInteger | nullable, morphs |
| created_at | timestamp | |
| updated_at | timestamp | |

## Table: `categories`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | translatable JSON `{"id","en"}` |
| slug | string(255) | translatable JSON `{"id","en"}`, unique enforced in app layer per-locale |
| type | string(255) | nullable: blog, portfolio, indexed |
| description | text | nullable, translatable JSON |
| timestamps | | |
| softDeletes | | |

## Table: `services`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | translatable JSON `{"id","en"}` |
| slug | string(255) | translatable JSON `{"id","en"}`, unique enforced in app layer per-locale |
| description | text | nullable, translatable JSON |
| photo | string(255) | nullable |
| photo_alt | string(255) | nullable, translatable JSON |
| is_active | boolean | default true, indexed |
| sort_order | integer | default 0 |
| timestamps | | |
| softDeletes | | |

## Table: `blogs`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| author_id | bigInteger | FK to users.id |
| title | string(255) | translatable JSON `{"id","en"}` |
| slug | string(255) | translatable JSON `{"id","en"}`, unique enforced in app layer per-locale |
| excerpt | text | nullable, translatable JSON |
| content | longText | nullable, translatable JSON |
| featured_image | string(255) | nullable |
| featured_image_alt | string(255) | nullable, translatable JSON |
| published_at | timestamp | nullable |
| is_featured | boolean | default false |
| status | string(255) | enum: draft, published, archived; default draft |
| meta_title | string(70) | nullable, translatable JSON |
| meta_description | string(160) | nullable, translatable JSON |
| meta_keywords | string(255) | nullable, translatable JSON |
| og_image | string(255) | nullable |
| og_image_alt | string(255) | nullable, translatable JSON |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default Article |
| timestamps | | |
| softDeletes | | |

> **Note:** `canonical_url`, `og_title`, and `og_description` are **computed at runtime** in `App\Http\Resources\*` (from `FRONTEND_URL` and fallbacks), not stored as columns.

## Table: `portfolio_items`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| service_id | bigInteger | FK to services.id, nullable |
| title | string(255) | translatable JSON `{"id","en"}` |
| slug | string(255) | translatable JSON `{"id","en"}`, unique enforced in app layer per-locale |
| description | text | nullable, translatable JSON |
| specifications | json | nullable — [{key, value}] |
| features | json | nullable — [string] |
| client | string(255) | nullable, translatable JSON |
| photo | string(255) | Cover photo, required |
| photo_alt | string(255) | nullable, translatable JSON |
| completed_at | date | nullable |
| is_active | boolean | default true |
| meta_title | string(70) | nullable, translatable JSON |
| meta_description | string(160) | nullable, translatable JSON |
| meta_keywords | string(255) | nullable, translatable JSON |
| og_image | string(255) | nullable |
| og_image_alt | string(255) | nullable, translatable JSON |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default CreativeWork |
| timestamps | | |
| softDeletes | | |

## Table: `blog_category` (pivot)
| Column | Type | Notes |
|--------|------|-------|
| blog_id | bigInteger | FK to blogs.id, cascadeOnDelete |
| category_id | bigInteger | FK to categories.id, cascadeOnDelete |

## Table: `testimonials`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| client_name | string(255) | translatable JSON `{"id","en"}`, holds Company/Organization name |
| content | text | translatable JSON `{"id","en"}` |
| rating | tinyInteger | 1-5, nullable |
| avatar | string(255) | nullable |
| avatar_alt | string(255) | nullable |
| is_active | boolean | default true, indexed |
| sort_order | integer | default 0, unique enforced in app layer |
| timestamps | | |
| softDeletes | | |

## Table: `faqs`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| question | string(255) | translatable JSON `{"id","en"}` |
| answer | text | translatable JSON `{"id","en"}` |
| sort_order | integer | default 0, unique enforced in app layer |
| is_active | boolean | default true, indexed |
| timestamps | | |
| softDeletes | | |

## Table: `team_members`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| user_id | bigInteger | FK to users.id, nullable |
| name | string(255) | translatable JSON `{"id","en"}` |
| position | string(255) | nullable, translatable JSON |
| avatar | string(255) | nullable |
| email | string(255) | nullable |
| phone | string(50) | nullable |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |

## Table: `promos`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | Promo title, translatable JSON `{"id","en"}` |
| slug | string(255) | translatable JSON `{"id","en"}`, unique enforced in app layer per-locale |
| badge_text | string(100) | nullable, translatable JSON (e.g., PROMO TERBATAS) |
| discount_info | string(100) | nullable, translatable JSON (e.g., 40%, Rp 500.000) |
| description | text | nullable, translatable JSON |
| image | string(500) | nullable |
| image_alt | string(255) | nullable, translatable JSON |
| valid_from | dateTime | nullable |
| valid_until | dateTime | nullable, indexed |
| wa_template | text | nullable (Custom WA message template), translatable JSON |
| is_highlighted | boolean | default false (Only max 1 true), indexed |
| is_active | boolean | default true, indexed |
| meta_title | string(70) | nullable, translatable JSON |
| meta_description | string(160) | nullable, translatable JSON |
| meta_keywords | string(255) | nullable, translatable JSON |
| timestamps | | |
| softDeletes | | |
