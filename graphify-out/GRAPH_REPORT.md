# Graph Report - brava-cms  (2026-08-02)

## Corpus Check
- 303 files · ~63,609 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1342 nodes · 2176 edges · 194 communities (174 shown, 20 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 59 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `630a1cdf`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- PortfolioItem
- Endpoints
- composer.json
- Illuminate\Database\Eloquent\Factories\Factory
- Illuminate\Http\Resources\Json\JsonResource
- Promo
- AI_CONTEXT.md
- UpdateServiceRequest
- scripts
- Laravel Boost Guidelines
- User.php
- devDependencies
- User
- Illuminate\Database\Seeder
- Illuminate\Http\RedirectResponse
- Controller
- Brava CMS — Dashboard Date Filter
- cache.php
- MediaService
- Illuminate\Http\JsonResponse
- Pest Testing 4
- Illuminate\Foundation\Http\FormRequest
- Blog
- Illuminate\Http\Request
- Tailwind CSS Development
- require-dev
- ContactController.php
- Setting
- ValidatesImageUrl.php
- command
- setup
- Brava CMS - Panduan Sistem Promo & Penawaran Spesial (Special Offers)
- Database Schema
- AnalyticsService
- Architecture Best Practices
- Queue & Job Best Practices
- Security Best Practices
- DashboardController.php
- Illuminate\Support\Collection
- FaqService
- TestimonialService
- Brava CMS — Google Analytics Dashboard
- Advanced Query Patterns
- Database Performance Best Practices
- Events & Notifications Best Practices
- Caching Best Practices
- Eloquent Best Practices
- Migration Best Practices
- CacheApiHeaders.php
- laravel-best-practices/SKILL.md
- Blade & Views Best Practices
- Error Handling Best Practices
- Task Scheduling Best Practices
- Testing Best Practices
- LoginRequest
- Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)
- Collection Best Practices
- HTTP Client Best Practices
- Mail Best Practices
- Routing & Controllers Best Practices
- Conventions & Style
- Validation & Forms Best Practices
- HtmlSanitizer.php
- Brava CMS
- Configuration Best Practices
- AppServiceProvider
- config
- Favicon Asset Set
- Blog.php
- .build
- Admin/PromoController.php
- StoreTeamRequest
- require
- PromoController
- README.md
- UpdateTeamRequest
- Pest.php
- Media
- Admin/PortfolioController.php
- psr-4
- ApiController
- UpdateCategoryRequest
- post-create-project-cmd
- UpdateTestimonialRequest
- API Reference
- .opencode/opencode.json
- profile/edit.blade.php
- graphify.js
- keywords
- categories/create.blade.php
- categories/edit.blade.php

## God Nodes (most connected - your core abstractions)
1. `Controller` - 42 edges
2. `User` - 41 edges
3. `Promo` - 30 edges
4. `Blog` - 29 edges
5. `PortfolioItem` - 29 edges
6. `Category` - 28 edges
7. `TeamMember` - 28 edges
8. `Service` - 27 edges
9. `Media` - 21 edges
10. `Faq` - 20 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `TeamMember`  [INFERRED]
  database/migrations/2026_07_30_100000_ensure_admin_users_have_team_members.php → app/Models/TeamMember.php
- `up()` --calls--> `User`  [INFERRED]
  database/migrations/2026_07_30_100000_ensure_admin_users_have_team_members.php → app/Models/User.php
- `Apple Touch Icon (PNG)` --references--> `Favicon Asset Set`  [INFERRED]
  public/apple-touch-icon.png → public/favicon.svg
- `Favicon 96x96 (PNG)` --references--> `Favicon Asset Set`  [INFERRED]
  public/favicon-96x96.png → public/favicon.svg
- `Web App Manifest Icon 192x192` --references--> `Favicon Asset Set`  [INFERRED]
  public/web-app-manifest-192x192.png → public/favicon.svg

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Favicon Asset Set (RealFaviconGenerator output)** — public_apple_touch_icon_apple_touch_icon, public_favicon_96x96_favicon_96, public_favicon_svg_favicon, public_web_app_manifest_192x192_manifest_icon_192, public_web_app_manifest_512x512_manifest_icon_512 [INFERRED 0.85]

## Communities (194 total, 20 thin omitted)

### Community 0 - "PortfolioItem"
Cohesion: 0.07
Nodes (8): PortfolioController, ServiceController, PortfolioController, ServiceController, PortfolioItem, Service, PortfolioService, ServiceService

### Community 1 - "Endpoints"
Cohesion: 0.04
Nodes (46): Base URL, Blogs, Brava CMS — API Specification, Caching Strategy (Laravel), Categories, Contact Form, Contact Form Protection, CORS (+38 more)

### Community 2 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, extra, laravel, dont-discover, license, minimum-stability (+5 more)

### Community 3 - "Illuminate\Database\Eloquent\Factories\Factory"
Cohesion: 0.06
Nodes (16): BlogFactory, static, CategoryFactory, FaqFactory, MediaFactory, PortfolioItemFactory, static, PromoFactory (+8 more)

### Community 4 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.12
Nodes (8): BlogListResource, BlogResource, CategoryResource, MediaResource, PortfolioResource, PromoResource, SettingResource, Illuminate\Http\Resources\Json\JsonResource

### Community 5 - "Promo"
Cohesion: 0.12
Nodes (5): PromoController, Promo, PromoService, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Database\Eloquent\Builder

### Community 6 - "AI_CONTEXT.md"
Cohesion: 0.07
Nodes (29): Admin, API, API Response Format, Architecture, Authentication, Authorization, Brava CMS - AI Context, Code Generation Rules (+21 more)

### Community 8 - "scripts"
Cohesion: 0.13
Nodes (15): scripts, dev, post-autoload-dump, post-update-cmd, pre-package-uninstall, test, bunx concurrently -c \"#93c5fd,#c4b5fd,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"bun run dev\" --names='server,queue,vite, Composer\\Config::disableProcessTimeout (+7 more)

### Community 9 - "Laravel Boost Guidelines"
Cohesion: 0.08
Nodes (25): APIs & Eloquent Resources, Application Structure & Architecture, Artisan, Conventions, Deployment, Do Things the Laravel Way, Documentation Files, Foundational Context (+17 more)

### Community 10 - "User.php"
Cohesion: 0.13
Nodes (3): DatabaseSeeder, TeamSeeder, Illuminate\Database\Eloquent\Relations\HasMany

### Community 11 - "devDependencies"
Cohesion: 0.08
Nodes (24): alpinejs, chart.js, concurrently, laravel-vite-plugin, dependencies, chart.js, tinymce, devDependencies (+16 more)

### Community 12 - "User"
Cohesion: 0.06
Nodes (23): User, BlogPolicy, CategoryPolicy, before(), create(), delete(), forceDelete(), restore() (+15 more)

### Community 13 - "Illuminate\Database\Seeder"
Cohesion: 0.11
Nodes (9): CategorySeeder, ContactSettingSeeder, FaqSeeder, PortfolioSeeder, PromoSeeder, ServiceSeeder, SettingSeeder, TestimonialSeeder (+1 more)

### Community 14 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.05
Nodes (14): BlogController, CategoryController, FaqController, TeamController, TestimonialController, ProfileController, Category, Faq (+6 more)

### Community 15 - "Controller"
Cohesion: 0.12
Nodes (11): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, PasswordController, VerifyEmailController, App\Http\Controllers\Controller, Controller (+3 more)

### Community 16 - "Brava CMS — Dashboard Date Filter"
Cohesion: 0.15
Nodes (12): Alur Data Saat Ini, Bonus (roadmap `docs/ANALYTICS.md` Phase 2), Brava CMS — Dashboard Date Filter, File & Perubahan, File & Perubahan, Kenapa Ini Gratis / Bukan Paywall, Overview, Phase 1 — Preset (7H / 30H / 90H / 1Y) (+4 more)

### Community 17 - "cache.php"
Cohesion: 0.41
Nodes (3): Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model, Illuminate\Database\Eloquent\SoftDeletes

### Community 18 - "MediaService"
Cohesion: 0.38
Nodes (3): UploadController, MediaService, Illuminate\Http\UploadedFile

### Community 19 - "Illuminate\Http\JsonResponse"
Cohesion: 0.36
Nodes (6): BlogController, error(), notFound(), paginatedSuccess(), success(), Illuminate\Http\JsonResponse

### Community 20 - "Pest Testing 4"
Cohesion: 0.11
Nodes (17): Architecture Testing, Assertions, Basic Test Structure, Basic Usage, Browser Test Example, Common Pitfalls, Creating Tests, Datasets (+9 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.14
Nodes (5): StoreCategoryRequest, StoreFaqRequest, UpdateBlogRequest, UpdateFaqRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "Blog"
Cohesion: 0.14
Nodes (3): Blog, BlogService, Illuminate\Database\Eloquent\Relations\BelongsToMany

### Community 23 - "Illuminate\Http\Request"
Cohesion: 0.15
Nodes (5): SettingController, TrashController, PortfolioListResource, ServiceListResource, Illuminate\Http\Request

### Community 24 - "Tailwind CSS Development"
Cohesion: 0.14
Nodes (13): Basic Usage, Common Patterns, Common Pitfalls, CSS-First Configuration, Dark Mode, Documentation, Flexbox Layout, Grid Layout (+5 more)

### Community 25 - "require-dev"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, laravel/boost, laravel/breeze, laravel/pail, laravel/pao, laravel/pint, mockery/mockery (+3 more)

### Community 27 - "Setting"
Cohesion: 0.25
Nodes (3): SettingController, Setting, SettingService

### Community 28 - "ValidatesImageUrl.php"
Cohesion: 0.15
Nodes (4): StoreBlogRequest, StoreServiceRequest, StoreTestimonialRequest, ProfileUpdateRequest

### Community 29 - "command"
Cohesion: 0.14
Nodes (13): enabled, type, url, command, enabled, type, mcp, context7 (+5 more)

### Community 30 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, bun install --ignore-scripts, bun run build, composer install, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 31 - "Brava CMS - Panduan Sistem Promo & Penawaran Spesial (Special Offers)"
Cohesion: 0.15
Nodes (12): 1. Ikhtisar Sistem, 2. Struktur Database (`promos`), 3. Logika & Aturan Bisnis (Edge Cases & Safety Analysis), 4. Spesifikasi API Endpoints (Next.js Compro), 5. Panduan Penggunaan Admin CMS, A. Aturan "Single Highlight Guarantee" (Hanya 1 Highlight), A. `GET /api/promos/highlight`, B. Auto-Expired & Auto-Fallback Logic (+4 more)

### Community 32 - "Database Schema"
Cohesion: 0.15
Nodes (13): Database Schema, Table: `blog_category` (pivot), Table: `blogs`, Table: `categories`, Table: `faqs`, Table: `media`, Table: `portfolio_items`, Table: `promos` (+5 more)

### Community 34 - "Architecture Best Practices"
Cohesion: 0.18
Nodes (11): Architecture Best Practices, Code to Interfaces, Convention Over Configuration, Default Sort by Descending, Single-Purpose Action Classes, Use Atomic Locks for Race Conditions, Use `Concurrency::run()` for Parallel Execution, Use `Context` for Request-Scoped Data (+3 more)

### Community 35 - "Queue & Job Best Practices"
Cohesion: 0.18
Nodes (10): Always Implement `failed()`, Batch Related Jobs, Implement `ShouldBeUnique`, Queue & Job Best Practices, Rate Limit External API Calls in Jobs, `retryUntil()` Needs `$tries = 0`, Set `retry_after` Greater Than `timeout`, Use Exponential Backoff (+2 more)

### Community 36 - "Security Best Practices"
Cohesion: 0.17
Nodes (11): Audit Dependencies, Authorize Every Action, CSRF Protection, Encrypt Sensitive Database Fields, Escape Output to Prevent XSS, Keep Secrets Out of Code, Mass Assignment Protection, Prevent SQL Injection (+3 more)

### Community 38 - "Illuminate\Support\Collection"
Cohesion: 0.24
Nodes (3): CategoryController, CategoryService, Illuminate\Support\Collection

### Community 39 - "FaqService"
Cohesion: 0.24
Nodes (3): FaqController, FaqResource, FaqService

### Community 40 - "TestimonialService"
Cohesion: 0.24
Nodes (3): TestimonialController, TestimonialResource, TestimonialService

### Community 41 - "Brava CMS — Google Analytics Dashboard"
Cohesion: 0.15
Nodes (13): Baris 1 — CMS Stats, Baris 2 — Analytics Stat Cards, Baris 3 — Grafik, Baris 4 — Detail, Baris 5 — Insight, Brava CMS — Google Analytics Dashboard, Caching, Cara Aktivasi (+5 more)

### Community 42 - "Advanced Query Patterns"
Cohesion: 0.20
Nodes (9): Advanced Query Patterns, Create Dynamic Relationships via Subquery FK, Prefer `whereIn` + Subquery Over `whereHas`, Sometimes Two Simple Queries Beat One Complex Query, Use `addSelect()` Subqueries for Single Values from Has-Many, Use Compound Indexes Matching `orderBy` Column Order, Use Conditional Aggregates Instead of Multiple Count Queries, Use Correlated Subqueries for Has-Many Ordering (+1 more)

### Community 43 - "Database Performance Best Practices"
Cohesion: 0.20
Nodes (9): Add Database Indexes, Always Eager Load Relationships, Chunk Large Datasets, Database Performance Best Practices, No Queries in Blade Templates, Prevent Lazy Loading in Development, Select Only Needed Columns, Use `cursor()` for Memory-Efficient Iteration (+1 more)

### Community 44 - "Events & Notifications Best Practices"
Cohesion: 0.20
Nodes (9): Always Queue Notifications, Events & Notifications Best Practices, Implement `HasLocalePreference` on Notifiable Models, Rely on Event Discovery, Route Notification Channels to Dedicated Queues, Run `event:cache` in Production Deploy, Use `afterCommit()` on Notifications in Transactions, Use On-Demand Notifications for Non-User Recipients (+1 more)

### Community 45 - "Caching Best Practices"
Cohesion: 0.22
Nodes (8): Caching Best Practices, Configure Failover Cache Stores in Production, Use `Cache::add()` for Atomic Conditional Writes, Use `Cache::flexible()` for Stale-While-Revalidate, Use `Cache::memo()` to Avoid Redundant Hits Within a Request, Use `Cache::remember()` Instead of Manual Get/Put, Use Cache Tags to Invalidate Related Groups, Use `once()` for Per-Request Memoization

### Community 46 - "Eloquent Best Practices"
Cohesion: 0.22
Nodes (8): Apply Global Scopes Sparingly, Avoid Hardcoded Table Names in Queries, Cast Date Columns Properly, Define Attribute Casts, Eloquent Best Practices, Use Correct Relationship Types, Use Local Scopes for Reusable Queries, Use `whereBelongsTo()` for Relationship Queries

### Community 47 - "Migration Best Practices"
Cohesion: 0.22
Nodes (8): Add Indexes in the Migration, Generate Migrations with Artisan, Keep Migrations Focused, Migration Best Practices, Mirror Defaults in Model `$attributes`, Never Modify Deployed Migrations, Use `constrained()` for Foreign Keys, Write Reversible `down()` Methods by Default

### Community 48 - "CacheApiHeaders.php"
Cohesion: 0.39
Nodes (4): CacheApiHeaders, NoRobots, Closure, Symfony\Component\HttpFoundation\Response

### Community 49 - "laravel-best-practices/SKILL.md"
Cohesion: 0.29
Nodes (5): Consistency First, Decision Rules, How to Apply, Laravel Best Practices, Rule Index

### Community 50 - "Blade & Views Best Practices"
Cohesion: 0.25
Nodes (7): Blade & Views Best Practices, Prefer Blade Components Over `@include`, Use `$attributes->merge()` in Component Templates, Use `@aware` for Deeply Nested Component Props, Use Blade Fragments for Partial Re-Renders (htmx/Turbo), Use `@pushOnce` for Per-Component Scripts, Use View Composers for Shared View Data

### Community 51 - "Error Handling Best Practices"
Cohesion: 0.25
Nodes (7): Add Context to Exception Classes, Enable `dontReportDuplicates()`, Error Handling Best Practices, Exception Reporting and Rendering, Force JSON Error Rendering for API Routes, Throttle High-Volume Exceptions, Use `ShouldntReport` for Exceptions That Should Never Log

### Community 52 - "Task Scheduling Best Practices"
Cohesion: 0.25
Nodes (7): Task Scheduling Best Practices, Use `environments()` to Restrict Tasks, Use `onOneServer()` on Multi-Server Deployments, Use `runInBackground()` for Concurrent Long Tasks, Use Schedule Groups for Shared Configuration, Use `takeUntilTimeout()` for Time-Bounded Processing, Use `withoutOverlapping()` on Variable-Duration Tasks

### Community 53 - "Testing Best Practices"
Cohesion: 0.25
Nodes (7): Call `Event::fake()` After Factory Setup, Testing Best Practices, Use `Exceptions::fake()` to Assert Exception Reporting, Use Factory States and Sequences, Use `LazilyRefreshDatabase` Over `RefreshDatabase`, Use Model Assertions Over Raw Database Assertions, Use `recycle()` to Share Relationship Instances Across Factories

### Community 55 - "Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)"
Cohesion: 0.15
Nodes (13): **1. Bagaimana cara menguji apakah dasbor sudah terkoneksi dengan GA4 asli?**, **2. Mengapa grafik saya masih nol (0) setelah dipasang?**, **3. Apa yang terjadi jika saya mengosongkan `GA4_PROPERTY_ID` di `.env`?**, **4. Apakah kuota API Google aman dan tidak akan kena limit?**, Daftar Isi, Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID, Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud, Langkah 3: Membuat Service Account & Mengunduh File Key JSON (+5 more)

### Community 56 - "Collection Best Practices"
Cohesion: 0.29
Nodes (6): Choose `cursor()` vs. `lazy()` Correctly, Collection Best Practices, Use `#[CollectedBy]` for Custom Collection Classes, Use Higher-Order Messages for Simple Operations, Use `lazyById()` When Updating Records While Iterating, Use `toQuery()` for Bulk Operations on Collections

### Community 57 - "HTTP Client Best Practices"
Cohesion: 0.29
Nodes (6): Always Set Explicit Timeouts, Fake HTTP Calls in Tests, Handle Errors Explicitly, HTTP Client Best Practices, Use Request Pooling for Concurrent Requests, Use Retry with Backoff for External APIs

### Community 58 - "Mail Best Practices"
Cohesion: 0.29
Nodes (6): Implement `ShouldQueue` on the Mailable Class, Mail Best Practices, Separate Content Tests from Sending Tests, Use `afterCommit()` on Mailables Inside Transactions, Use `assertQueued()` Not `assertSent()` for Queued Mailables, Use Markdown Mailables for Transactional Emails

### Community 59 - "Routing & Controllers Best Practices"
Cohesion: 0.29
Nodes (6): Keep Controllers Thin, Routing & Controllers Best Practices, Type-Hint Form Requests, Use Implicit Route Model Binding, Use Resource Controllers, Use Scoped Bindings for Nested Resources

### Community 60 - "Conventions & Style"
Cohesion: 0.29
Nodes (6): Conventions & Style, Follow Laravel Naming Conventions, No Inline JS/CSS in Blade, No Unnecessary Comments, Prefer Shorter Readable Syntax, Use Laravel String & Array Helpers

### Community 61 - "Validation & Forms Best Practices"
Cohesion: 0.29
Nodes (6): Always Use `validated()`, Array vs. String Notation for Rules, Use Form Request Classes, Use `Rule::when()` for Conditional Validation, Use the `after()` Method for Custom Validation, Validation & Forms Best Practices

### Community 62 - "HtmlSanitizer.php"
Cohesion: 0.48
Nodes (3): HtmlSanitizer, DOMElement, DOMNode

### Community 64 - "Brava CMS"
Cohesion: 0.29
Nodes (7): Brava CMS, Documentation Roadmap, Getting Started, Key Features & Architecture, License, Tech Stack, Testing & Code Quality

### Community 65 - "Configuration Best Practices"
Cohesion: 0.33
Nodes (5): Configuration Best Practices, `env()` Only in Config Files, Use `App::environment()` for Environment Checks, Use Constants and Language Files, Use Encrypted Env or External Secrets

### Community 67 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 68 - "Favicon Asset Set"
Cohesion: 0.47
Nodes (6): Apple Touch Icon (PNG), Favicon 96x96 (PNG), Favicon Asset Set, Favicon (SVG, embeds PNG), Web App Manifest Icon 192x192, Web App Manifest Icon 512x512

### Community 69 - "Blog.php"
Cohesion: 0.16
Nodes (3): BlogSeeder, Illuminate\Database\Eloquent\Relations\BelongsTo, Illuminate\Database\Eloquent\Relations\MorphMany

### Community 73 - "require"
Cohesion: 0.33
Nodes (6): require, google/analytics-data, intervention/image-laravel, laravel/framework, laravel/tinker, php

### Community 78 - "Media"
Cohesion: 0.12
Nodes (5): MediaController, StoreMediaRequest, UpdateMediaRequest, Media, Illuminate\Database\Eloquent\Relations\MorphTo

### Community 80 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 83 - "post-create-project-cmd"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 85 - "API Reference"
Cohesion: 0.50
Nodes (4): API Reference, Auth, Dimensi & Metrik yang Dipakai, Package

### Community 86 - ".opencode/opencode.json"
Cohesion: 0.50
Nodes (3): plugin, $schema, .opencode/plugins/graphify.js

### Community 87 - "profile/edit.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 108 - "keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **380 isolated node(s):** `Kenapa Ini Gratis / Bukan Paywall`, `Alur Data Saat Ini`, `File & Perubahan`, `Risiko & Catatan`, `File & Perubahan` (+375 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **20 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `Illuminate\Database\Eloquent\Factories\Factory`, `Blog.php`, `User.php`, `Illuminate\Http\RedirectResponse`, `cache.php`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Why does `Controller` connect `Controller` to `PortfolioItem`, `Promo`, `DashboardController.php`, `Admin/PromoController.php`, `Media`, `Illuminate\Http\RedirectResponse`, `Admin/PortfolioController.php`, `cache.php`, `MediaService`, `ApiController`, `Illuminate\Http\Request`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Why does `TeamMember` connect `Illuminate\Http\RedirectResponse` to `cache.php`, `User.php`, `User`, `Blog.php`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 7 inferred relationships involving `User` (e.g. with `.store()` and `.definition()`) actually correct?**
  _`User` has 7 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Kenapa Ini Gratis / Bukan Paywall`, `Alur Data Saat Ini`, `File & Perubahan` to the rest of the system?**
  _380 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `PortfolioItem` be split into smaller, more focused modules?**
  _Cohesion score 0.07373737373737374 - nodes in this community are weakly interconnected._
- **Should `Endpoints` be split into smaller, more focused modules?**
  _Cohesion score 0.0425531914893617 - nodes in this community are weakly interconnected._