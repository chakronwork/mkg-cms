# Implementation Tasks

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## 1. Task Management Rules

- Each feature must pass requirements, implementation, security, and testing checks before being marked complete.
- Admin write actions must use POST, CSRF verification, validation, and redirect-after-post.
- Database access must use PDO prepared statements.
- Any schema change must be reflected in documentation and SQL.
- Do not introduce framework-level complexity unless explicitly approved.

---

## 2. Milestones

| Milestone | Scope | Exit Criteria |
|---|---|---|
| M1 | Project foundation | Directory structure, config, router, base controller/model, database connection |
| M2 | Auth and security baseline | Login/logout, sessions, CSRF, security headers, error mode |
| M3 | Content foundation | Pages, page sections, menus, site settings |
| M4 | Media library | Upload, browse, alt text, soft delete, picker integration |
| M5 | Products | CRUD, images, LINE URL, QR code, SEO, public listing/detail |
| M6 | Portfolios | CRUD, images, SEO, public listing/detail |
| M7 | Final hardening | Manual tests, security checklist, deployment notes |

---

## 3. Foundation Tasks

| ID | Task | Status |
|---|---|---|
| FND-01 | Create project directory structure from TSD | DONE |
| FND-02 | Create `config/database.example.php` | DONE |
| FND-03 | Create `config/app.php` with base URL and upload settings | DONE |
| FND-04 | Implement PDO connection helper/base model | DONE |
| FND-05 | Implement frontend entry point `public/index.php` | DONE |
| FND-06 | Implement admin entry point `public/admin/index.php` | DONE |
| FND-07 | Implement simple router | DONE |
| FND-08 | Implement base controller with `view()` and `redirect()` helpers | DONE |
| FND-09 | Create `database/schema.sql` | DONE |

---

## 4. Security and Auth Tasks

| ID | Task | Status |
|---|---|---|
| SEC-01 | Implement `Auth` helper | DONE |
| SEC-02 | Implement `Csrf` helper | DONE |
| SEC-03 | Add secure `session_start()` options | DONE |
| SEC-04 | Create admin login form | DONE |
| SEC-05 | Implement login with `password_verify()` | DONE |
| SEC-06 | Regenerate session ID after login | DONE |
| SEC-07 | Implement POST-only logout with CSRF | DONE |
| SEC-08 | Add security headers | DONE |
| SEC-09 | Add production/development error handling config | DONE |
| SEC-10 | Add upload `.htaccess` PHP execution block | DONE |

---

## 5. Admin Content Tasks

| ID | Task | Status |
|---|---|---|
| ADM-01 | Create AdminLTE layout shell | TODO |
| ADM-02 | Create dashboard page | DONE |
| ADM-03 | Implement page list/edit flow | DONE |
| ADM-04 | Implement page section editing with TinyMCE | DONE |
| ADM-05 | Implement menu CRUD/reorder | TODO |
| ADM-06 | Implement site settings form | DONE |
| ADM-07 | Implement SEO fields for pages | DONE |
| ADM-08 | Add form validation and error messages | DONE |

---

## 6. Media Tasks

| ID | Task | Status |
|---|---|---|
| MED-01 | Create media model | DONE |
| MED-02 | Create media library grid | DONE |
| MED-03 | Implement upload form | DONE |
| MED-04 | Validate upload size max 5 MB | DONE |
| MED-05 | Validate real MIME type with `finfo` | DONE |
| MED-06 | Generate randomized stored filename | DONE |
| MED-07 | Store files under `public/uploads/{year}/{month}/` | DONE |
| MED-08 | Store original filename, path, MIME, size, alt text | DONE |
| MED-09 | Implement media soft delete | DONE |
| MED-10 | Implement media picker for product/portfolio forms | DONE |

---

## 7. Product Tasks

| ID | Task | Status |
|---|---|---|
| PRD-01 | Create product model | DONE |
| PRD-02 | Create admin product list with pagination | TODO |
| PRD-03 | Create product create/edit forms | DONE |
| PRD-04 | Validate required fields and unique slug | DONE |
| PRD-05 | Save product images and sort order | DONE |
| PRD-06 | Support LINE URL and QR code media | DONE |
| PRD-07 | Implement product soft delete | DONE |
| PRD-08 | Render public product listing | DONE |
| PRD-09 | Render public product detail page | DONE |
| PRD-10 | Render product SEO metadata | DONE |

---

## 8. Portfolio Tasks

| ID | Task | Status |
|---|---|---|
| PTF-01 | Create portfolio model | DONE |
| PTF-02 | Create admin portfolio list | DONE |
| PTF-03 | Create portfolio create/edit forms | DONE |
| PTF-04 | Validate required fields and unique slug | DONE |
| PTF-05 | Save portfolio images and sort order | DONE |
| PTF-06 | Implement portfolio soft delete | DONE |
| PTF-07 | Render public portfolio listing | DONE |
| PTF-08 | Render public portfolio detail page | DONE |
| PTF-09 | Render portfolio SEO metadata | DONE |

---

## 9. Frontend Tasks

| ID | Task | Status |
|---|---|---|
| FE-01 | Create frontend layout shell | DONE |
| FE-02 | Render navigation from menus table | DONE |
| FE-03 | Render home page sections | DONE |
| FE-04 | Render about page sections | DONE |
| FE-05 | Render contact page with settings and map | DONE |
| FE-06 | Render site logo/favicon from settings | TODO |
| FE-07 | Add responsive Bootstrap layout | DONE |
| FE-08 | Add fallback SEO metadata | DONE |

---

## 10. Quality Gate

Before release, verify:

- [ ] All acceptance criteria in SRS are satisfied
- [ ] All security checklist items in SECURITY.md are satisfied
- [ ] Manual happy-path and error-path tests are completed
- [x] Upload directory blocks PHP execution
- [x] Production config disables error display
- [x] Database schema imports cleanly on MySQL 8.0
- [x] Public pages show only published, non-deleted content
- [ ] Admin forms preserve values after validation failure

---

*Last updated: 2026-06-02*
