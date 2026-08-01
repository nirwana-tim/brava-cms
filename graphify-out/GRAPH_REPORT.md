# Graph Report - .  (2026-08-01)

## Corpus Check
- 39 files · ~62,849 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1329 nodes · 2167 edges · 194 communities (168 shown, 26 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 63 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- User.php
- Illuminate\Http\Resources\Json\JsonResource
- Illuminate\Foundation\Http\FormRequest
- ContentAccessPolicy.php
- composer.json
- Illuminate\Database\Eloquent\Factories\Factory
- Promo
- Faq
- Endpoints
- scripts
- devDependencies
- Media
- SettingController
- Testimonial
- Controller
- TeamMember
- PortfolioItem
- Illuminate\Http\JsonResponse
- Service
- command
- Illuminate\View\View
- Category
- AnalyticsService
- AI_CONTEXT.md
- LoginRequest
- ValidatesImageUrl.php
- .build
- Illuminate\Http\Request
- Laravel Boost Guidelines
- Illuminate\Http\RedirectResponse
- AppServiceProvider
- Pest.php
- profile/edit.blade.php
- SCHEMA.md
- 0001_01_01_000001_create_cache_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_07_28_170000_create_settings_table.php
- 2026_07_28_170001_create_media_table.php
- 2026_07_28_170002_create_categories_table.php
- 2026_07_28_170003_create_services_table.php
- 2026_07_28_170004_create_blogs_table.php
- 2026_07_28_170005_create_blog_category_table.php
- 2026_07_28_170006_create_portfolio_items_table.php
- 2026_07_28_170007_create_category_portfolio_item_table.php
- 2026_07_28_170008_create_testimonials_table.php
- User
- 2026_07_28_170010_create_team_members_table.php
- 2026_07_28_180000_drop_category_from_faqs_table.php
- 2026_07_28_190000_drop_sort_order_and_project_url_from_portfolio_items_table.php
- 2026_07_28_190001_drop_sort_order_from_categories_table.php
- 2026_07_28_190002_drop_is_active_from_categories_table.php
- 2026_07_30_180750_create_promos_table.php
- 2026_07_31_000001_create_api_cache_table.php
- 2026_07_31_210629_add_seo_fields_to_portfolio_items_table.php
- graphify.js
- artisan
- categories/create.blade.php
- categories/edit.blade.php
- analytics.php
- config/app.php
- cors.php
- database.php
- filesystems.php
- logging.php
- mail.php
- queue.php
- services.php
- session.php
- index.php
- app.js
- blogs/create.blade.php
- blogs/edit.blade.php
- blogs/index.blade.php
- blogs/show.blade.php
- form.blade.php
- categories/index.blade.php
- dashboard.blade.php
- faqs/create.blade.php
- faqs/edit.blade.php
- faqs/index.blade.php
- faqs/show.blade.php
- media/create.blade.php
- media/edit.blade.php
- media/index.blade.php
- portfolio/create.blade.php
- portfolio/edit.blade.php
- portfolio/index.blade.php
- confirm-password.blade.php
- forgot-password.blade.php
- register.blade.php
- auth/reset-password.blade.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 44 edges
2. `User` - 42 edges
3. `Blog` - 30 edges
4. `Promo` - 30 edges
5. `Category` - 29 edges
6. `PortfolioItem` - 29 edges
7. `Service` - 28 edges
8. `TeamMember` - 28 edges
9. `Media` - 21 edges
10. `Faq` - 20 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `TeamMember`  [INFERRED]
  database/migrations/2026_07_30_100000_ensure_admin_users_have_team_members.php → app/Models/TeamMember.php
- `up()` --calls--> `User`  [INFERRED]
  database/migrations/2026_07_30_100000_ensure_admin_users_have_team_members.php → app/Models/User.php
- `before()` --references--> `User`  [EXTRACTED]
  app/Policies/Concerns/ContentAccessPolicy.php → app/Models/User.php
- `create()` --references--> `User`  [EXTRACTED]
  app/Policies/Concerns/ContentAccessPolicy.php → app/Models/User.php
- `delete()` --references--> `User`  [EXTRACTED]
  app/Policies/Concerns/ContentAccessPolicy.php → app/Models/User.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Favicon Asset Set (RealFaviconGenerator output)** — public_apple_touch_icon_apple_touch_icon, public_favicon_96x96_favicon_96, public_favicon_svg_favicon, public_web_app_manifest_192x192_manifest_icon_192, public_web_app_manifest_512x512_manifest_icon_512 [INFERRED 0.85]

## Communities (194 total, 26 thin omitted)

### Community 0 - "User.php"
Cohesion: 0.08
Nodes (9): MediaController, PortfolioController, UploadController, Media, PortfolioItem, MediaService, PortfolioService, Illuminate\Database\Eloquent\Relations\MorphTo (+1 more)

### Community 1 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.04
Nodes (46): Base URL, Blogs, Brava CMS — API Specification, Caching Strategy (Laravel), Categories, Contact Form, Contact Form Protection, CORS (+38 more)

### Community 2 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.04
Nodes (45): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+37 more)

### Community 3 - "ContentAccessPolicy.php"
Cohesion: 0.06
Nodes (16): BlogFactory, static, CategoryFactory, FaqFactory, MediaFactory, PortfolioItemFactory, static, PromoFactory (+8 more)

### Community 4 - "composer.json"
Cohesion: 0.10
Nodes (11): PortfolioController, BlogListResource, BlogResource, CategoryResource, MediaResource, PortfolioListResource, PortfolioResource, PromoResource (+3 more)

### Community 5 - "Illuminate\Database\Eloquent\Factories\Factory"
Cohesion: 0.10
Nodes (5): PromoController, Promo, PromoService, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Database\Eloquent\Builder

### Community 6 - "Promo"
Cohesion: 0.07
Nodes (29): Admin, API, API Response Format, Architecture, Authentication, Authorization, Brava CMS - AI Context, Code Generation Rules (+21 more)

### Community 7 - "Faq"
Cohesion: 0.11
Nodes (6): ServiceController, ServiceController, StoreServiceRequest, UpdateServiceRequest, Service, ServiceService

### Community 8 - "Endpoints"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 9 - "scripts"
Cohesion: 0.08
Nodes (25): APIs & Eloquent Resources, Application Structure & Architecture, Artisan, Conventions, Deployment, Do Things the Laravel Way, Documentation Files, Foundational Context (+17 more)

### Community 11 - "Media"
Cohesion: 0.08
Nodes (24): alpinejs, chart.js, concurrently, laravel-vite-plugin, dependencies, chart.js, tinymce, devDependencies (+16 more)

### Community 12 - "SettingController"
Cohesion: 0.11
Nodes (16): BlogPolicy, CategoryPolicy, before(), create(), delete(), forceDelete(), restore(), update() (+8 more)

### Community 13 - "Testimonial"
Cohesion: 0.11
Nodes (9): CategorySeeder, ContactSettingSeeder, FaqSeeder, PortfolioSeeder, PromoSeeder, ServiceSeeder, SettingSeeder, TestimonialSeeder (+1 more)

### Community 14 - "Controller"
Cohesion: 0.13
Nodes (3): BlogController, CategoryController, Category

### Community 15 - "TeamMember"
Cohesion: 0.13
Nodes (9): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, PasswordController, VerifyEmailController, Controller, Illuminate\Foundation\Auth\Access\AuthorizesRequests, Illuminate\Foundation\Auth\EmailVerificationRequest (+1 more)

### Community 16 - "PortfolioItem"
Cohesion: 0.15
Nodes (5): User, TeamMemberPolicy, up(), Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 17 - "Illuminate\Http\JsonResponse"
Cohesion: 0.39
Nodes (3): Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\SoftDeletes

### Community 18 - "Service"
Cohesion: 0.16
Nodes (4): TeamController, TeamMember, DatabaseSeeder, TeamSeeder

### Community 19 - "command"
Cohesion: 0.20
Nodes (9): ApiController, BlogController, PromoController, error(), notFound(), paginatedSuccess(), success(), Illuminate\Http\JsonResponse (+1 more)

### Community 20 - "Illuminate\View\View"
Cohesion: 0.11
Nodes (17): Architecture Testing, Assertions, Basic Test Structure, Basic Usage, Browser Test Example, Common Pitfalls, Creating Tests, Datasets (+9 more)

### Community 21 - "Category"
Cohesion: 0.15
Nodes (5): StoreCategoryRequest, StoreFaqRequest, UpdateCategoryRequest, UpdateMediaRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "AnalyticsService"
Cohesion: 0.15
Nodes (3): Blog, BlogService, Illuminate\Database\Eloquent\Relations\MorphMany

### Community 23 - "AI_CONTEXT.md"
Cohesion: 0.17
Nodes (6): DashboardController, SettingController, EmailVerificationPromptController, GuestLayout, Illuminate\View\Component, Illuminate\View\View

### Community 24 - "LoginRequest"
Cohesion: 0.14
Nodes (13): Basic Usage, Common Patterns, Common Pitfalls, CSS-First Configuration, Dark Mode, Documentation, Flexbox Layout, Grid Layout (+5 more)

### Community 26 - ".build"
Cohesion: 0.19
Nodes (4): ContactController, SitemapController, ContactService, SitemapService

### Community 27 - "Illuminate\Http\Request"
Cohesion: 0.20
Nodes (4): SettingController, SettingResource, Setting, SettingService

### Community 28 - "Laravel Boost Guidelines"
Cohesion: 0.19
Nodes (3): StoreBlogRequest, UpdateTeamRequest, ProfileUpdateRequest

### Community 29 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.14
Nodes (13): enabled, type, url, command, enabled, type, mcp, context7 (+5 more)

### Community 31 - "Pest.php"
Cohesion: 0.15
Nodes (12): 1. Ikhtisar Sistem, 2. Struktur Database (`promos`), 3. Logika & Aturan Bisnis (Edge Cases & Safety Analysis), 4. Spesifikasi API Endpoints (Next.js Compro), 5. Panduan Penggunaan Admin CMS, A. Aturan "Single Highlight Guarantee" (Hanya 1 Highlight), A. `GET /api/promos/highlight`, B. Auto-Expired & Auto-Fallback Logic (+4 more)

### Community 32 - "profile/edit.blade.php"
Cohesion: 0.15
Nodes (13): Database Schema, Table: `blog_category` (pivot), Table: `blogs`, Table: `categories`, Table: `faqs`, Table: `media`, Table: `portfolio_items`, Table: `promos` (+5 more)

### Community 34 - "0001_01_01_000001_create_cache_table.php"
Cohesion: 0.18
Nodes (11): Architecture Best Practices, Code to Interfaces, Convention Over Configuration, Default Sort by Descending, Single-Purpose Action Classes, Use Atomic Locks for Race Conditions, Use `Concurrency::run()` for Parallel Execution, Use `Context` for Request-Scoped Data (+3 more)

### Community 35 - "0001_01_01_000002_create_jobs_table.php"
Cohesion: 0.18
Nodes (10): Always Implement `failed()`, Batch Related Jobs, Implement `ShouldBeUnique`, Queue & Job Best Practices, Rate Limit External API Calls in Jobs, `retryUntil()` Needs `$tries = 0`, Set `retry_after` Greater Than `timeout`, Use Exponential Backoff (+2 more)

### Community 36 - "2026_07_28_170000_create_settings_table.php"
Cohesion: 0.18
Nodes (11): Audit Dependencies, Authorize Every Action, CSRF Protection, Encrypt Sensitive Database Fields, Escape Output to Prevent XSS, Keep Secrets Out of Code, Mass Assignment Protection, Prevent SQL Injection (+3 more)

### Community 37 - "2026_07_28_170001_create_media_table.php"
Cohesion: 0.27
Nodes (3): TrashController, ProfileController, Illuminate\Http\RedirectResponse

### Community 38 - "2026_07_28_170002_create_categories_table.php"
Cohesion: 0.25
Nodes (3): CategoryController, CategoryService, Illuminate\Support\Collection

### Community 39 - "2026_07_28_170003_create_services_table.php"
Cohesion: 0.24
Nodes (3): FaqController, FaqResource, FaqService

### Community 40 - "2026_07_28_170004_create_blogs_table.php"
Cohesion: 0.24
Nodes (3): TestimonialController, TestimonialResource, TestimonialService

### Community 41 - "2026_07_28_170005_create_blog_category_table.php"
Cohesion: 0.18
Nodes (11): API Reference, Auth, Brava CMS — Google Analytics Dashboard, Caching, Cara Aktivasi, Dimensi & Metrik yang Dipakai, File Structure, Overview (+3 more)

### Community 42 - "2026_07_28_170006_create_portfolio_items_table.php"
Cohesion: 0.20
Nodes (9): Advanced Query Patterns, Create Dynamic Relationships via Subquery FK, Prefer `whereIn` + Subquery Over `whereHas`, Sometimes Two Simple Queries Beat One Complex Query, Use `addSelect()` Subqueries for Single Values from Has-Many, Use Compound Indexes Matching `orderBy` Column Order, Use Conditional Aggregates Instead of Multiple Count Queries, Use Correlated Subqueries for Has-Many Ordering (+1 more)

### Community 43 - "2026_07_28_170007_create_category_portfolio_item_table.php"
Cohesion: 0.20
Nodes (9): Add Database Indexes, Always Eager Load Relationships, Chunk Large Datasets, Database Performance Best Practices, No Queries in Blade Templates, Prevent Lazy Loading in Development, Select Only Needed Columns, Use `cursor()` for Memory-Efficient Iteration (+1 more)

### Community 44 - "2026_07_28_170008_create_testimonials_table.php"
Cohesion: 0.20
Nodes (9): Always Queue Notifications, Events & Notifications Best Practices, Implement `HasLocalePreference` on Notifiable Models, Rely on Event Discovery, Route Notification Channels to Dedicated Queues, Run `event:cache` in Production Deploy, Use `afterCommit()` on Notifications in Transactions, Use On-Demand Notifications for Non-User Recipients (+1 more)

### Community 45 - "User"
Cohesion: 0.22
Nodes (8): Caching Best Practices, Configure Failover Cache Stores in Production, Use `Cache::add()` for Atomic Conditional Writes, Use `Cache::flexible()` for Stale-While-Revalidate, Use `Cache::memo()` to Avoid Redundant Hits Within a Request, Use `Cache::remember()` Instead of Manual Get/Put, Use Cache Tags to Invalidate Related Groups, Use `once()` for Per-Request Memoization

### Community 46 - "2026_07_28_170010_create_team_members_table.php"
Cohesion: 0.22
Nodes (8): Apply Global Scopes Sparingly, Avoid Hardcoded Table Names in Queries, Cast Date Columns Properly, Define Attribute Casts, Eloquent Best Practices, Use Correct Relationship Types, Use Local Scopes for Reusable Queries, Use `whereBelongsTo()` for Relationship Queries

### Community 47 - "2026_07_28_180000_drop_category_from_faqs_table.php"
Cohesion: 0.22
Nodes (8): Add Indexes in the Migration, Generate Migrations with Artisan, Keep Migrations Focused, Migration Best Practices, Mirror Defaults in Model `$attributes`, Never Modify Deployed Migrations, Use `constrained()` for Foreign Keys, Write Reversible `down()` Methods by Default

### Community 48 - "2026_07_28_190000_drop_sort_order_and_project_url_from_portfolio_items_table.php"
Cohesion: 0.39
Nodes (4): CacheApiHeaders, NoRobots, Closure, Symfony\Component\HttpFoundation\Response

### Community 49 - "2026_07_28_190001_drop_sort_order_from_categories_table.php"
Cohesion: 0.25
Nodes (5): Consistency First, Decision Rules, How to Apply, Laravel Best Practices, Rule Index

### Community 50 - "2026_07_28_190002_drop_is_active_from_categories_table.php"
Cohesion: 0.25
Nodes (7): Blade & Views Best Practices, Prefer Blade Components Over `@include`, Use `$attributes->merge()` in Component Templates, Use `@aware` for Deeply Nested Component Props, Use Blade Fragments for Partial Re-Renders (htmx/Turbo), Use `@pushOnce` for Per-Component Scripts, Use View Composers for Shared View Data

### Community 51 - "2026_07_30_180750_create_promos_table.php"
Cohesion: 0.25
Nodes (7): Add Context to Exception Classes, Enable `dontReportDuplicates()`, Error Handling Best Practices, Exception Reporting and Rendering, Force JSON Error Rendering for API Routes, Throttle High-Volume Exceptions, Use `ShouldntReport` for Exceptions That Should Never Log

### Community 52 - "2026_07_31_000001_create_api_cache_table.php"
Cohesion: 0.25
Nodes (7): Task Scheduling Best Practices, Use `environments()` to Restrict Tasks, Use `onOneServer()` on Multi-Server Deployments, Use `runInBackground()` for Concurrent Long Tasks, Use Schedule Groups for Shared Configuration, Use `takeUntilTimeout()` for Time-Bounded Processing, Use `withoutOverlapping()` on Variable-Duration Tasks

### Community 53 - "2026_07_31_210629_add_seo_fields_to_portfolio_items_table.php"
Cohesion: 0.25
Nodes (7): Call `Event::fake()` After Factory Setup, Testing Best Practices, Use `Exceptions::fake()` to Assert Exception Reporting, Use Factory States and Sequences, Use `LazilyRefreshDatabase` Over `RefreshDatabase`, Use Model Assertions Over Raw Database Assertions, Use `recycle()` to Share Relationship Instances Across Factories

### Community 55 - "artisan"
Cohesion: 0.25
Nodes (8): Daftar Isi, Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID, Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud, Langkah 3: Membuat Service Account & Mengunduh File Key JSON, Langkah 4: Memberikan Akses Viewer kepada Service Account di GA4, Langkah 5: Memasang File JSON Key di Brava CMS, Langkah 6: Konfigurasi File `.env`, Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)

### Community 56 - "categories/create.blade.php"
Cohesion: 0.29
Nodes (6): Choose `cursor()` vs. `lazy()` Correctly, Collection Best Practices, Use `#[CollectedBy]` for Custom Collection Classes, Use Higher-Order Messages for Simple Operations, Use `lazyById()` When Updating Records While Iterating, Use `toQuery()` for Bulk Operations on Collections

### Community 57 - "categories/edit.blade.php"
Cohesion: 0.29
Nodes (6): Always Set Explicit Timeouts, Fake HTTP Calls in Tests, Handle Errors Explicitly, HTTP Client Best Practices, Use Request Pooling for Concurrent Requests, Use Retry with Backoff for External APIs

### Community 58 - "analytics.php"
Cohesion: 0.29
Nodes (6): Implement `ShouldQueue` on the Mailable Class, Mail Best Practices, Separate Content Tests from Sending Tests, Use `afterCommit()` on Mailables Inside Transactions, Use `assertQueued()` Not `assertSent()` for Queued Mailables, Use Markdown Mailables for Transactional Emails

### Community 59 - "config/app.php"
Cohesion: 0.29
Nodes (6): Keep Controllers Thin, Routing & Controllers Best Practices, Type-Hint Form Requests, Use Implicit Route Model Binding, Use Resource Controllers, Use Scoped Bindings for Nested Resources

### Community 60 - "cors.php"
Cohesion: 0.29
Nodes (6): Conventions & Style, Follow Laravel Naming Conventions, No Inline JS/CSS in Blade, No Unnecessary Comments, Prefer Shorter Readable Syntax, Use Laravel String & Array Helpers

### Community 61 - "database.php"
Cohesion: 0.29
Nodes (6): Always Use `validated()`, Array vs. String Notation for Rules, Use Form Request Classes, Use `Rule::when()` for Conditional Validation, Use the `after()` Method for Custom Validation, Validation & Forms Best Practices

### Community 62 - "filesystems.php"
Cohesion: 0.48
Nodes (3): HtmlSanitizer, DOMElement, DOMNode

### Community 64 - "logging.php"
Cohesion: 0.29
Nodes (7): Brava CMS, Documentation Roadmap, Getting Started, Key Features & Architecture, License, Tech Stack, Testing & Code Quality

### Community 65 - "mail.php"
Cohesion: 0.33
Nodes (5): Configuration Best Practices, `env()` Only in Config Files, Use `App::environment()` for Environment Checks, Use Constants and Language Files, Use Encrypted Env or External Secrets

### Community 67 - "services.php"
Cohesion: 0.33
Nodes (6): Baris 1 — CMS Stats, Baris 2 — Analytics Stat Cards, Baris 3 — Grafik, Baris 4 — Detail, Baris 5 — Insight, Yang Ada di Dashboard

### Community 68 - "session.php"
Cohesion: 0.47
Nodes (6): Apple Touch Icon (PNG), Favicon 96x96 (PNG), Favicon Asset Set, Favicon (SVG, embeds PNG), Web App Manifest Icon 192x192, Web App Manifest Icon 512x512

### Community 76 - "categories/index.blade.php"
Cohesion: 0.40
Nodes (5): **1. Bagaimana cara menguji apakah dasbor sudah terkoneksi dengan GA4 asli?**, **2. Mengapa grafik saya masih nol (0) setelah dipasang?**, **3. Apa yang terjadi jika saya mengosongkan `GA4_PROPERTY_ID` di `.env`?**, **4. Apakah kuota API Google aman dan tidak akan kena limit?**, Tanya Jawab & Troubleshooting

### Community 86 - "portfolio/edit.blade.php"
Cohesion: 0.50
Nodes (3): plugin, $schema, .opencode/plugins/graphify.js

### Community 87 - "portfolio/index.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

## Knowledge Gaps
- **372 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+367 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **26 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `PortfolioItem` to `ContentAccessPolicy.php`, `index.php`, `devDependencies`, `blogs/show.blade.php`, `forgot-password.blade.php`, `SettingController`, `Illuminate\Http\JsonResponse`, `Service`, `AI_CONTEXT.md`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Why does `Controller` connect `TeamMember` to `User.php`, `composer.json`, `Illuminate\Database\Eloquent\Factories\Factory`, `2026_07_28_170001_create_media_table.php`, `Faq`, `devDependencies`, `Controller`, `Illuminate\Http\JsonResponse`, `Service`, `command`, `AI_CONTEXT.md`, `ValidatesImageUrl.php`, `AppServiceProvider`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Blog` connect `AnalyticsService` to `SCHEMA.md`, `index.php`, `app.js`, `blogs/index.blade.php`, `Controller`, `Illuminate\Http\JsonResponse`, `portfolio/create.blade.php`, `AI_CONTEXT.md`, `.build`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 8 inferred relationships involving `User` (e.g. with `.index()` and `.store()`) actually correct?**
  _`User` has 8 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _372 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User.php` be split into smaller, more focused modules?**
  _Cohesion score 0.08048103607770583 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Http\Resources\Json\JsonResource` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._