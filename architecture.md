# Architecture

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## 1. Architecture Overview

mkg-cms uses a lightweight MVC architecture implemented in plain PHP. The system is intentionally small so it can run on XAMPP and shared hosting without framework, Composer, or Node.js requirements.

```text
Browser
  -> Apache
  -> public/index.php or public/admin/index.php
  -> Router
  -> Controller
  -> Model
  -> PDO / MySQL
  -> View
  -> HTML Response
```

---

## 2. System Boundaries

| Boundary | Responsibility |
|---|---|
| Public frontend | Displays published pages, products, portfolios, menus, SEO metadata, and contact information |
| Admin backend | Authenticated content management for business staff |
| Database | Stores content, media metadata, settings, menus, admin users, and soft-delete timestamps |
| File storage | Stores uploaded image files under `public/uploads/{year}/{month}/` |
| External services | CDN assets, Google Maps iframe, LINE/Facebook contact links |

---

## 3. Core Components

| Component | Responsibility |
|---|---|
| Router | Maps HTTP method and path to controller action |
| Base Controller | Provides view rendering, redirect, CSRF verification, and common response helpers |
| Base Model | Owns PDO connection access and shared query helpers |
| Auth Helper | Manages admin session verification |
| CSRF Helper | Generates and verifies CSRF tokens |
| Admin Controllers | Handle backend CRUD workflows |
| Frontend Controllers | Load published content and render public pages |
| Views | Render HTML templates with escaped output |

---

## 4. Request Lifecycle

### Public GET Request

```text
1. Visitor requests a public URL.
2. Apache forwards request to public/index.php.
3. Router matches path to a frontend controller action.
4. Controller loads published content through models.
5. View renders escaped HTML and SEO metadata.
6. Response is returned to browser.
```

### Admin POST Request

```text
1. Admin submits a form.
2. Apache forwards request to public/admin/index.php.
3. Auth check verifies active admin session.
4. Controller verifies CSRF token before any write.
5. Controller validates request data.
6. Model writes using PDO prepared statements.
7. Controller redirects after success using PRG pattern.
```

---

## 5. Data Architecture

### Entity Groups

| Group | Tables |
|---|---|
| Identity | `admins` |
| Pages | `pages`, `page_sections` |
| Products | `products`, `product_images` |
| Portfolios | `portfolios`, `portfolio_images` |
| Media | `media` |
| Navigation | `menus` |
| Site configuration | `settings` |

### Data Rules

- Content tables use `deleted_at` for soft delete.
- Slugs are unique per resource type.
- Foreign keys enforce relationships between content and media.
- Public queries only return `status = 'published'` and `deleted_at IS NULL` where applicable.
- Admin list queries exclude soft-deleted records unless a restore feature is explicitly added.

---

## 6. Security Architecture

| Control | Architecture Rule |
|---|---|
| Authentication | All `/admin` routes require active session except login |
| Session fixation defense | Regenerate session ID after login |
| CSRF | Every state-changing request must include and verify CSRF token |
| SQL injection defense | All database access uses PDO prepared statements |
| XSS defense | Escape all user/database output with `htmlspecialchars()` except trusted admin HTML |
| Upload defense | Validate MIME with `finfo`, size limit 5 MB, random filenames |
| Error handling | Development shows errors; production logs errors without exposing internals |

---

## 7. Deployment Architecture

### Development

```text
Windows 10/11
  -> XAMPP
  -> Apache
  -> PHP 8.3
  -> MySQL 8.0
```

### Production Target

```text
Shared hosting / cPanel
  -> Apache
  -> PHP 8.3-compatible runtime
  -> MySQL-compatible database
  -> Public document root mapped to public/
```

---

## 8. Architectural Decisions

| Decision | Rationale |
|---|---|
| Plain PHP MVC | Small project, easy deployment, no framework overhead |
| PDO instead of ORM | Transparent SQL, fewer dependencies, easier debugging |
| Soft delete | Prevents accidental data loss from admin actions |
| PRG after POST | Prevents duplicate form submissions on refresh |
| Media library abstraction | Allows products, portfolios, logo, favicon, and QR codes to reuse images |
| Manual routing | Sufficient for small route set and shared hosting compatibility |

---

## 9. Quality Attributes

| Attribute | Requirement |
|---|---|
| Maintainability | Clear class responsibilities and PSR-12 formatting |
| Security | OWASP-aligned baseline applied to every feature |
| Performance | Local admin pages should load within 3 seconds |
| Portability | Works without Composer, npm, or framework runtime |
| Reliability | Failed uploads must not create orphaned database records |

---

## 10. Architecture Review Checklist

- [ ] New feature has a clear controller, model, and view boundary
- [ ] Database changes are documented in SQL
- [ ] Admin writes require authentication and CSRF verification
- [ ] Public output is escaped correctly
- [ ] Queries use prepared statements
- [ ] Soft delete is used for content deletion
- [ ] Uploads follow the approved validation pipeline
- [ ] No framework or dependency is introduced without explicit justification

---

*Last updated: 2026-06-02*
