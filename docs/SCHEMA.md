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
| role | string(255) | enum: super_admin, admin; default: admin |
| avatar | string(255) | nullable |
| timestamps | | |
| softDeletes | | |

## Table: `settings`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| key | string(255) | unique |
| value | text | nullable |
| group | string(50) | general, seo, social, contact |
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
| name | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| is_active | boolean | default true |
| sort_order | integer | default 0 |
| timestamps | | |
| softDeletes | | |

## Table: `services`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| content | longText | nullable |
| photo | string(255) | nullable |
| is_active | boolean | default true |
| published_at | timestamp | nullable |
| timestamps | | |
| softDeletes | | |

## Table: `blogs`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| author_id | bigInteger | FK to users.id |
| title | string(255) | |
| slug | string(255) | unique |
| excerpt | text | nullable |
| content | longText | nullable |
| featured_image | string(255) | nullable |
| published_at | timestamp | nullable |
| is_featured | boolean | default false |
| status | string(255) | enum: draft, published, archived; default draft |
| meta_title | string(70) | nullable |
| meta_description | string(160) | nullable |
| meta_keywords | string(255) | nullable |
| og_title | string(70) | nullable |
| og_description | string(160) | nullable |
| og_image | string(255) | nullable |
| canonical_url | string(255) | nullable |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default Article |
| timestamps | | |
| softDeletes | | |

## Table: `portfolio_items`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| service_id | bigInteger | FK to services.id, nullable |
| title | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| content | longText | nullable |
| client | string(255) | nullable |
| project_url | string(255) | nullable |
| photo | string(255) | Cover photo, required |
| photo_alt | string(255) | nullable |
| completed_at | date | nullable |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| meta_title | string(70) | nullable |
| meta_description | string(160) | nullable |
| og_image | string(255) | nullable |
| og_image_alt | string(255) | nullable |
| robots_index | boolean | default true |
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
| client_name | string(255) | Holds Company/Organization name |
| content | text | |
| rating | tinyInteger | 1-5, nullable |
| avatar | string(255) | nullable |
| avatar_alt | string(255) | nullable |
| is_active | boolean | default true |
| sort_order | integer | default 0 |
| timestamps | | |
| softDeletes | | |

## Table: `faqs`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| question | string(255) | |
| answer | text | |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |

## Table: `team_members`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| user_id | bigInteger | FK to users.id, nullable |
| name | string(255) | |
| position | string(255) | nullable |
| avatar | string(255) | nullable |
| email | string(255) | nullable, unique |
| phone | string(50) | nullable |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |

## Table: `promos`
| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | Promo title (e.g., 40% Diskon Seragam Perusahaan) |
| slug | string(255) | Unique URL slug |
| badge_text | string(100) | nullable (e.g., PROMO TERBATAS) |
| discount_info | string(100) | nullable (e.g., 40%, Rp 500.000) |
| description | text | nullable |
| image | string(500) | nullable |
| image_alt | string(255) | nullable |
| valid_from | dateTime | nullable |
| valid_until | dateTime | nullable |
| wa_template | text | nullable (Custom WA message template) |
| is_highlighted | boolean | default false (Only max 1 true) |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |
