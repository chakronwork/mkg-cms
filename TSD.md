# TSD — Technical Specification Document

**Project:** Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Draft

---

## Table of Contents

1. [Technology Stack](#1-technology-stack)
2. [Architecture](#2-architecture)
3. [Directory Structure](#3-directory-structure)
4. [Database Schema](#4-database-schema)
5. [Routing](#5-routing)
6. [Request Lifecycle](#6-request-lifecycle)
7. [Controller Conventions](#7-controller-conventions)
8. [Model Conventions](#8-model-conventions)
9. [View Conventions](#9-view-conventions)
10. [File Upload Pipeline](#10-file-upload-pipeline)
11. [Coding Standards](#11-coding-standards)
12. [Development Workflow](#12-development-workflow)
13. [Forbidden Patterns](#13-forbidden-patterns)

---

## 1. Technology Stack

| Layer | Technology | Version | Notes |
|---|---|---|---|
| Language | PHP | 8.3 | `strict_types=1` everywhere |
| Database | MySQL | 8.0 | `utf8mb4_unicode_ci` |
| DB Driver | PDO | — | Prepared statements only |
| Admin UI | AdminLTE | 3.x | Built on Bootstrap 5 |
| CSS Framework | Bootstrap | 5.3 | Via CDN |
| Rich Text Editor | TinyMCE | 6 | Self-hosted or CDN |
| Dev Environment | XAMPP | — | PHP 8.3 + Apache + MySQL |

---

## 2. Architecture

### 2.1 Pattern

Lightweight **MVC** implemented from scratch. No third-party framework. Separation of concerns:

- **Model** — Database access via PDO. One model class per database table/entity.
- **View** — PHP template files. No logic beyond simple loops and conditionals.
- **Controller** — Handles HTTP request, calls model, passes data to view.

### 2.2 Entry Point

All requests route through `public/index.php` (frontend) and `public/admin/index.php` (backend). A simple router maps URL paths to controller actions.

```
Browser → Apache → public/index.php → Router → Controller → Model → View → Response
```

### 2.3 Session & Auth Flow

```
Request to /admin/*
  └─ AuthMiddleware::check()
       ├─ Session valid? → Continue to controller
       └─ Session invalid? → Redirect to /admin/login
```

---

## 3. Directory Structure

```
sign-shop-cms/
│
├── app/
│   ├── controllers/
│   │   ├── admin/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── ProductController.php
│   │   │   ├── PortfolioController.php
│   │   │   ├── MediaController.php
│   │   │   ├── MenuController.php
│   │   │   ├── SettingsController.php
│   │   │   └── UserController.php
│   │   └── frontend/
│   │       ├── HomeController.php
│   │       ├── ProductController.php
│   │       ├── PortfolioController.php
│   │       └── ContactController.php
│   │
│   ├── models/
│   │   ├── AdminModel.php
│   │   ├── PageModel.php
│   │   ├── PageSectionModel.php
│   │   ├── ProductModel.php
│   │   ├── PortfolioModel.php
│   │   ├── MediaModel.php
│   │   ├── MenuModel.php
│   │   └── SettingsModel.php
│   │
│   └── views/
│       ├── admin/
│       │   ├── layouts/
│       │   │   └── main.php          ← AdminLTE shell
│       │   ├── auth/
│       │   │   └── login.php
│       │   ├── dashboard/
│       │   ├── pages/
│       │   ├── products/
│       │   │   ├── list.php
│       │   │   ├── create.php
│       │   │   └── edit.php
│       │   ├── portfolios/
│       │   ├── media/
│       │   ├── menus/
│       │   ├── settings/
│       │   └── users/
│       └── frontend/
│           ├── layouts/
│           │   └── main.php          ← Public site shell
│           ├── home.php
│           ├── about.php
│           ├── products/
│           ├── portfolio/
│           └── contact.php
│
├── config/
│   ├── database.php                  ← DB credentials (gitignored)
│   ├── database.example.php          ← Template committed to git
│   └── app.php                       ← Base URL, upload path, etc.
│
├── core/
│   ├── Router.php
│   ├── Controller.php                ← Base controller
│   ├── Model.php                     ← Base model (PDO wrapper)
│   ├── Auth.php                      ← Session helper
│   └── Csrf.php                      ← CSRF token helper
│
├── database/
│   └── schema.sql                    ← Full schema with CREATE TABLE
│
├── public/
│   ├── index.php                     ← Frontend entry point
│   ├── admin/
│   │   └── index.php                 ← Admin entry point
│   ├── css/
│   ├── js/
│   └── uploads/                      ← Writable. Gitignored.
│
└── docs/
    ├── README.md
    ├── SRS.md
    ├── TSD.md
    └── SECURITY.md
```

---

## 4. Database Schema

### Conventions

- Primary keys: `INT UNSIGNED AUTO_INCREMENT`
- Soft delete: `deleted_at DATETIME NULL` on all content tables (`NULL` = active)
- Charset: `utf8mb4` / collation `utf8mb4_unicode_ci` on every table and column
- Timestamps: managed by application, not MySQL triggers

### 4.1 `admins`

```sql
CREATE TABLE admins (
  id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  username     VARCHAR(80)     NOT NULL,
  password_hash VARCHAR(255)   NOT NULL,
  full_name    VARCHAR(150)    NOT NULL,
  created_at   DATETIME        NOT NULL,
  updated_at   DATETIME        NOT NULL,
  deleted_at   DATETIME        NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admins_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.2 `pages`

```sql
CREATE TABLE pages (
  id               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  title            VARCHAR(255)  NOT NULL,
  slug             VARCHAR(255)  NOT NULL,
  seo_title        VARCHAR(255)  NULL,
  seo_description  TEXT          NULL,
  status           ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at       DATETIME      NOT NULL,
  updated_at       DATETIME      NOT NULL,
  deleted_at       DATETIME      NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.3 `page_sections`

```sql
CREATE TABLE page_sections (
  id           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  page_id      INT UNSIGNED   NOT NULL,
  section_key  VARCHAR(100)   NOT NULL,
  section_name VARCHAR(150)   NOT NULL,
  content      LONGTEXT       NULL,
  sort_order   SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at   DATETIME       NOT NULL,
  updated_at   DATETIME       NOT NULL,
  PRIMARY KEY (id),
  KEY idx_page_sections_page_id (page_id),
  CONSTRAINT fk_page_sections_page FOREIGN KEY (page_id)
    REFERENCES pages (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.4 `media`

```sql
CREATE TABLE media (
  id         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  filename   VARCHAR(255)   NOT NULL,
  filepath   VARCHAR(512)   NOT NULL,
  mime_type  VARCHAR(100)   NOT NULL,
  alt_text   VARCHAR(255)   NULL,
  file_size  INT UNSIGNED   NOT NULL,
  created_at DATETIME       NOT NULL,
  deleted_at DATETIME       NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.5 `products`

```sql
CREATE TABLE products (
  id                INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  name              VARCHAR(255)  NOT NULL,
  slug              VARCHAR(255)  NOT NULL,
  description       TEXT          NULL,
  line_url          VARCHAR(512)  NULL,
  qr_code_media_id  INT UNSIGNED  NULL,
  seo_title         VARCHAR(255)  NULL,
  seo_description   TEXT          NULL,
  status            ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at        DATETIME      NOT NULL,
  updated_at        DATETIME      NOT NULL,
  deleted_at        DATETIME      NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_products_slug (slug),
  CONSTRAINT fk_products_qr_media FOREIGN KEY (qr_code_media_id)
    REFERENCES media (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.6 `product_images`

```sql
CREATE TABLE product_images (
  id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  product_id  INT UNSIGNED     NOT NULL,
  media_id    INT UNSIGNED     NOT NULL,
  sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_product_images_product (product_id),
  CONSTRAINT fk_product_images_product FOREIGN KEY (product_id)
    REFERENCES products (id) ON DELETE CASCADE,
  CONSTRAINT fk_product_images_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.7 `portfolios`

```sql
CREATE TABLE portfolios (
  id               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  title            VARCHAR(255)  NOT NULL,
  slug             VARCHAR(255)  NOT NULL,
  description      TEXT          NULL,
  seo_title        VARCHAR(255)  NULL,
  seo_description  TEXT          NULL,
  status           ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at       DATETIME      NOT NULL,
  updated_at       DATETIME      NOT NULL,
  deleted_at       DATETIME      NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_portfolios_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.8 `portfolio_images`

```sql
CREATE TABLE portfolio_images (
  id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  portfolio_id  INT UNSIGNED     NOT NULL,
  media_id      INT UNSIGNED     NOT NULL,
  sort_order    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  CONSTRAINT fk_portfolio_images_portfolio FOREIGN KEY (portfolio_id)
    REFERENCES portfolios (id) ON DELETE CASCADE,
  CONSTRAINT fk_portfolio_images_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.9 `menus`

```sql
CREATE TABLE menus (
  id         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  title      VARCHAR(150)     NOT NULL,
  url        VARCHAR(512)     NOT NULL,
  target     ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1)       NOT NULL DEFAULT 1,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.10 `settings`

```sql
CREATE TABLE settings (
  id                INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  site_name         VARCHAR(255)  NOT NULL,
  site_description  TEXT          NULL,
  phone             VARCHAR(30)   NULL,
  email             VARCHAR(255)  NULL,
  line_url          VARCHAR(512)  NULL,
  facebook_url      VARCHAR(512)  NULL,
  address           TEXT          NULL,
  google_map_embed  TEXT          NULL,
  logo_media_id     INT UNSIGNED  NULL,
  favicon_media_id  INT UNSIGNED  NULL,
  updated_at        DATETIME      NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_settings_logo    FOREIGN KEY (logo_media_id)    REFERENCES media (id) ON DELETE SET NULL,
  CONSTRAINT fk_settings_favicon FOREIGN KEY (favicon_media_id) REFERENCES media (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. Routing

### 5.1 Frontend Routes

| Method | Path | Controller | Action |
|---|---|---|---|
| GET | `/` | `HomeController` | `index` |
| GET | `/about` | `HomeController` | `about` |
| GET | `/products` | `ProductController` | `index` |
| GET | `/products/{slug}` | `ProductController` | `show` |
| GET | `/portfolio` | `PortfolioController` | `index` |
| GET | `/portfolio/{slug}` | `PortfolioController` | `show` |
| GET | `/contact` | `ContactController` | `index` |

### 5.2 Admin Routes

| Method | Path | Controller | Action |
|---|---|---|---|
| GET/POST | `/admin/login` | `AuthController` | `login` |
| POST | `/admin/logout` | `AuthController` | `logout` |
| GET | `/admin` | `DashboardController` | `index` |
| GET | `/admin/products` | `ProductController` | `index` |
| GET/POST | `/admin/products/create` | `ProductController` | `create` |
| GET/POST | `/admin/products/{id}/edit` | `ProductController` | `edit` |
| POST | `/admin/products/{id}/delete` | `ProductController` | `delete` |
| GET/POST | `/admin/media` | `MediaController` | `index` |
| POST | `/admin/media/upload` | `MediaController` | `upload` |
| POST | `/admin/media/{id}/delete` | `MediaController` | `delete` |

> Portfolio, Pages, Menus, Settings follow the same pattern.

---

## 6. Request Lifecycle

```
1. Browser sends request
2. Apache routes to public/index.php (or public/admin/index.php)
3. Router parses URI → matches controller/action
4. [Admin only] AuthMiddleware checks $_SESSION['admin_id']
5. Controller instantiates, calls action method
6. Action validates CSRF token (POST requests)
7. Action calls Model methods (PDO prepared statements)
8. Model returns data array
9. Controller passes data to View via require
10. View renders HTML with htmlspecialchars() on all output
11. Response sent to browser
```

---

## 7. Controller Conventions

```php
<?php

declare(strict_types=1);

class ProductController extends Controller
{
    private ProductModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new ProductModel();
    }

    public function index(): void
    {
        $products = $this->model->getAllActive();
        $this->view('admin/products/list', ['products' => $products]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $data = $this->validateProductInput($_POST);
            $this->model->create($data);
            $this->redirect('/admin/products');
        }
        $this->view('admin/products/create');
    }
}
```

**Rules:**
- One controller per resource
- Constructor injects the corresponding model
- `POST` handlers always call `$this->verifyCsrf()` first
- Never echo output directly — always use `$this->view()`
- Redirect after successful POST (PRG pattern)

---

## 8. Model Conventions

```php
<?php

declare(strict_types=1);

class ProductModel extends Model
{
    public function getAllActive(): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM products WHERE id = :id AND deleted_at IS NULL'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET deleted_at = NOW() WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
    }
}
```

**Rules:**
- Always use `WHERE deleted_at IS NULL` in SELECT queries
- Never concatenate user values into SQL
- Always use named placeholders (`:name`) not positional (`?`)
- Return typed values — arrays for lists, `array|false` for single row

---

## 9. View Conventions

```php
<!-- All user output must be escaped -->
<h1><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h1>

<!-- CSRF token in every form -->
<form method="POST" action="/admin/products/create">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    <!-- fields -->
    <button type="submit">Save</button>
</form>
```

**Rules:**
- No business logic in views — only loops and simple conditionals
- Every output of user-supplied data uses `htmlspecialchars()`
- Every POST form includes a CSRF hidden input
- TinyMCE areas are not escaped (they render trusted HTML from admin)

---

## 10. File Upload Pipeline

```
1. Client submits file via multipart/form-data
2. PHP checks $_FILES['file']['error'] === UPLOAD_ERR_OK
3. Check file size: $_FILES['file']['size'] <= 5_242_880 (5 MB)
4. Detect MIME type with finfo_file() — do NOT trust $_FILES['type']
5. Validate MIME against allowlist: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
6. Generate safe filename: bin2hex(random_bytes(16)) . '.' . $ext
7. Build destination path: public/uploads/{Y}/{m}/
8. Create directory if not exists (mkdir with 0755)
9. move_uploaded_file() to destination
10. Insert record into media table with original filename, stored path, mime, size
```

---

## 11. Coding Standards

### 11.1 PHP

- `declare(strict_types=1)` at top of every file
- PSR-12 formatting (4-space indent, opening brace on same line for classes/methods)
- Class names: `PascalCase`
- Method/function names: `camelCase`
- Variable names: `camelCase`
- Constants: `UPPER_SNAKE_CASE`
- No `@` error suppression operator

### 11.2 Database

- Table names: `snake_case`, plural (e.g. `products`, `page_sections`)
- Column names: `snake_case` (e.g. `created_at`, `qr_code_media_id`)
- All queries use named PDO placeholders
- Index name convention: `idx_{table}_{column}`, FK: `fk_{table}_{column}`

### 11.3 Files & URLs

- View files: `snake_case.php`
- URL slugs: `kebab-case`, ASCII only, max 200 chars
- Upload filenames: hex random string, never original name on disk

---

## 12. Development Workflow

For every feature, complete **all steps in order**:

1. **Define** — describe scope and expected behavior in a comment or note
2. **SQL** — write migration if new tables/columns are needed
3. **Model** — implement data access methods with full type hints
4. **Controller** — handle request, call model, validate input
5. **View** — template with escaping and CSRF token
6. **Validation** — server-side rules, error messages
7. **Test** — manual walkthrough of happy path and error paths
8. **Review** — check security checklist before marking complete

### Security Checklist (per feature)

- [ ] CSRF token verified on every POST
- [ ] All output escaped with `htmlspecialchars()`
- [ ] No raw user input in SQL
- [ ] Auth check present on admin routes
- [ ] File uploads validated by MIME type (not extension alone)

---

## 13. Forbidden Patterns

The following are explicitly **not used** in this project unless the developer explicitly requests and justifies them:

| Pattern / Tool | Reason Excluded |
|---|---|
| Laravel / Symfony | Overkill for solo SME project; XAMPP deployment |
| Composer autoloader | Kept simple; manual require for now |
| Repository Pattern | Extra abstraction layer without benefit at this scale |
| Service Layer | Same as above |
| CQRS / Event Bus | Massively over-engineered for a CMS |
| Dependency Injection Container | Unnecessary complexity |
| GraphQL | No API consumers |
| ORM (Eloquent, Doctrine) | Direct PDO is more transparent and debuggable |
| `.env` file parsing | `config/database.php` is sufficient for XAMPP |

---

*Document owner: First (Chakron) · Mae Klong Graphic Internship Project*
