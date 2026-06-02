# Project Context

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft  
**Owner:** First (Chakron) / Mae Klong Graphic Internship Project

---

## 1. Purpose

mkg-cms is a custom content management system for Mae Klong Graphic, a Thai sign-making business. The system provides a public website for visitors and an admin backend for staff to manage business content without editing source code.

This document records the business context, project assumptions, stakeholder needs, and boundaries used by the requirements, technical design, testing, and security documents.

---

## 2. Business Goals

| ID | Goal | Success Indicator |
|---|---|---|
| BG-01 | Present Mae Klong Graphic professionally online | Public pages display complete business, product, portfolio, and contact information |
| BG-02 | Reduce developer dependency for routine content updates | Admin can update pages, products, portfolios, menus, SEO, and settings from backend |
| BG-03 | Keep the system maintainable for a solo developer | Simple PHP MVC structure, direct PDO, clear documentation |
| BG-04 | Support Thai shared hosting deployment | Runs on PHP/MySQL without complex build tools or paid services |

---

## 3. Product Scope

### In Scope

- Public website pages: Home, About, Products, Portfolio, Contact
- Admin login/logout
- Admin dashboard
- Content management for fixed pages and page sections
- Product catalog management with images and LINE/QR contact support
- Portfolio management with image galleries
- Media library for image uploads and selection
- Menu management
- SEO metadata management
- Site-wide settings such as logo, contact details, and map embed
- Security baseline for authentication, sessions, CSRF, SQL injection, XSS, and uploads

### Out of Scope

- E-commerce checkout and payment processing
- Public user registration
- Multi-language workflow
- REST API / headless CMS mode
- Plugin ecosystem
- Advanced role-based permissions beyond initial admin/staff distinction
- Automated image optimization pipeline

---

## 4. Stakeholders

| Stakeholder | Role | Needs |
|---|---|---|
| Mae Klong Graphic | Client / business owner | Easy content updates, clear product presentation, customer contact flow |
| Admin user | Backend operator | Simple forms, reliable upload flow, clear validation errors |
| Visitor | Public website user | Fast browsing, clear product/portfolio information, easy contact path |
| Developer | Builder / maintainer | Predictable architecture, safe database access, maintainable code |

---

## 5. User Personas

### Business Admin

- Uses the backend to update products, portfolios, pages, and contact data
- Does not need technical knowledge of HTML, SQL, or PHP
- Needs clear feedback when saving, uploading, or deleting content

### Public Visitor

- Browses products and previous work
- Uses LINE, phone, Facebook, or contact page information to contact the business
- Expects pages to load quickly and display correctly on mobile

### Developer Maintainer

- Implements and reviews features
- Maintains database schema, security controls, and deployment configuration
- Uses documentation as the single source of project intent

---

## 6. Assumptions

- The system runs on XAMPP during development with PHP 8.3, Apache, and MySQL 8.0.
- The production target is Thai shared hosting or cPanel-style hosting.
- Admin users are trusted business staff.
- Uploaded media are images only: JPEG, PNG, GIF, and WebP.
- The business prefers simple maintainability over framework complexity.
- No Node.js build process is required for v1.0.

---

## 7. Constraints

| Area | Constraint |
|---|---|
| Runtime | PHP 8.3 and MySQL 8.0 |
| Architecture | Lightweight MVC, no full-stack framework |
| Database | PDO prepared statements only |
| Hosting | Must work on XAMPP and shared hosting |
| Security | Must follow the project security baseline and OWASP-aligned controls |
| Dependencies | Prefer CDN or local static copies for frontend libraries |

---

## 8. Key Risks

| Risk | Impact | Mitigation |
|---|---|---|
| Weak upload validation | Remote code execution or stored malicious files | Validate MIME with finfo, randomize names, block PHP in uploads |
| Missing CSRF protection | Unauthorized admin actions | Require CSRF token on every POST |
| Direct SQL concatenation | SQL injection | Use PDO prepared statements everywhere |
| Unescaped output | XSS in admin or frontend | Escape output with htmlspecialchars except trusted TinyMCE HTML |
| Over-engineering | Delays and maintenance burden | Keep architecture intentionally small and documented |

---

## 9. Related Documents

| Document | Purpose |
|---|---|
| README.md | Project overview and setup |
| SRS.md | Functional and non-functional requirements |
| TSD.md | Technical architecture and implementation conventions |
| SECURITY.md | Security baseline and checklist |
| architecture.md | Architecture decision and system design summary |
| api.md | Route and request/response contract |
| testing.md | Test strategy and acceptance verification |
| tasks.md | Implementation backlog and tracking |

---

*Last updated: 2026-06-02*
