# MKG-CMS Visual System Map

**Project:** Sign Shop CMS  
**Date:** 2026-06-02

---

## System Architecture Visualization

```
┌─────────────────────────────────────────────────────────────────────┐
│                         MKG-CMS SYSTEM                               │
│                     Sign Shop Content Management                     │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────┐
│                          FRONTEND LAYER                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌──────────────────────┐          ┌──────────────────────┐        │
│  │   PUBLIC WEBSITE     │          │    ADMIN PANEL       │        │
│  ├──────────────────────┤          ├──────────────────────┤        │
│  │ • Home               │          │ • Dashboard          │        │
│  │ • About              │          │ • Pages Manager      │        │
│  │ • Products           │          │ • Products CRUD      │        │
│  │ • Portfolio          │          │ • Portfolios CRUD    │        │
│  │ • Contact            │          │ • Media Library      │        │
│  │                      │          │ • Menu Manager       │        │
│  │ Bootstrap 5          │          │ • Settings           │        │
│  │ Responsive Design    │          │ AdminLTE 3           │        │
│  └──────────────────────┘          └──────────────────────┘        │
│           ↓                                   ↓                     │
└───────────┼───────────────────────────────────┼─────────────────────┘
            │                                   │
            │                                   │
┌───────────┼───────────────────────────────────┼─────────────────────┐
│           ↓                 ROUTER            ↓                     │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  public/index.php              public/admin/index.php               │
│      │                                  │                            │
│      ├─→ HomeController                 ├─→ AuthController          │
│      ├─→ FrontendProductController      ├─→ DashboardController     │
│      ├─→ FrontendPortfolioController    ├─→ AdminProductController  │
│      └─→ ContactController              ├─→ AdminPortfolioController│
│                                         ├─→ AdminPageController     │
│                                         ├─→ MediaController         │
│                                         ├─→ MenuController          │
│                                         └─→ SettingsController      │
│                                                                      │
└───────────┼───────────────────────────────────┼─────────────────────┘
            │                                   │
            │         CONTROLLER LAYER          │
            │                                   │
┌───────────┼───────────────────────────────────┼─────────────────────┐
│           ↓                                   ↓                     │
├─────────────────────────────────────────────────────────────────────┤
│                          CORE SERVICES                               │
│                                                                      │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐                │
│  │    Auth     │  │    CSRF     │  │   Router    │                │
│  └─────────────┘  └─────────────┘  └─────────────┘                │
│                                                                      │
└───────────┼───────────────────────────────────┼─────────────────────┘
            │                                   │
            │           MODEL LAYER             │
            │                                   │
┌───────────┼───────────────────────────────────┼─────────────────────┐
│           ↓                                   ↓                     │
├─────────────────────────────────────────────────────────────────────┤
│                              MODELS                                  │
│                                                                      │
│  PageModel          ProductModel        PortfolioModel              │
│  AdminModel         MediaModel          MenuModel                   │
│  SettingsModel                                                       │
│                                                                      │
│  All extend: Base Model (PDO + Prepared Statements)                 │
│                                                                      │
└───────────┼───────────────────────────────────┼─────────────────────┘
            │                                   │
            │         DATABASE LAYER            │
            │                                   │
┌───────────┼───────────────────────────────────┼─────────────────────┐
│           ↓                                   ↓                     │
├─────────────────────────────────────────────────────────────────────┤
│                         MySQL 8.0 DATABASE                           │
│                                                                      │
│  admins              products              portfolios                │
│  pages               product_images        portfolio_images          │
│  page_sections       media                 menus                    │
│  settings                                                            │
│                                                                      │
│  Character Set: utf8mb4_unicode_ci                                   │
│  Storage Engine: InnoDB                                              │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Data Flow Visualization

```
┌─────────────────────────────────────────────────────────────────────┐
│                       REQUEST → RESPONSE FLOW                        │
└─────────────────────────────────────────────────────────────────────┘

USER INPUT (Browser)
     │
     │ HTTP Request (GET/POST)
     │
     ↓
┌─────────────────┐
│  Entry Point    │ ← public/index.php or public/admin/index.php
│  - Bootstrap    │
│  - Session      │
│  - Config       │
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  Router Match   │ ← Pattern matching, extract params
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  Auth Check     │ ← Admin routes only
│  (if admin)     │
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  Controller     │ ← Instantiate and call action
│  Action         │
└────────┬────────┘
         │
         ├──→ GET Request ───────────────────────────┐
         │                                           │
         │   1. Load data from models                │
         │   2. Prepare view variables               │
         │   3. Render view template                 │
         │   4. Return HTML                          │
         │                                           │
         └──→ POST Request ──────────────────────────┤
                                                     │
             1. Validate CSRF token                  │
             2. Validate input data                  │
             3. Call model methods                   │
             4. Redirect (PRG pattern)               │
             5. Flash message                        │
                                                     │
                     ↓                               ↓
┌─────────────────────────────────────────────────────────────┐
│  Model Layer                                                 │
│  - Build SQL queries                                         │
│  - PDO prepared statements                                   │
│  - Execute and return results                                │
└────────┬─────────────────────────────────────────────────────┘
         │
         ↓
┌─────────────────┐
│  Database       │ ← MySQL query execution
└────────┬────────┘
         │
         ↓
┌─────────────────┐
│  View Render    │ ← app/views/* with data
│  - Escape output│
│  - Generate HTML│
└────────┬────────┘
         │
         ↓
     RESPONSE (HTML to Browser)
```

---

## Security Flow Map

```
┌─────────────────────────────────────────────────────────────────────┐
│                         SECURITY LAYERS                              │
└─────────────────────────────────────────────────────────────────────┘

INPUT ────────────────────────────────────────────────┐
  │                                                    │
  ├─→ CSRF Protection                                 │
  │   ├─ Token generation                             │
  │   ├─ Token validation                             │
  │   └─ Session-based storage                        │
  │                                                    │
  ├─→ Authentication                                   │
  │   ├─ Password hashing (bcrypt)                    │
  │   ├─ Session management                           │
  │   └─ Session regeneration                         │
  │                                                    │
  ├─→ Input Validation                                │
  │   ├─ Required field checks                        │
  │   ├─ Format validation                            │
  │   ├─ Type checking                                │
  │   └─ Length limits                                │
  │                                                    │
  ├─→ SQL Injection Prevention                        │
  │   ├─ PDO prepared statements                      │
  │   ├─ Parameter binding                            │
  │   └─ No dynamic SQL                               │
  │                                                    │
  ├─→ File Upload Security                            │
  │   ├─ MIME type detection (finfo)                  │
  │   ├─ Size limit enforcement                       │
  │   ├─ Random filename generation                   │
  │   ├─ .htaccess PHP blocking                       │
  │   └─ Whitelist validation                         │
  │                                                    │
  └─→ Output Escaping                                 │
      ├─ htmlspecialchars() via e()                   │
      ├─ Context-aware escaping                       │
      └─ No raw output                                │
                                                       │
OUTPUT ←───────────────────────────────────────────────┘
```

---

## Feature Module Map

```
┌─────────────────────────────────────────────────────────────────────┐
│                         FEATURE MODULES                              │
└─────────────────────────────────────────────────────────────────────┘

┌──────────────────────┐
│   AUTHENTICATION     │
├──────────────────────┤
│ • Login/Logout       │
│ • Session Management │
│ • Password Verify    │
└──────────────────────┘

┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────────┐
│   PAGES MANAGER      │  │  PRODUCTS MODULE     │  │  PORTFOLIOS MODULE   │
├──────────────────────┤  ├──────────────────────┤  ├──────────────────────┤
│ • Fixed Pages        │  │ • CRUD Operations    │  │ • CRUD Operations    │
│ • Sections Editor    │  │ • Image Gallery      │  │ • Image Gallery      │
│ • SEO Metadata       │  │ • LINE Integration   │  │ • SEO Metadata       │
│ • TinyMCE Editor     │  │ • QR Code Support    │  │ • Public Listing     │
│                      │  │ • SEO Metadata       │  │ • Detail Pages       │
│                      │  │ • Public Listing     │  │                      │
│                      │  │ • Detail Pages       │  │                      │
└──────────────────────┘  └──────────────────────┘  └──────────────────────┘

┌──────────────────────┐  ┌──────────────────────┐  ┌──────────────────────┐
│   MEDIA LIBRARY      │  │   MENU MANAGER       │  │   SITE SETTINGS      │
├──────────────────────┤  ├──────────────────────┤  ├──────────────────────┤
│ • Upload Images      │  │ • Add/Edit/Delete    │  │ • Site Info          │
│ • MIME Validation    │  │ • Drag-to-Reorder    │  │ • Contact Details    │
│ • Alt Text           │  │ • Active/Inactive    │  │ • Social Media URLs  │
│ • Media Picker       │  │ • Target Control     │  │ • Logo/Favicon       │
│ • Soft Delete        │  │                      │  │ • Google Maps        │
└──────────────────────┘  └──────────────────────┘  └──────────────────────┘
```

---

## Technology Stack Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                         TECHNOLOGY STACK                             │
└─────────────────────────────────────────────────────────────────────┘

FRONTEND
  ├─ HTML5
  ├─ CSS3
  ├─ Bootstrap 5 (Public)
  ├─ AdminLTE 3 (Admin)
  ├─ JavaScript (ES6+)
  ├─ jQuery
  ├─ TinyMCE (WYSIWYG)
  └─ Font Awesome (Icons)

BACKEND
  ├─ PHP 8.1+
  ├─ Custom MVC Framework
  ├─ PDO (Database)
  ├─ Session Management
  └─ File Upload Handling

DATABASE
  ├─ MySQL 8.0+
  ├─ InnoDB Engine
  ├─ utf8mb4_unicode_ci
  └─ Foreign Key Constraints

DEVELOPMENT
  ├─ XAMPP (Local Server)
  ├─ Git (Version Control)
  └─ VSCode / PhpStorm

DEPLOYMENT
  ├─ Apache 2.4+
  ├─ mod_rewrite
  ├─ HTTPS/SSL
  └─ PHP-FPM (recommended)
```

---

## File Structure Tree

```
mkg-cms/
│
├─ app/
│  ├─ controllers/
│  │  ├─ admin/           (8 controllers)
│  │  └─ frontend/        (4 controllers)
│  ├─ models/             (7 models)
│  └─ views/
│     ├─ admin/           (AdminLTE templates)
│     └─ frontend/        (Bootstrap templates)
│
├─ config/
│  ├─ app.php            (Configuration)
│  └─ database.php       (DB credentials)
│
├─ core/
│  ├─ Auth.php           (Authentication)
│  ├─ Controller.php     (Base controller)
│  ├─ Csrf.php           (CSRF protection)
│  ├─ Database.php       (PDO connection)
│  ├─ Model.php          (Base model)
│  └─ Router.php         (Simple router)
│
├─ database/
│  └─ schema.sql         (Complete schema)
│
├─ public/
│  ├─ admin/
│  │  └─ index.php       (Admin entry)
│  ├─ uploads/           (Media storage)
│  │  └─ {year}/{month}/ (Organized structure)
│  └─ index.php          (Public entry)
│
└─ [documentation]/       (11 markdown files)
```

---

## Workflow Diagram: Complete CRUD Cycle

```
CREATE
  │
  ├─→ Click "Add New" ─────→ GET /admin/products/create
  │                          Load empty form
  │
  ├─→ Fill form ───────────→ JavaScript validation
  │                          Auto-generate slug
  │                          Media picker integration
  │
  └─→ Submit ──────────────→ POST /admin/products/create
                             CSRF validation
                             Server-side validation
                             Insert to database
                             Redirect to list

READ
  │
  ├─→ List view ───────────→ GET /admin/products
  │                          Query paginated results
  │                          Display table
  │
  └─→ Detail view ─────────→ GET /products/{slug}
                             Query by slug
                             Load related images
                             Render detail page

UPDATE
  │
  ├─→ Click "Edit" ────────→ GET /admin/products/{id}/edit
  │                          Load existing data
  │                          Populate form fields
  │
  ├─→ Modify data ─────────→ JavaScript validation
  │                          Update preview
  │
  └─→ Submit ──────────────→ POST /admin/products/{id}/edit
                             CSRF validation
                             Validate changes
                             Update database
                             Redirect to list

DELETE
  │
  ├─→ Click "Delete" ──────→ JavaScript confirm dialog
  │
  └─→ Confirm ─────────────→ POST /admin/products/{id}/delete
                             CSRF validation
                             Soft delete (set deleted_at)
                             Redirect to list
```

---

*This visual system map provides a comprehensive overview of the MKG-CMS architecture, data flows, and component relationships.*

**Related Documents:**
- CLAUDE.md – Detailed technical guide
- FRONTEND-FLOW.md – Complete user flow documentation
- DOCUMENTATION-INDEX.md – Documentation navigation guide
