# SRS — Software Requirements Specification

**Project:** Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [Stakeholders](#3-stakeholders)
4. [Functional Requirements](#4-functional-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [Use Cases](#6-use-cases)
7. [System Constraints](#7-system-constraints)
8. [Acceptance Criteria](#8-acceptance-criteria)

---

## 1. Introduction

### 1.1 Purpose

This document defines the software requirements for **Sign Shop CMS** — a custom content management system built for a Thai sign-making business (Mae Klong Graphic). It serves as the agreement between the developer and the client on what the system must do.

### 1.2 Scope

The system provides:

- A **public website** for customers to browse products, view portfolio, and make contact
- An **admin panel** for the business owner to manage all website content without technical knowledge

### 1.3 Definitions

| Term | Meaning |
|---|---|
| **Admin** | Authenticated business owner or staff using the backend panel |
| **Visitor** | Anonymous public user browsing the frontend website |
| **Soft Delete** | Marking records as deleted without removing them from the database |
| **Slug** | URL-friendly string derived from a title (e.g. `vinyl-banner`) |
| **Media** | Uploaded image files managed through the Media Library |

---

## 2. Overall Description

### 2.1 Product Perspective

Sign Shop CMS is a standalone web application running on a shared hosting or XAMPP environment. It replaces manual HTML editing and provides a structured way to maintain the business website.

### 2.2 Product Functions (Summary)

- Manage website pages and their content sections
- Manage product catalog with images, descriptions, and LINE contact links
- Manage portfolio of completed work with image galleries
- Centralized media library for all uploaded images
- Control navigation menus
- Configure SEO metadata per page
- Manage site-wide settings (business info, logo, social links)

### 2.3 User Classes

| User Class | Description | Access Level |
|---|---|---|
| **Super Admin** | Business owner, full access | All modules |
| **Staff Admin** | Employee, limited access | Content only (TBD) |
| **Visitor** | Public customer | Read-only frontend |

---

## 3. Stakeholders

| Stakeholder | Role | Interest |
|---|---|---|
| Mae Klong Graphic | Client / End User | Easy content updates, professional website |
| Developer (First) | Builder / Maintainer | Clean codebase, extensible design |

---

## 4. Functional Requirements

### 4.1 Authentication

| ID | Requirement |
|---|---|
| AUTH-01 | Admin can log in with username and password |
| AUTH-02 | System must lock out unauthenticated users from all `/admin` routes |
| AUTH-03 | Admin can change their own password from the Profile page |
| AUTH-04 | Session must regenerate ID after successful login |
| AUTH-05 | Admin can log out and session must be destroyed completely |

### 4.2 Pages & Page Sections

| ID | Requirement |
|---|---|
| PAGE-01 | Admin can edit content of fixed pages: Home, About, Contact |
| PAGE-02 | Each page contains multiple named sections (e.g. `hero`, `about_intro`) |
| PAGE-03 | Section content is editable via TinyMCE rich text editor |
| PAGE-04 | Admin can reorder sections via sort order field |
| PAGE-05 | Pages have `published` / `draft` status; draft pages are not visible on frontend |
| PAGE-06 | Each page has optional SEO title and SEO description fields |

### 4.3 Products

| ID | Requirement |
|---|---|
| PROD-01 | Admin can create, edit, delete products |
| PROD-02 | Each product has: name, slug, description, status |
| PROD-03 | Each product can have multiple images uploaded via Media Library |
| PROD-04 | Images can be reordered; first image is treated as thumbnail |
| PROD-05 | Each product can have an optional LINE chat URL |
| PROD-06 | Each product can have an optional QR code image (selected from Media Library) |
| PROD-07 | Each product has optional SEO title and SEO description |
| PROD-08 | Deleted products use soft delete (`deleted_at` timestamp) |
| PROD-09 | Product list in admin shows paginated results, sortable by name and date |

### 4.4 Portfolios

| ID | Requirement |
|---|---|
| PORT-01 | Admin can create, edit, delete portfolio items |
| PORT-02 | Each portfolio item has: title, slug, description, status |
| PORT-03 | Each portfolio item can have multiple images |
| PORT-04 | Images can be reordered; first image is treated as cover |
| PORT-05 | Each portfolio item has optional SEO title and SEO description |
| PORT-06 | Deleted portfolio items use soft delete |

### 4.5 Media Library

| ID | Requirement |
|---|---|
| MEDIA-01 | Admin can upload image files (JPEG, PNG, GIF, WebP) |
| MEDIA-02 | Maximum file size is 5 MB per upload |
| MEDIA-03 | Uploaded files are stored with randomized filenames |
| MEDIA-04 | Admin can set alt text for each media item |
| MEDIA-05 | Admin can delete media files (soft delete; file remains on disk) |
| MEDIA-06 | Media can be browsed and selected from a modal picker within product/portfolio forms |
| MEDIA-07 | Media library displays thumbnail grid with filename, size, and upload date |

### 4.6 Menus

| ID | Requirement |
|---|---|
| MENU-01 | Admin can create navigation menu items with title and URL |
| MENU-02 | Menu items can be set to open in same tab or new tab |
| MENU-03 | Menu items can be reordered via sort order |
| MENU-04 | Menu items can be activated or deactivated without deletion |

### 4.7 SEO Settings

| ID | Requirement |
|---|---|
| SEO-01 | Admin can set meta title and meta description per page |
| SEO-02 | Frontend renders `<title>` and `<meta name="description">` from database values |
| SEO-03 | If no SEO title is set, page title falls back to site name from settings |

### 4.8 Site Settings

| ID | Requirement |
|---|---|
| SET-01 | Admin can update: site name, site description |
| SET-02 | Admin can update: phone, email, LINE URL, Facebook URL, address |
| SET-03 | Admin can embed a Google Maps iframe code |
| SET-04 | Admin can set site logo and favicon from Media Library |
| SET-05 | Settings are stored as a single row (no multi-row key-value) |

### 4.9 Public Frontend

| ID | Requirement |
|---|---|
| FE-01 | Home page renders content from `pages` + `page_sections` table |
| FE-02 | Product list page shows all published products in a grid |
| FE-03 | Product detail page shows images (gallery), description, LINE button, QR code |
| FE-04 | Portfolio list page shows all published portfolio items in a grid |
| FE-05 | Portfolio detail page shows full image gallery |
| FE-06 | Contact page shows business info, embedded map, and LINE link from settings |
| FE-07 | Navigation menu renders from `menus` table |
| FE-08 | All pages render SEO meta tags from database |

---

## 5. Non-Functional Requirements

### 5.1 Performance

| ID | Requirement |
|---|---|
| PERF-01 | Admin pages must load within 3 seconds on localhost (XAMPP) |
| PERF-02 | Uploaded images should be stored without server-side resizing (client responsibility) |
| PERF-03 | Database queries must use indexed columns for filtering and sorting |

### 5.2 Usability

| ID | Requirement |
|---|---|
| USE-01 | Admin panel must be usable without developer assistance after onboarding |
| USE-02 | All forms must display clear validation error messages |
| USE-03 | Destructive actions (delete) must require confirmation dialog |
| USE-04 | Admin panel must be responsive on tablet and desktop |

### 5.3 Maintainability

| ID | Requirement |
|---|---|
| MAINT-01 | Code must follow PSR-12 formatting |
| MAINT-02 | Each PHP class must have a single, clear responsibility |
| MAINT-03 | No hardcoded credentials — all config via `config/database.php` |
| MAINT-04 | All database changes must have corresponding SQL migration files |

### 5.4 Reliability

| ID | Requirement |
|---|---|
| REL-01 | Soft delete must be used for all content (never hard delete from UI) |
| REL-02 | Foreign key constraints must be enforced at database level |
| REL-03 | Failed file uploads must not create orphaned database records |

---

## 6. Use Cases

### UC-01: Admin Logs In

```
Actor   : Admin
Goal    : Gain access to the admin panel
Trigger : Admin navigates to /admin/login

Main Flow:
  1. Admin enters username and password
  2. System validates credentials against admins table
  3. System calls password_verify() to check hash
  4. System regenerates session ID
  5. System redirects to /admin/dashboard

Alternative Flow (Wrong Password):
  3a. password_verify() returns false
  3b. System displays "Invalid username or password"
  3c. System does not reveal which field was wrong
```

### UC-02: Admin Creates a Product

```
Actor   : Admin
Goal    : Add a new product to the website
Trigger : Admin clicks "Add Product" in admin panel

Main Flow:
  1. Admin fills in name, description, LINE URL
  2. Admin selects images from Media Library modal
  3. Admin sets status to "published"
  4. Admin submits form
  5. System validates all fields (CSRF token, required fields, slug uniqueness)
  6. System inserts into products table
  7. System inserts into product_images table
  8. System redirects to product list with success message

Alternative Flow (Validation Fails):
  5a. Required field is missing
  5b. System redisplays form with error messages
  5c. Previously entered values are preserved
```

### UC-03: Admin Uploads an Image

```
Actor   : Admin
Goal    : Upload an image to the Media Library
Trigger : Admin clicks "Upload" in Media Library

Main Flow:
  1. Admin selects a file from local disk
  2. System checks MIME type (must be image/jpeg, image/png, image/gif, image/webp)
  3. System checks file size (must be ≤ 5 MB)
  4. System generates random filename
  5. System moves file to public/uploads/{year}/{month}/
  6. System inserts record into media table
  7. System displays new image in library grid

Alternative Flow (Invalid File):
  2a. MIME type not in allowlist
  2b. System returns error: "Only image files are allowed"
  3a. File size exceeds 5 MB
  3b. System returns error: "File size must not exceed 5 MB"
```

---

## 7. System Constraints

### 7.1 Technical Constraints

- Must run on **XAMPP** (PHP 8.3 + MySQL 8.0 + Apache)
- Must work on **Windows 10/11** local development environment
- No Composer dependency manager (dependencies managed manually or via CDN)
- No Node.js / npm build step
- AdminLTE and Bootstrap loaded via CDN or local copy

### 7.2 Business Constraints

- Must be maintainable by a **solo developer**
- No external paid services or APIs
- Deployment target: Thai shared hosting (cPanel)

---

## 8. Acceptance Criteria

The system is considered complete when all of the following are true:

- [ ] Admin can log in and log out successfully
- [ ] Admin can create, edit, and soft-delete products with images
- [ ] Admin can create, edit, and soft-delete portfolio items with images
- [ ] Admin can edit page section content via TinyMCE
- [ ] Admin can upload images and use them in products/portfolios
- [ ] Admin can manage menus, SEO settings, and site settings
- [ ] Public frontend renders all published content correctly
- [ ] All forms have server-side validation with clear error messages
- [ ] CSRF protection active on all state-changing forms
- [ ] No plain-text passwords stored in database
- [ ] All SQL queries use PDO prepared statements

---

*Document owner: First (Chakron) · Mae Klong Graphic Internship Project*
