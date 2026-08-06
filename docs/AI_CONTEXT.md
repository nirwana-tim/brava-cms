# Brava CMS - AI Context

> This document provides architectural context, coding standards, and project conventions for all AI coding assistants working on this repository.
>
> AI must follow this document unless explicitly instructed otherwise.

---

# Project Overview

Project Name: Brava CMS

Brava CMS is a reusable Headless CMS built with Laravel 13.

This project is **NOT** a public website.

This project only provides:

- Admin Dashboard
- Content Management System
- REST API
- Authentication
- Media Management

The public website will be developed separately using Next.js.

Next.js consumes the REST API only.

This repository does NOT contain the frontend website.

---

# Tech Stack

Framework

- Laravel 13

PHP

- PHP 8.3+

Authentication

- Laravel Breeze

Frontend (Admin)

- Blade
- Tailwind CSS v4
- Vite

Database

- MySQL (production)
- SQLite (development, current .env)

Storage

- Laravel Storage
- Relative paths only

API

- REST API
- Versionless (single namespace)
- Consumed by Next.js public website

Package Manager

- Bun (not npm)
- bun.lock (not package-lock.json)

Deployment

- Shared Hosting
- Apache

---

# Architecture

Brava CMS follows a layered architecture.

Controller

↓

Service

↓

Model

Never put business logic inside controllers.

Controllers should be thin.

Business logic belongs inside Services.

Models should only contain:

- Relationships
- Casts
- Scopes
- Accessors
- Mutators

Avoid putting business logic in models.

---

# Repository Pattern

Repository Pattern is NOT used.

Reason:

Current project complexity does not justify it.

Preferred architecture:

Controller

↓

Service

↓

Model

Do not introduce Repository Pattern unless requested.

---

# Folder Structure

app/

Actions/

Enums/

Http/

Controllers/

Admin/

Api/

Requests/

Resources/

Models/

Policies/

Providers/

Services/

Traits/

Support/

routes/

admin.php

api.php

web.php

---

# Admin

Admin panel uses Blade.

Admin routes are separated.

Example:

/admin/dashboard

/admin/services

/admin/blogs

/admin/portfolio

/admin/testimonials

Never expose admin pages through API.

---

# API

API is read/write only for frontend integration.

REST conventions must be followed.

Example:

GET /api/services

POST /api/contact

GET /api/blogs

GET /api/blogs/{slug}

GET /api/sitemap

Responses must always use API Resources.

Never return Eloquent models directly.

Good

return ServiceResource::collection($services);

Bad

return Service::all();

---

# Validation

Always use Form Request.

Example

StoreServiceRequest

UpdateServiceRequest

Never validate directly inside controllers.

---

# Authorization

Use Policies where applicable.

Do not manually compare user IDs inside controllers.

---

# Authentication

Laravel Breeze.

Session Authentication.

No JWT.

No Sanctum.

No Passport.

API is public for website content.

Admin authentication is session-based.

---

# User Roles

Current roles:

- Super Admin
- Admin
- Staff

`staff` can manage content (create/update/delete) but cannot manage users, settings, or system configuration. Do not implement complex permission systems.

Role expansion may happen in future versions.

---

# Database Conventions

Use:

foreignId()

cascadeOnDelete()

softDeletes() where appropriate.

timestamps()

Use UUID only if requested.

Default primary key:

id (BIGINT)

---

# Naming Convention

Models

Service

Blog

PortfolioItem

Testimonial

Category

Media

Setting

Controllers

ServiceController

BlogController

Services

ServiceService

BlogService

Requests

StoreServiceRequest

UpdateServiceRequest

Resources

ServiceResource

BlogResource

Tables

services

blogs

portfolio_items

testimonials

media

settings

Use singular model names.

Use plural table names.

---

# Slugs

Every public content must have slug.

Examples:

services

blogs

portfolio

Slug must be unique.

Generate automatically when possible.

---

# SEO

SEO is embedded inside each content entity.

Do NOT create a separate SEO table.

Each module owns its SEO fields.

Example:

meta_title

meta_description

meta_keywords

og_title

og_description

og_image

canonical_url

robots_index

robots_follow

schema_type

Note: `canonical_url`, `og_title`, and `og_description` are computed at runtime in API Resources (from `FRONTEND_URL` + fallbacks), NOT stored as columns. Image fields in API responses are returned as absolute URLs via `url()`.

Global SEO lives inside Settings.

Example:

site_name

default_meta_title

default_meta_description

default_og_image

google_verification

bing_verification

organization_schema

favicon

logo

---

# Media Library

Media is shared.

Services

Blogs

Portfolio

Pages

Team

must reuse Media Library.

Avoid duplicate uploads.

Supported files:

Images

PDF

Word

Excel

ZIP

Videos

---

# Website Settings

Settings module stores:

Company Name

Logo

Favicon

Address

Email

Phone

Google Maps

Social Links

Footer

Copyright

Analytics IDs

Verification Codes

Default SEO

---

# Content Modules

Current modules

Dashboard

Services

Categories

Blogs

Portfolio

Testimonials

FAQ

Team

Media

Settings

Users

Recycle Bin (Trash - Super Admin only)

Future modules

Career

Newsletter

Analytics

Backup

Audit Log

Multi Language

---

# Coding Style

Use:

Constructor Injection

Service Layer

Form Request

API Resource

Enum

Policies

Use Carbon for date operations.

Avoid helper functions when service classes are more appropriate.

Keep methods small.

Single Responsibility Principle.

Prefer readable code over clever code.

---

# Eloquent

Always eager load relationships when needed.

Avoid N+1 queries.

Use pagination for admin tables.

Never call all() for large datasets.

---

# API Response Format

Success

{
    "success": true,
    "message": "...",
    "data": {}
}

Validation Error

{
    "success": false,
    "message": "Validation failed",
    "errors": {}
}

---

# Status

Prefer PHP Enums.

Example

Draft

Published

Archived

Inactive

Active

Avoid string literals throughout the codebase.

---

# File Upload

Use Laravel Storage.

Never store absolute paths.

Store only relative paths inside database.

---

# Logging

Unexpected exceptions must be logged.

Never swallow exceptions silently.

---

# Performance

Use pagination.

Use eager loading.

API endpoints use caching via cache services (`Cache::flexible` / `Cache::remember`).

Cache is automatically flushed via the `App\Traits\ClearsApiCache` trait when CMS models are saved or deleted.

Successful `GET /api/*` responses include `Cache-Control: public, max-age=900, s-maxage=900` via the `App\Http\Middleware\CacheApiHeaders` middleware (registered on the `api` middleware group). POST requests and non-API routes are never publicly cached.

Avoid premature optimization.

---

# Reference Documents

| File | Purpose |
|------|---------|
| `docs/SCHEMA.md` | Complete database schema for all modules |
| `docs/API.md` | API endpoint specifications, request/response, SEO strategy |
| `docs/ANALYTICS.md` | Google Analytics dashboard plan (post-MVP) |
| `docs/AI_BEHAVIOUR.md` | AI coding behavior guidelines |

# Code Generation Rules

When generating code:

- Follow Laravel 13 conventions.
- Follow PSR-12.
- Use type declarations.
- Use return types.
- Use constructor property promotion.
- Use dependency injection.
- Do not over-engineer.
- Keep code maintainable.
- Prefer readability over abstraction.

---

# Important

This project is intended to become a reusable CMS template.

Do not implement client-specific features.

Every implementation should be generic and reusable.

Always think as if this CMS will be used by hundreds of different company profile websites.
