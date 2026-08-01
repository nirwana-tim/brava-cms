# Graph Report - brava-cms  (2026-08-01)

## Corpus Check
- 302 files · ~62,847 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1319 nodes · 2157 edges · 186 communities (165 shown, 21 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 57 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `851cbca5`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- User.php
- Illuminate\Http\Request
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
- Setting
- Testimonial
- Illuminate\Http\RedirectResponse
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
- PortfolioService
- Laravel Boost Guidelines
- ProfileController.php
- AppServiceProvider
- Pest.php
- profile/edit.blade.php
- User
- graphify.js
- categories/create.blade.php
- categories/edit.blade.php
- Blog
- Pest Testing 4
- Tailwind CSS Development
- Brava CMS - Panduan Sistem Promo & Penawaran Spesial (Special Offers)
- Database Schema
- Architecture Best Practices
- Illuminate\Support\Collection
- Queue & Job Best Practices
- Security Best Practices
- Admin/PromoController.php
- TestimonialService
- Brava CMS — Google Analytics Dashboard
- Advanced Query Patterns
- Database Performance Best Practices
- Events & Notifications Best Practices
- ServiceService
- Caching Best Practices
- Eloquent Best Practices
- Migration Best Practices
- CacheApiHeaders.php
- Blade & Views Best Practices
- Error Handling Best Practices
- Task Scheduling Best Practices
- Testing Best Practices
- Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)
- Collection Best Practices
- HTTP Client Best Practices
- Mail Best Practices
- Routing & Controllers Best Practices
- laravel-best-practices/SKILL.md
- Conventions & Style
- Validation & Forms Best Practices
- Brava CMS
- Configuration Best Practices
- Yang Ada di Dashboard
- UpdateBlogRequest
- StoreTeamRequest
- UpdateTeamRequest
- SettingPolicy
- README.md
- Tanya Jawab & Troubleshooting
- StoreBlogRequest
- StoreServiceRequest
- StoreTestimonialRequest
- UpdateServiceRequest
- .teamMember

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

## Communities (186 total, 21 thin omitted)

### Community 0 - "User.php"
Cohesion: 0.06
Nodes (18): HtmlSanitizer, BlogSeeder, CategorySeeder, ContactSettingSeeder, FaqSeeder, PromoSeeder, ServiceSeeder, SettingSeeder (+10 more)

### Community 1 - "Illuminate\Http\Request"
Cohesion: 0.16
Nodes (8): BlogListResource, BlogResource, CategoryResource, MediaResource, PortfolioResource, PromoResource, Illuminate\Http\Request, Illuminate\Http\Resources\Json\JsonResource

### Community 2 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.19
Nodes (4): StoreCategoryRequest, UpdateCategoryRequest, UpdateTestimonialRequest, Illuminate\Foundation\Http\FormRequest

### Community 3 - "ContentAccessPolicy.php"
Cohesion: 0.11
Nodes (16): BlogPolicy, CategoryPolicy, before(), create(), delete(), forceDelete(), restore(), update() (+8 more)

### Community 4 - "composer.json"
Cohesion: 0.04
Nodes (45): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+37 more)

### Community 5 - "Illuminate\Database\Eloquent\Factories\Factory"
Cohesion: 0.06
Nodes (16): BlogFactory, static, CategoryFactory, FaqFactory, MediaFactory, PortfolioItemFactory, static, PromoFactory (+8 more)

### Community 6 - "Promo"
Cohesion: 0.12
Nodes (5): PromoController, Promo, PromoService, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Database\Eloquent\Builder

### Community 7 - "Faq"
Cohesion: 0.09
Nodes (7): FaqController, FaqController, StoreFaqRequest, UpdateFaqRequest, FaqResource, Faq, FaqService

### Community 8 - "Endpoints"
Cohesion: 0.04
Nodes (46): Base URL, Blogs, Brava CMS — API Specification, Caching Strategy (Laravel), Categories, Contact Form, Contact Form Protection, CORS (+38 more)

### Community 9 - "scripts"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 10 - "devDependencies"
Cohesion: 0.08
Nodes (24): alpinejs, chart.js, concurrently, laravel-vite-plugin, dependencies, chart.js, tinymce, devDependencies (+16 more)

### Community 11 - "Media"
Cohesion: 0.10
Nodes (8): MediaController, UploadController, StoreMediaRequest, UpdateMediaRequest, Media, MediaService, Illuminate\Database\Eloquent\Relations\MorphTo, Illuminate\Http\UploadedFile

### Community 12 - "Setting"
Cohesion: 0.18
Nodes (4): SettingController, SettingResource, Setting, SettingService

### Community 14 - "Illuminate\Http\RedirectResponse"
Cohesion: 0.11
Nodes (12): TrashController, AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, PasswordController, VerifyEmailController, Controller (+4 more)

### Community 15 - "TeamMember"
Cohesion: 0.16
Nodes (4): TeamController, TeamMember, DatabaseSeeder, TeamSeeder

### Community 17 - "Illuminate\Http\JsonResponse"
Cohesion: 0.20
Nodes (9): ApiController, BlogController, PromoController, error(), notFound(), paginatedSuccess(), success(), Illuminate\Http\JsonResponse (+1 more)

### Community 18 - "Service"
Cohesion: 0.17
Nodes (4): DashboardController, ServiceController, Service, PortfolioSeeder

### Community 19 - "command"
Cohesion: 0.14
Nodes (13): enabled, type, url, command, enabled, type, mcp, context7 (+5 more)

### Community 20 - "Illuminate\View\View"
Cohesion: 0.24
Nodes (4): SettingController, GuestLayout, Illuminate\View\Component, Illuminate\View\View

### Community 21 - "Category"
Cohesion: 0.19
Nodes (3): CategoryController, Category, Illuminate\Database\Eloquent\Relations\BelongsToMany

### Community 23 - "AI_CONTEXT.md"
Cohesion: 0.07
Nodes (29): Admin, API, API Response Format, Architecture, Authentication, Authorization, Brava CMS - AI Context, Code Generation Rules (+21 more)

### Community 25 - "ValidatesImageUrl.php"
Cohesion: 0.19
Nodes (3): StorePortfolioRequest, UpdatePortfolioRequest, ProfileUpdateRequest

### Community 26 - ".build"
Cohesion: 0.19
Nodes (4): ContactController, SitemapController, ContactService, SitemapService

### Community 27 - "PortfolioService"
Cohesion: 0.19
Nodes (3): PortfolioController, PortfolioListResource, PortfolioService

### Community 28 - "Laravel Boost Guidelines"
Cohesion: 0.08
Nodes (25): APIs & Eloquent Resources, Application Structure & Architecture, Artisan, Conventions, Deployment, Do Things the Laravel Way, Documentation Files, Foundational Context (+17 more)

### Community 32 - "profile/edit.blade.php"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 45 - "User"
Cohesion: 0.15
Nodes (5): User, TeamMemberPolicy, up(), Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable

### Community 140 - "Blog"
Cohesion: 0.16
Nodes (3): BlogController, Blog, BlogService

### Community 141 - "Pest Testing 4"
Cohesion: 0.11
Nodes (17): Architecture Testing, Assertions, Basic Test Structure, Basic Usage, Browser Test Example, Common Pitfalls, Creating Tests, Datasets (+9 more)

### Community 142 - "Tailwind CSS Development"
Cohesion: 0.14
Nodes (13): Basic Usage, Common Patterns, Common Pitfalls, CSS-First Configuration, Dark Mode, Documentation, Flexbox Layout, Grid Layout (+5 more)

### Community 143 - "Brava CMS - Panduan Sistem Promo & Penawaran Spesial (Special Offers)"
Cohesion: 0.15
Nodes (12): 1. Ikhtisar Sistem, 2. Struktur Database (`promos`), 3. Logika & Aturan Bisnis (Edge Cases & Safety Analysis), 4. Spesifikasi API Endpoints (Next.js Compro), 5. Panduan Penggunaan Admin CMS, A. Aturan "Single Highlight Guarantee" (Hanya 1 Highlight), A. `GET /api/promos/highlight`, B. Auto-Expired & Auto-Fallback Logic (+4 more)

### Community 144 - "Database Schema"
Cohesion: 0.15
Nodes (13): Database Schema, Table: `blog_category` (pivot), Table: `blogs`, Table: `categories`, Table: `faqs`, Table: `media`, Table: `portfolio_items`, Table: `promos` (+5 more)

### Community 145 - "Architecture Best Practices"
Cohesion: 0.17
Nodes (11): Architecture Best Practices, Code to Interfaces, Convention Over Configuration, Default Sort by Descending, Single-Purpose Action Classes, Use Atomic Locks for Race Conditions, Use `Concurrency::run()` for Parallel Execution, Use `Context` for Request-Scoped Data (+3 more)

### Community 146 - "Illuminate\Support\Collection"
Cohesion: 0.23
Nodes (3): CategoryController, CategoryService, Illuminate\Support\Collection

### Community 147 - "Queue & Job Best Practices"
Cohesion: 0.18
Nodes (10): Always Implement `failed()`, Batch Related Jobs, Implement `ShouldBeUnique`, Queue & Job Best Practices, Rate Limit External API Calls in Jobs, `retryUntil()` Needs `$tries = 0`, Set `retry_after` Greater Than `timeout`, Use Exponential Backoff (+2 more)

### Community 148 - "Security Best Practices"
Cohesion: 0.18
Nodes (11): Audit Dependencies, Authorize Every Action, CSRF Protection, Encrypt Sensitive Database Fields, Escape Output to Prevent XSS, Keep Secrets Out of Code, Mass Assignment Protection, Prevent SQL Injection (+3 more)

### Community 150 - "TestimonialService"
Cohesion: 0.24
Nodes (3): TestimonialController, TestimonialResource, TestimonialService

### Community 151 - "Brava CMS — Google Analytics Dashboard"
Cohesion: 0.18
Nodes (11): API Reference, Auth, Brava CMS — Google Analytics Dashboard, Caching, Cara Aktivasi, Dimensi & Metrik yang Dipakai, File Structure, Overview (+3 more)

### Community 152 - "Advanced Query Patterns"
Cohesion: 0.20
Nodes (9): Advanced Query Patterns, Create Dynamic Relationships via Subquery FK, Prefer `whereIn` + Subquery Over `whereHas`, Sometimes Two Simple Queries Beat One Complex Query, Use `addSelect()` Subqueries for Single Values from Has-Many, Use Compound Indexes Matching `orderBy` Column Order, Use Conditional Aggregates Instead of Multiple Count Queries, Use Correlated Subqueries for Has-Many Ordering (+1 more)

### Community 153 - "Database Performance Best Practices"
Cohesion: 0.20
Nodes (9): Add Database Indexes, Always Eager Load Relationships, Chunk Large Datasets, Database Performance Best Practices, No Queries in Blade Templates, Prevent Lazy Loading in Development, Select Only Needed Columns, Use `cursor()` for Memory-Efficient Iteration (+1 more)

### Community 154 - "Events & Notifications Best Practices"
Cohesion: 0.20
Nodes (9): Always Queue Notifications, Events & Notifications Best Practices, Implement `HasLocalePreference` on Notifiable Models, Rely on Event Discovery, Route Notification Channels to Dedicated Queues, Run `event:cache` in Production Deploy, Use `afterCommit()` on Notifications in Transactions, Use On-Demand Notifications for Non-User Recipients (+1 more)

### Community 155 - "ServiceService"
Cohesion: 0.27
Nodes (3): ServiceController, ServiceListResource, ServiceService

### Community 156 - "Caching Best Practices"
Cohesion: 0.22
Nodes (8): Caching Best Practices, Configure Failover Cache Stores in Production, Use `Cache::add()` for Atomic Conditional Writes, Use `Cache::flexible()` for Stale-While-Revalidate, Use `Cache::memo()` to Avoid Redundant Hits Within a Request, Use `Cache::remember()` Instead of Manual Get/Put, Use Cache Tags to Invalidate Related Groups, Use `once()` for Per-Request Memoization

### Community 157 - "Eloquent Best Practices"
Cohesion: 0.22
Nodes (8): Apply Global Scopes Sparingly, Avoid Hardcoded Table Names in Queries, Cast Date Columns Properly, Define Attribute Casts, Eloquent Best Practices, Use Correct Relationship Types, Use Local Scopes for Reusable Queries, Use `whereBelongsTo()` for Relationship Queries

### Community 158 - "Migration Best Practices"
Cohesion: 0.22
Nodes (8): Add Indexes in the Migration, Generate Migrations with Artisan, Keep Migrations Focused, Migration Best Practices, Mirror Defaults in Model `$attributes`, Never Modify Deployed Migrations, Use `constrained()` for Foreign Keys, Write Reversible `down()` Methods by Default

### Community 159 - "CacheApiHeaders.php"
Cohesion: 0.39
Nodes (4): CacheApiHeaders, NoRobots, Closure, Symfony\Component\HttpFoundation\Response

### Community 160 - "Blade & Views Best Practices"
Cohesion: 0.25
Nodes (7): Blade & Views Best Practices, Prefer Blade Components Over `@include`, Use `$attributes->merge()` in Component Templates, Use `@aware` for Deeply Nested Component Props, Use Blade Fragments for Partial Re-Renders (htmx/Turbo), Use `@pushOnce` for Per-Component Scripts, Use View Composers for Shared View Data

### Community 161 - "Error Handling Best Practices"
Cohesion: 0.25
Nodes (7): Add Context to Exception Classes, Enable `dontReportDuplicates()`, Error Handling Best Practices, Exception Reporting and Rendering, Force JSON Error Rendering for API Routes, Throttle High-Volume Exceptions, Use `ShouldntReport` for Exceptions That Should Never Log

### Community 162 - "Task Scheduling Best Practices"
Cohesion: 0.25
Nodes (7): Task Scheduling Best Practices, Use `environments()` to Restrict Tasks, Use `onOneServer()` on Multi-Server Deployments, Use `runInBackground()` for Concurrent Long Tasks, Use Schedule Groups for Shared Configuration, Use `takeUntilTimeout()` for Time-Bounded Processing, Use `withoutOverlapping()` on Variable-Duration Tasks

### Community 163 - "Testing Best Practices"
Cohesion: 0.25
Nodes (7): Call `Event::fake()` After Factory Setup, Testing Best Practices, Use `Exceptions::fake()` to Assert Exception Reporting, Use Factory States and Sequences, Use `LazilyRefreshDatabase` Over `RefreshDatabase`, Use Model Assertions Over Raw Database Assertions, Use `recycle()` to Share Relationship Instances Across Factories

### Community 164 - "Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)"
Cohesion: 0.25
Nodes (8): Daftar Isi, Langkah 1: Membuat Properti GA4 & Mendapatkan Property ID, Langkah 2: Mengaktifkan Google Analytics Data API di Google Cloud, Langkah 3: Membuat Service Account & Mengunduh File Key JSON, Langkah 4: Memberikan Akses Viewer kepada Service Account di GA4, Langkah 5: Memasang File JSON Key di Brava CMS, Langkah 6: Konfigurasi File `.env`, Panduan Manual Aktivasi & Pengaturan Google Analytics 4 (GA4)

### Community 165 - "Collection Best Practices"
Cohesion: 0.29
Nodes (6): Choose `cursor()` vs. `lazy()` Correctly, Collection Best Practices, Use `#[CollectedBy]` for Custom Collection Classes, Use Higher-Order Messages for Simple Operations, Use `lazyById()` When Updating Records While Iterating, Use `toQuery()` for Bulk Operations on Collections

### Community 166 - "HTTP Client Best Practices"
Cohesion: 0.29
Nodes (6): Always Set Explicit Timeouts, Fake HTTP Calls in Tests, Handle Errors Explicitly, HTTP Client Best Practices, Use Request Pooling for Concurrent Requests, Use Retry with Backoff for External APIs

### Community 167 - "Mail Best Practices"
Cohesion: 0.29
Nodes (6): Implement `ShouldQueue` on the Mailable Class, Mail Best Practices, Separate Content Tests from Sending Tests, Use `afterCommit()` on Mailables Inside Transactions, Use `assertQueued()` Not `assertSent()` for Queued Mailables, Use Markdown Mailables for Transactional Emails

### Community 168 - "Routing & Controllers Best Practices"
Cohesion: 0.29
Nodes (6): Keep Controllers Thin, Routing & Controllers Best Practices, Type-Hint Form Requests, Use Implicit Route Model Binding, Use Resource Controllers, Use Scoped Bindings for Nested Resources

### Community 169 - "laravel-best-practices/SKILL.md"
Cohesion: 0.29
Nodes (5): Consistency First, Decision Rules, How to Apply, Laravel Best Practices, Rule Index

### Community 170 - "Conventions & Style"
Cohesion: 0.29
Nodes (6): Conventions & Style, Follow Laravel Naming Conventions, No Inline JS/CSS in Blade, No Unnecessary Comments, Prefer Shorter Readable Syntax, Use Laravel String & Array Helpers

### Community 171 - "Validation & Forms Best Practices"
Cohesion: 0.29
Nodes (6): Always Use `validated()`, Array vs. String Notation for Rules, Use Form Request Classes, Use `Rule::when()` for Conditional Validation, Use the `after()` Method for Custom Validation, Validation & Forms Best Practices

### Community 172 - "Brava CMS"
Cohesion: 0.29
Nodes (7): Brava CMS, Documentation Roadmap, Getting Started, Key Features & Architecture, License, Tech Stack, Testing & Code Quality

### Community 173 - "Configuration Best Practices"
Cohesion: 0.33
Nodes (5): Configuration Best Practices, `env()` Only in Config Files, Use `App::environment()` for Environment Checks, Use Constants and Language Files, Use Encrypted Env or External Secrets

### Community 174 - "Yang Ada di Dashboard"
Cohesion: 0.33
Nodes (6): Baris 1 — CMS Stats, Baris 2 — Analytics Stat Cards, Baris 3 — Grafik, Baris 4 — Detail, Baris 5 — Insight, Yang Ada di Dashboard

### Community 180 - "Tanya Jawab & Troubleshooting"
Cohesion: 0.40
Nodes (5): **1. Bagaimana cara menguji apakah dasbor sudah terkoneksi dengan GA4 asli?**, **2. Mengapa grafik saya masih nol (0) setelah dipasang?**, **3. Apa yang terjadi jika saya mengosongkan `GA4_PROPERTY_ID` di `.env`?**, **4. Apakah kuota API Google aman dan tidak akan kena limit?**, Tanya Jawab & Troubleshooting

## Knowledge Gaps
- **369 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+364 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `User.php`, `ContentAccessPolicy.php`, `Illuminate\Database\Eloquent\Factories\Factory`, `TeamMember`, `Service`, `SettingPolicy`, `.teamMember`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Why does `Controller` connect `Illuminate\Http\RedirectResponse` to `User.php`, `Promo`, `Faq`, `Media`, `Blog`, `Testimonial`, `TeamMember`, `PortfolioItem`, `Illuminate\Http\JsonResponse`, `Service`, `Illuminate\View\View`, `Category`, `Admin/PromoController.php`, `ProfileController.php`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Blog` connect `Blog` to `User.php`, `UpdateBlogRequest`, `Service`, `Category`, `AnalyticsService`, `.build`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 8 inferred relationships involving `User` (e.g. with `.index()` and `.store()`) actually correct?**
  _`User` has 8 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _369 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `User.php` be split into smaller, more focused modules?**
  _Cohesion score 0.05593008739076155 - nodes in this community are weakly interconnected._
- **Should `ContentAccessPolicy.php` be split into smaller, more focused modules?**
  _Cohesion score 0.10666666666666667 - nodes in this community are weakly interconnected._