# API and Route Contract

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## 1. Purpose

This document defines the HTTP route contract for the public website and admin backend. The project does not expose a JSON REST API in v1.0. The term API in this document means the server-side route, form, and controller contract used by the CMS.

---

## 2. Global Rules

| Rule | Requirement |
|---|---|
| Authentication | All `/admin/*` routes require an authenticated admin session except `/admin/login` |
| CSRF | Every state-changing request uses POST and includes `csrf_token` |
| Response after POST | Successful POST requests redirect using the PRG pattern |
| Validation | Invalid input redisplays the form with errors and preserved values |
| Database access | Controllers must use models; models must use PDO prepared statements |
| Output | HTML views must escape user/database values with `htmlspecialchars()` |

---

## 3. Public Routes

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/` | `HomeController` | `index` | Render home page |
| GET | `/about` | `HomeController` | `about` | Render about page |
| GET | `/products` | `ProductController` | `index` | Render published product grid |
| GET | `/products/{slug}` | `ProductController` | `show` | Render product detail page |
| GET | `/portfolio` | `PortfolioController` | `index` | Render published portfolio grid |
| GET | `/portfolio/{slug}` | `PortfolioController` | `show` | Render portfolio detail page |
| GET | `/contact` | `ContactController` | `index` | Render contact page |

### Public Route Constraints

- `{slug}` must be kebab-case ASCII, max 200 characters.
- Missing or unpublished records return 404.
- Public list pages only show `status = 'published'` and `deleted_at IS NULL`.
- SEO metadata is loaded from the database with fallback to site settings.

---

## 4. Admin Authentication Routes

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/login` | `AuthController` | `login` | Show login form |
| POST | `/admin/login` | `AuthController` | `login` | Authenticate admin |
| POST | `/admin/logout` | `AuthController` | `logout` | Destroy admin session |

### Login Request

| Field | Type | Required | Rule |
|---|---|---|---|
| `username` | string | Yes | 1-80 characters |
| `password` | string | Yes | 1+ characters |
| `csrf_token` | string | Yes | Must match session token |

### Login Behavior

- Invalid credentials return a generic error message.
- Successful login calls `session_regenerate_id(true)`.
- Password verification uses `password_verify()`.

---

## 5. Admin Dashboard

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin` | `DashboardController` | `index` | Show CMS dashboard |

Dashboard metrics may include counts for products, portfolios, media items, and pages.

---

## 6. Admin Products

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/products` | `ProductController` | `index` | List products |
| GET | `/admin/products/create` | `ProductController` | `create` | Show create form |
| POST | `/admin/products/create` | `ProductController` | `create` | Create product |
| GET | `/admin/products/{id}/edit` | `ProductController` | `edit` | Show edit form |
| POST | `/admin/products/{id}/edit` | `ProductController` | `edit` | Update product |
| POST | `/admin/products/{id}/delete` | `ProductController` | `delete` | Soft-delete product |

### Product Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `name` | string | Yes | Max 255 characters |
| `slug` | string | Yes | Unique, kebab-case, max 200 characters |
| `description` | text | No | Trusted admin content |
| `line_url` | string | No | Valid URL, max 512 characters |
| `qr_code_media_id` | integer | No | Existing media record |
| `status` | enum | Yes | `published` or `draft` |
| `seo_title` | string | No | Max 255 characters |
| `seo_description` | text | No | Plain text |
| `media_ids` | integer[] | No | Existing media records, ordered |

---

## 7. Admin Portfolios

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/portfolios` | `PortfolioController` | `index` | List portfolio items |
| GET | `/admin/portfolios/create` | `PortfolioController` | `create` | Show create form |
| POST | `/admin/portfolios/create` | `PortfolioController` | `create` | Create portfolio item |
| GET | `/admin/portfolios/{id}/edit` | `PortfolioController` | `edit` | Show edit form |
| POST | `/admin/portfolios/{id}/edit` | `PortfolioController` | `edit` | Update portfolio item |
| POST | `/admin/portfolios/{id}/delete` | `PortfolioController` | `delete` | Soft-delete portfolio item |

### Portfolio Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `title` | string | Yes | Max 255 characters |
| `slug` | string | Yes | Unique, kebab-case, max 200 characters |
| `description` | text | No | Trusted admin content |
| `status` | enum | Yes | `published` or `draft` |
| `seo_title` | string | No | Max 255 characters |
| `seo_description` | text | No | Plain text |
| `media_ids` | integer[] | No | Existing media records, ordered |

---

## 8. Admin Media

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/media` | `MediaController` | `index` | Show media library |
| POST | `/admin/media/upload` | `MediaController` | `upload` | Upload image |
| POST | `/admin/media/{id}/delete` | `MediaController` | `delete` | Soft-delete media record |
| POST | `/admin/media/{id}/alt-text` | `MediaController` | `updateAltText` | Update alt text |

### Upload Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `file` | file | Yes | JPEG, PNG, GIF, WebP; max 5 MB |
| `alt_text` | string | No | Max 255 characters |
| `csrf_token` | string | Yes | Must match session token |

### Upload Behavior

- MIME is detected with `finfo_file()`.
- Original filename is stored for display only.
- Stored filename is random hex with safe extension.
- Failed upload must not create a database record.

---

## 9. Admin Pages and Sections

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/pages` | `PageController` | `index` | List fixed pages |
| GET | `/admin/pages/{id}/edit` | `PageController` | `edit` | Show page edit form |
| POST | `/admin/pages/{id}/edit` | `PageController` | `edit` | Update page metadata and sections |

### Page Fields

| Field | Type | Required | Rule |
|---|---|---|---|
| `title` | string | Yes | Max 255 characters |
| `slug` | string | Yes | Unique, kebab-case |
| `status` | enum | Yes | `published` or `draft` |
| `seo_title` | string | No | Max 255 characters |
| `seo_description` | text | No | Plain text |
| `sections` | array | No | Section content and sort order |

---

## 10. Admin Menus and Settings

| Method | Path | Controller | Action | Description |
|---|---|---|---|---|
| GET | `/admin/menus` | `MenuController` | `index` | List menu items |
| POST | `/admin/menus/create` | `MenuController` | `create` | Create menu item |
| POST | `/admin/menus/{id}/edit` | `MenuController` | `edit` | Update menu item |
| POST | `/admin/menus/{id}/delete` | `MenuController` | `delete` | Delete or deactivate menu item |
| GET | `/admin/settings` | `SettingsController` | `index` | Show settings form |
| POST | `/admin/settings` | `SettingsController` | `index` | Update site settings |

---

## 11. Standard Error Handling

| Condition | Expected Response |
|---|---|
| Unauthenticated admin request | Redirect to `/admin/login` |
| Invalid CSRF token | HTTP 403 with generic message |
| Missing record | HTTP 404 |
| Validation failure | Same form with error messages |
| Upload validation failure | Media form with error message |
| Server/database failure in production | Generic error response and server-side log entry |

---

*Last updated: 2026-06-02*
