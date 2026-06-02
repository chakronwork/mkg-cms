# Testing Strategy

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## 1. Purpose

This document defines the testing approach for mkg-cms. The project is a small PHP/MySQL CMS, so v1.0 emphasizes deterministic manual testing, security verification, database checks, and regression checklists. Automated tests may be added later when the codebase is stable.

---

## 2. Test Scope

### In Scope

- Admin authentication and session behavior
- CSRF protection on state-changing actions
- Product, portfolio, page, menu, settings, and media workflows
- Public rendering of published content
- Server-side validation
- File upload security
- SQL and output escaping review
- Browser compatibility on modern desktop and mobile browsers

### Out of Scope for v1.0

- Load testing beyond basic local performance checks
- Automated browser test suite
- Public API testing because no JSON REST API exists in v1.0
- Payment or e-commerce testing

---

## 3. Test Environments

| Environment | Purpose | Stack |
|---|---|---|
| Local development | Main build and manual testing | Windows 10/11, XAMPP, PHP 8.3, MySQL 8.0 |
| Production-like shared hosting | Deployment verification | Apache, PHP 8.3-compatible runtime, MySQL-compatible database |

---

## 4. Test Data

Minimum seed data:

- 1 active admin user with bcrypt password hash
- 3 fixed pages: Home, About, Contact
- 5 products with at least 2 images each
- 5 portfolio items with at least 2 images each
- 5 menu items
- 1 settings row with business contact information
- Sample media records for logo, favicon, product images, portfolio images, and QR code

---

## 5. Functional Test Cases

### Authentication

| ID | Scenario | Expected Result |
|---|---|---|
| AUTH-T01 | Visit `/admin` while logged out | Redirects to `/admin/login` |
| AUTH-T02 | Login with valid credentials | Session created and redirected to dashboard |
| AUTH-T03 | Login with invalid password | Generic error shown; no session created |
| AUTH-T04 | Logout via POST | Session destroyed and redirected to login |
| AUTH-T05 | Access admin page after logout | Redirects to login |

### Products

| ID | Scenario | Expected Result |
|---|---|---|
| PROD-T01 | Create product with valid fields | Product appears in admin list |
| PROD-T02 | Create product without name | Validation error shown |
| PROD-T03 | Create product with duplicate slug | Validation error shown |
| PROD-T04 | Add multiple images | Images save in selected order |
| PROD-T05 | Soft-delete product | Product removed from active admin/public lists; `deleted_at` set |
| PROD-T06 | Publish product | Product appears on public product list and detail page |
| PROD-T07 | Draft product | Product does not appear publicly |

### Portfolios

| ID | Scenario | Expected Result |
|---|---|---|
| PORT-T01 | Create portfolio item with valid fields | Item appears in admin list |
| PORT-T02 | Add gallery images | Images render on detail page |
| PORT-T03 | Duplicate slug | Validation error shown |
| PORT-T04 | Soft-delete portfolio item | Item removed from active lists; `deleted_at` set |
| PORT-T05 | Draft portfolio item | Item does not appear publicly |

### Media

| ID | Scenario | Expected Result |
|---|---|---|
| MEDIA-T01 | Upload valid JPEG/PNG/GIF/WebP | File stored and media record created |
| MEDIA-T02 | Upload file larger than 5 MB | Upload rejected with error |
| MEDIA-T03 | Upload non-image file | Upload rejected with error |
| MEDIA-T04 | Upload image with unsafe original filename | Stored filename is randomized |
| MEDIA-T05 | Delete media item | Media record soft-deleted |
| MEDIA-T06 | Update alt text | Alt text persists and renders where used |

### Pages, Menus, and Settings

| ID | Scenario | Expected Result |
|---|---|---|
| PAGE-T01 | Edit home page section | Public home page reflects change |
| PAGE-T02 | Set page to draft | Page is not publicly visible |
| MENU-T01 | Create active menu item | Menu item appears publicly |
| MENU-T02 | Deactivate menu item | Menu item disappears publicly |
| SET-T01 | Update phone/email/LINE URL | Contact page reflects new values |
| SET-T02 | Update logo/favicon | Public layout uses selected media |

---

## 6. Security Test Cases

| ID | Scenario | Expected Result |
|---|---|---|
| SEC-T01 | Submit POST without CSRF token | HTTP 403 or rejected request |
| SEC-T02 | Submit POST with invalid CSRF token | HTTP 403 or rejected request |
| SEC-T03 | Attempt SQL injection in login/product search/slug | Query remains safe; no SQL error or unauthorized access |
| SEC-T04 | Enter HTML/JS in normal text field | Output is escaped; script does not execute |
| SEC-T05 | Upload `.php` file disguised as image | Upload rejected by MIME validation |
| SEC-T06 | Directly request PHP file under uploads | PHP execution blocked |
| SEC-T07 | Access another admin route after session timeout/logout | Redirects to login |
| SEC-T08 | Production mode error path | No stack trace or SQL details displayed |

---

## 7. Database Verification

For each CRUD feature, verify:

- Inserts create expected records.
- Updates only modify intended records.
- Deletes set `deleted_at` instead of hard deleting content.
- Public queries exclude `deleted_at IS NOT NULL` records.
- Slug uniqueness constraints are enforced.
- Foreign key relationships behave as documented.
- Failed uploads do not create orphaned media records.

---

## 8. Browser and Responsive Checks

| Area | Checks |
|---|---|
| Desktop | Admin forms, tables, modals, public pages |
| Tablet | Admin layout remains usable, forms do not overflow |
| Mobile | Public navigation, product grids, portfolio grids, contact page |
| Accessibility baseline | Images have alt text where available; buttons and links are understandable |

Target browsers:

- Latest Chrome
- Latest Edge
- Latest Firefox
- Mobile Chrome or equivalent Android browser

---

## 9. Manual Release Checklist

- [ ] Import `database/schema.sql` into a clean MySQL database
- [ ] Configure database connection
- [ ] Create initial admin user
- [ ] Confirm login/logout works
- [ ] Confirm all admin modules are reachable only after login
- [ ] Confirm every POST form rejects invalid CSRF token
- [ ] Create, edit, publish, draft, and soft-delete products
- [ ] Create, edit, publish, draft, and soft-delete portfolios
- [ ] Upload valid images and reject invalid files
- [ ] Update pages, menus, SEO, and settings
- [ ] Confirm public pages render correctly
- [ ] Confirm production config hides errors
- [ ] Confirm uploads directory blocks PHP execution

---

## 10. Defect Reporting Format

Use this format for any bug found during testing:

```text
ID: BUG-YYYYMMDD-001
Severity: Critical / High / Medium / Low
Area: Auth / Products / Portfolio / Media / Pages / Settings / Frontend / Security
Environment: Local XAMPP or Production-like
Steps to Reproduce:
Expected Result:
Actual Result:
Evidence:
Status: Open / Fixed / Verified
```

---

*Last updated: 2026-06-02*
