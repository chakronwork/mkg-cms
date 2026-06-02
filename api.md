# API and Route Contract

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Current  

---

## 1. Purpose

This document defines the current HTTP route contract for the public website and admin backend. The project does not expose a JSON REST API in v1.0. "API" in this document means the server-side route, form, and controller contract used by the CMS.

---

## 2. Global Rules

| Rule | Requirement |
|---|---|
| Authentication | All `/admin/*` routes require an authenticated admin session except `/admin/login` |
| CSRF | Every state-changing admin request uses POST and includes `csrf_token` |
| Response after POST | Successful POST requests redirect using the PRG pattern |
| Validation | Invalid input redisplays the form with error messages and submitted values where supported |
| Database access | Controllers use models; models use PDO prepared statements |
| Output escaping | Views escape user/database values with `e()` before rendering |
| Base URL | Public routes are served under `base_url`; admin routes are served under `admin_url` |

---

## 3. Public Routes

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/` | `HomeController` | `index` | Render home page |
| GET | `/about` | `HomeController` | `about` | Render about page |
| GET | `/products` | `FrontendProductController` | `index` | Render published product listing |
| GET | `/products/{slug}` | `FrontendProductController` | `show` | Render published product detail |
| GET | `/portfolio` | `FrontendPortfolioController` | `index` | Render published portfolio listing |
| GET | `/portfolio/{slug}` | `FrontendPortfolioController` | `show` | Render published portfolio detail |
| GET | `/contact` | `ContactController` | `index` | Render contact page |

### Public Route Constraints

| Item | Rule |
|---|---|
| `{slug}` | Kebab-case route segment mapped to database slug |
| Missing records | Return HTTP 404 |
| Unpublished records | Return HTTP 404 |
| Soft-deleted records | Excluded from public listing/detail |
| SEO metadata | Loaded from record/page data with site-level fallback where implemented |

---

## 4. Admin Authentication

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/login` | `AuthController` | `login` | Show login form |
| POST | `/admin/login` | `AuthController` | `login` | Authenticate admin |
| POST | `/admin/logout` | `AuthController` | `logout` | Logout and destroy admin session |

### Login Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `username` | string | Yes | Admin username |
| `password` | string | Yes | Admin password |
| `csrf_token` | string | Yes | Must match session token |

### Login Behavior

- Invalid credentials render a generic error message.
- Successful login uses `password_verify()` and calls `session_regenerate_id(true)`.
- Authenticated admins visiting `/admin/login` are redirected to `/admin`.
- Logout is POST-only and CSRF-protected.

---

## 5. Admin Dashboard

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin` | `DashboardController` | `index` | Show CMS dashboard |
| GET | `/admin/dashboard` | `DashboardController` | `index` | Dashboard alias |

---

## 6. Admin Pages and Sections

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/pages` | `AdminPageController` | `index` | List fixed CMS pages |
| GET | `/admin/pages/{id}/edit` | `AdminPageController` | `edit` | Show page edit form |
| POST | `/admin/pages/{id}/edit` | `AdminPageController` | `edit` | Update page metadata and sections |

### Page Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `title` | string | Yes | Cannot be blank |
| `status` | enum | Yes | `published` or `draft` |
| `seo_title` | string | No | Stored as text |
| `seo_description` | text | No | Stored as text |
| `sections[{id}][section_name]` | string | No | Section display name |
| `sections[{id}][content]` | HTML/text | No | Trusted admin content |
| `sections[{id}][sort_order]` | integer | No | Section order |
| `csrf_token` | string | Yes | Must match session token |

---

## 7. Admin Products

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/products` | `AdminProductController` | `index` | List products with pagination |
| GET | `/admin/products/create` | `AdminProductController` | `create` | Show create form |
| POST | `/admin/products/create` | `AdminProductController` | `create` | Create product |
| GET | `/admin/products/{id}/edit` | `AdminProductController` | `edit` | Show edit form |
| POST | `/admin/products/{id}/edit` | `AdminProductController` | `edit` | Update product |
| POST | `/admin/products/{id}/delete` | `AdminProductController` | `delete` | Soft-delete product |

### Product List Query

| Query | Type | Default | Rule |
|---|---|---|---|
| `page` | integer | `1` | Minimum `1`; 10 products per page |

### Product Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `name` | string | Yes | Cannot be blank |
| `slug` | string | Yes | Unique among non-deleted products; lowercase letters, numbers, hyphens; max 200 chars |
| `description` | text | No | Plain text; line breaks are preserved on public detail pages |
| `line_url` | string | No | Stored as text |
| `qr_code_media_id` | integer | No | `0` or blank stores `NULL` |
| `status` | enum | Yes | `published` or `draft`; invalid value becomes `draft` |
| `seo_title` | string | No | Stored as text |
| `seo_description` | text | No | Stored as text |
| `media_ids` | integer[] or comma/space text | No | Saved to `product_images` in submitted order |
| `csrf_token` | string | Yes | Must match session token |

---

## 8. Admin Portfolios

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/portfolios` | `AdminPortfolioController` | `index` | List portfolio items |
| GET | `/admin/portfolios/create` | `AdminPortfolioController` | `create` | Show create form |
| POST | `/admin/portfolios/create` | `AdminPortfolioController` | `create` | Create portfolio item |
| GET | `/admin/portfolios/{id}/edit` | `AdminPortfolioController` | `edit` | Show edit form |
| POST | `/admin/portfolios/{id}/edit` | `AdminPortfolioController` | `edit` | Update portfolio item |
| POST | `/admin/portfolios/{id}/delete` | `AdminPortfolioController` | `delete` | Soft-delete portfolio item |

### Portfolio Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `title` | string | Yes | Cannot be blank |
| `slug` | string | Yes | Unique among non-deleted portfolios; lowercase letters, numbers, hyphens; max 200 chars |
| `description` | text | No | Plain text; line breaks are preserved on public detail pages |
| `status` | enum | Yes | `published` or `draft`; invalid value becomes `draft` |
| `seo_title` | string | No | Stored as text |
| `seo_description` | text | No | Stored as text |
| `media_ids` | integer[] or comma/space text | No | Saved to `portfolio_images` in submitted order |
| `csrf_token` | string | Yes | Must match session token |

---

## 9. Admin Media

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/media` | `MediaController` | `index` | Show media library |
| POST | `/admin/media/upload` | `MediaController` | `upload` | Upload image |
| POST | `/admin/media/{id}/alt` | `MediaController` | `alt` | Update media alt text |
| POST | `/admin/media/{id}/delete` | `MediaController` | `delete` | Soft-delete media record |

### Upload Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `file` | file | Yes | JPEG, PNG, GIF, or WebP; max 5 MB |
| `alt_text` | string | No | Stored as text |
| `csrf_token` | string | Yes | Must match session token |

### Upload Behavior

- MIME type is detected with `finfo(FILEINFO_MIME_TYPE)`.
- Stored filename is 32 random hex characters plus the configured safe extension.
- Files are stored under `public/uploads/{year}/{month}/`.
- Database `filepath` stores the relative path, for example `2026/06/example.webp`.
- Failed upload validation redisplays the media library with errors and does not create a media record.

---

## 10. Admin Menus

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/menus` | `MenuController` | `index` | List menu items and show add/edit controls |
| POST | `/admin/menus` | `MenuController` | `save` | Create or update a menu item |
| POST | `/admin/menus/reorder` | `MenuController` | `reorder` | Recalculate menu sort order from submitted IDs |
| POST | `/admin/menus/{id}/delete` | `MenuController` | `delete` | Delete menu item |

### Menu Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `id` | integer | No | Present when updating an existing item |
| `title` | string | Yes | Cannot be blank |
| `url` | string | Yes | Cannot be blank |
| `target` | enum | No | `_self` or `_blank`; invalid value becomes `_self` |
| `sort_order` | integer | No | Stored directly on save |
| `is_active` | boolean | No | Checked value stores `1`; missing value stores `0` |
| `menu_ids[]` | integer[] | Reorder only | IDs in desired order; sort values become 10, 20, 30... |
| `csrf_token` | string | Yes | Must match session token |

---

## 11. Admin Settings

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/settings` | `SettingsController` | `index` | Show site settings form |
| POST | `/admin/settings` | `SettingsController` | `index` | Update site settings |

### Settings Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `site_name` | string | Yes | Cannot be blank |
| `site_description` | text | No | Stored as text |
| `phone` | string | No | Stored as text |
| `email` | string | No | Stored as text |
| `line_url` | string | No | Stored as text |
| `facebook_url` | string | No | Stored as text |
| `address` | text | No | Stored as text |
| `google_map_embed` | text | No | Trusted admin embed content |
| `logo_media_id` | integer | No | `0` or blank stores `NULL` |
| `favicon_media_id` | integer | No | `0` or blank stores `NULL` |
| `csrf_token` | string | Yes | Must match session token |

---

## 12. Standard Error Handling

| Condition | Expected Response |
|---|---|
| Unauthenticated admin request | Redirect to `/admin/login` |
| Invalid CSRF token | HTTP 403 with generic message |
| Missing admin/public record | HTTP 404 |
| Validation failure | Same form or listing view with error messages |
| Upload validation failure | Media library view with error messages |
| Unknown route | HTTP 404 plain text from router |
| Server/database failure in production | Generic error response with server-side logging where configured |

---

*Last updated: 2026-06-02*
