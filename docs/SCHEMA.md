# Brava CMS — Database Schema

## Naming Conventions
- Table names: plural snake_case (`products`, `blog_categories`)
- Pivot tables: singular alphabetical (`blog_category`, `portfolio_category`)
- FK columns: `{module}_id` (`category_id`, `blog_id`)
- Timestamps: always `created_at`, `updated_at`
- Soft deletes: always `deleted_at` where applicable

---

## users (extends default Laravel migration)

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | |
| email | string(255) | unique |
| email_verified_at | timestamp | nullable |
| password | string(255) | hashed |
| role | enum('super_admin','editor') | default 'editor' |
| avatar | string(255) | nullable, relative path |
| remember_token | string(100) | nullable |
| timestamps | | |
| softDeletes | | |

---

## categories

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| type | string(50) | 'product','blog','portfolio' |
| is_active | boolean | default true |
| sort_order | integer | default 0 |
| timestamps | | |
| softDeletes | | |

Indexes: `slug` (unique), `type`, `is_active`, `sort_order`

---

## products

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| content | longText | nullable |
| price | decimal(15,2) | nullable |
| is_featured | boolean | default false |
| is_active | boolean | default true |
| published_at | timestamp | nullable |
| meta_title | string(70) | nullable |
| meta_description | string(160) | nullable |
| meta_keywords | string(255) | nullable |
| og_title | string(70) | nullable |
| og_description | string(160) | nullable |
| og_image | string(255) | nullable, relative path |
| canonical_url | string(255) | nullable |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default 'Product' |
| timestamps | | |
| softDeletes | | |

Indexes: `slug` (unique), `is_active`, `is_featured`, `published_at`

---

## blog_category (pivot)

| Column | Type | Notes |
|--------|------|-------|
| blog_id | bigInteger | FK → blogs.id, cascadeOnDelete |
| category_id | bigInteger | FK → categories.id, cascadeOnDelete |

Primary key: composite (`blog_id`, `category_id`)

---

## blogs

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| author_id | bigInteger | FK → users.id |
| title | string(255) | |
| slug | string(255) | unique |
| excerpt | text | nullable |
| content | longText | nullable |
| featured_image | string(255) | nullable, relative path |
| published_at | timestamp | nullable |
| is_featured | boolean | default false |
| status | enum('draft','published','archived') | default 'draft' |
| meta_title | string(70) | nullable |
| meta_description | string(160) | nullable |
| meta_keywords | string(255) | nullable |
| og_title | string(70) | nullable |
| og_description | string(160) | nullable |
| og_image | string(255) | nullable, relative path |
| canonical_url | string(255) | nullable |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default 'Article' |
| timestamps | | |
| softDeletes | | |

Indexes: `slug` (unique), `status`, `published_at`, `is_featured`, `author_id`

---

## portfolio_category (pivot)

| Column | Type | Notes |
|--------|------|-------|
| portfolio_item_id | bigInteger | FK → portfolio_items.id, cascadeOnDelete |
| category_id | bigInteger | FK → categories.id, cascadeOnDelete |

Primary key: composite (`portfolio_item_id`, `category_id`)

---

## portfolio_items

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| title | string(255) | |
| slug | string(255) | unique |
| description | text | nullable |
| content | longText | nullable |
| client | string(255) | nullable |
| project_url | string(255) | nullable |
| completed_at | date | nullable |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| meta_title | string(70) | nullable |
| meta_description | string(160) | nullable |
| meta_keywords | string(255) | nullable |
| og_title | string(70) | nullable |
| og_description | string(160) | nullable |
| og_image | string(255) | nullable, relative path |
| canonical_url | string(255) | nullable |
| robots_index | boolean | default true |
| robots_follow | boolean | default true |
| schema_type | string(50) | default 'CreativeWork' |
| timestamps | | |
| softDeletes | | |

Indexes: `slug` (unique), `is_active`, `sort_order`

---

## testimonials

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| client_name | string(255) | |
| client_position | string(255) | nullable |
| company | string(255) | nullable |
| content | text | |
| rating | tinyInteger | 1-5, nullable |
| avatar | string(255) | nullable, relative path |
| is_active | boolean | default true |
| sort_order | integer | default 0 |
| timestamps | | |
| softDeletes | | |

Indexes: `is_active`, `sort_order`

---

## faqs

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| question | string(255) | |
| answer | text | |
| category | string(255) | nullable, for grouping FAQs |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |

Indexes: `is_active`, `sort_order`, `category`

---

## team_members

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | |
| position | string(255) | nullable |
| bio | text | nullable |
| avatar | string(255) | nullable, relative path |
| email | string(255) | nullable |
| phone | string(50) | nullable |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| timestamps | | |
| softDeletes | | |

Indexes: `is_active`, `sort_order`

---

## media

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| name | string(255) | original file name |
| file_name | string(255) | hashed/stored file name |
| mime_type | string(127) | e.g. 'image/jpeg' |
| size | integer | bytes |
| disk | string(50) | default 'public' |
| path | string(255) | relative path only |
| alt_text | string(255) | nullable |
| sort_order | integer | default 0 |
| collection | string(50) | nullable 'gallery','featured','logo' |
| mediable_type | string(255) | nullable, morphs |
| mediable_id | bigInteger | nullable, morphs |
| created_at | timestamp | |
| updated_at | timestamp | |

Indexes: `mediable_type` + `mediable_id` (morphs), `collection`

---

## settings

| Column | Type | Notes |
|--------|------|-------|
| id | bigIncrements | |
| key | string(255) | unique |
| value | text | nullable |
| group | string(50) | 'general','seo','social','contact' |
| type | string(50) | 'text','textarea','image','color','boolean' |
| created_at | timestamp | |
| updated_at | timestamp | |

Indexes: `key` (unique), `group`

---

## Setting Keys

### general
| Key | Type | Default |
|-----|------|---------|
| site_name | text | '' |
| site_description | textarea | '' |
| logo | image | null |
| favicon | image | null |

### contact
| Key | Type | Default |
|-----|------|---------|
| address | textarea | '' |
| email | text | '' |
| phone | text | '' |
| whatsapp | text | '' |
| google_maps_url | text | '' |

### social
| Key | Type | Default |
|-----|------|---------|
| facebook_url | text | '' |
| instagram_url | text | '' |
| twitter_url | text | '' |
| youtube_url | text | '' |
| linkedin_url | text | '' |
| tiktok_url | text | '' |

### seo
| Key | Type | Default |
|-----|------|---------|
| default_meta_title | text | '' |
| default_meta_description | textarea | '' |
| default_og_image | image | null |
| google_analytics_id | text | '' |
| google_verification | text | '' |
| bing_verification | text | '' |
| organization_schema | textarea | '' |
| robots_txt | textarea | '' |
