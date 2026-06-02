# Frontend and UX-UI Pro Flow Documentation

**Project:** mkg-cms / Sign Shop CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Status:** Current

---

## 1. Overview

This document defines the complete frontend user experience flow for both the public website and admin panel, including CLI commands, API routes, backend processing, and data flow.

---

## 2. Public Website Flow

### 2.1 Home Page Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Visit homepage                                  │
│ URL: /                                                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ CLI: Browser sends GET request                               │
│ Request: GET /mkg-cms/public/                                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ API Route: public/index.php                                  │
│ Router: GET / → HomeController::index                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: HomeController::index()                             │
│ 1. Load PageModel                                            │
│ 2. Query: findBySlug('home')                                 │
│ 3. Query: findSections(page_id)                              │
│ 4. Load SettingsModel::get()                                 │
│ 5. Load MenuModel::getActive()                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ SELECT * FROM pages WHERE slug='home' AND deleted_at IS NULL │
│ SELECT * FROM page_sections WHERE page_id=? ORDER BY sort   │
│ SELECT * FROM settings WHERE id=1                            │
│ SELECT * FROM menus WHERE is_active=1 ORDER BY sort_order   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: Render View                                         │
│ File: app/views/frontend/home.php                            │
│ Layout: app/views/frontend/layouts/main.php                  │
│ Data: page, sections, settings, menus                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: HTML Response                                      │
│ - Bootstrap 5 responsive layout                              │
│ - Navigation menu from database                              │
│ - Hero section with editable content                         │
│ - SEO meta tags from page data                               │
│ - Social media links from settings                           │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Product Listing Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Products" menu                           │
│ URL: /products                                                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ API Route: GET /products                                     │
│ Router: FrontendProductController::index                     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: FrontendProductController::index()                  │
│ 1. Load ProductModel                                         │
│ 2. Query: findPublished()                                    │
│ 3. For each product: load first image                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ SELECT p.* FROM products p                                   │
│   WHERE p.status='published' AND p.deleted_at IS NULL        │
│   ORDER BY p.created_at DESC                                 │
│                                                              │
│ SELECT m.* FROM media m                                      │
│   JOIN product_images pi ON m.id = pi.media_id              │
│   WHERE pi.product_id=? AND m.deleted_at IS NULL             │
│   ORDER BY pi.sort_order LIMIT 1                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Product Grid Display                               │
│ - Bootstrap card grid (3 columns)                            │
│ - Product thumbnail image                                    │
│ - Product name and excerpt                                   │
│ - "View Details" link to /products/{slug}                    │
└─────────────────────────────────────────────────────────────┘
```

### 2.3 Product Detail Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click product card                              │
│ URL: /products/{slug}                                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ API Route: GET /products/{slug}                              │
│ Router: FrontendProductController::show($slug)               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: FrontendProductController::show($slug)              │
│ 1. Query: ProductModel::findBySlug($slug)                    │
│ 2. If not found or unpublished: return 404                   │
│ 3. Query: ProductModel::findImages($id)                      │
│ 4. Query: MediaModel::findById(qr_code_media_id)             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ SELECT * FROM products                                       │
│   WHERE slug=? AND status='published' AND deleted_at IS NULL │
│                                                              │
│ SELECT m.* FROM media m                                      │
│   JOIN product_images pi ON m.id = pi.media_id              │
│   WHERE pi.product_id=? AND m.deleted_at IS NULL             │
│   ORDER BY pi.sort_order                                     │
│                                                              │
│ SELECT * FROM media WHERE id=? AND deleted_at IS NULL        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Product Detail Display                             │
│ - Image gallery with thumbnails                              │
│ - Product name and full description                          │
│ - LINE contact button (if line_url set)                      │
│ - QR code image (if qr_code_media_id set)                    │
│ - SEO meta tags (title, description)                         │
│ - Breadcrumb navigation                                      │
└─────────────────────────────────────────────────────────────┘
```

### 2.4 Portfolio Flow

```
┌─────────────────────────────────────────────────────────────┐
│ Portfolio Listing: /portfolio                                 │
│ Same pattern as products:                                    │
│ - Grid display of published portfolio items                  │
│ - First image as thumbnail                                   │
│ - Link to /portfolio/{slug}                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Portfolio Detail: /portfolio/{slug}                           │
│ Same pattern as product detail:                              │
│ - Image gallery                                              │
│ - Title and description                                      │
│ - SEO metadata                                               │
└─────────────────────────────────────────────────────────────┘
```

### 2.5 Contact Page Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Visit contact page                              │
│ URL: /contact                                                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: ContactController::index()                          │
│ 1. Load PageModel::findBySlug('contact')                     │
│ 2. Load SettingsModel::get()                                 │
│ 3. Prepare contact information                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend Display:                                            │
│ - Contact information (phone, email, address)                │
│ - Social media links (LINE, Facebook)                        │
│ - Google Maps embed                                          │
│ - Page sections (editable content)                           │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Admin Panel Flow

### 3.1 Admin Login Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Visit admin panel                               │
│ URL: /admin                                                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: Auth Middleware Check                               │
│ If (!Auth::check()) redirect to /admin/login                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Login Form (admin/auth/login.php)                  │
│ Fields:                                                      │
│ - Username (text input)                                      │
│ - Password (password input)                                  │
│ - CSRF token (hidden)                                        │
│ - Submit button                                              │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Submit login form                               │
│ Method: POST /admin/login                                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AuthController::login()                             │
│ 1. Validate CSRF token                                       │
│ 2. Validate username and password not empty                  │
│ 3. Query: AdminModel::findByUsername($username)              │
│ 4. Verify password with password_verify()                    │
│ 5. If valid: Auth::login($admin)                             │
│ 6. session_regenerate_id(true)                               │
│ 7. Redirect to /admin                                        │
│ 8. If invalid: show error, redisplay form                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ SELECT * FROM admins                                         │
│   WHERE username=? AND deleted_at IS NULL                    │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Admin Dashboard Flow

```
┌─────────────────────────────────────────────────────────────┐
│ Successful Login: Redirect to /admin                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: DashboardController::index()                        │
│ 1. Load summary statistics (optional)                        │
│ 2. Recent activity (optional)                                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: AdminLTE Dashboard                                 │
│ Layout: admin/layouts/main.php                               │
│ - Top navbar with logout button                              │
│ - Left sidebar with navigation menu                          │
│ - Main content area with widgets                             │
│ - Footer                                                     │
│                                                              │
│ Sidebar Menu:                                                │
│ - Dashboard                                                  │
│ - Pages                                                      │
│ - Products                                                   │
│ - Portfolios                                                 │
│ - Media Library                                              │
│ - Menus                                                      │
│ - Settings                                                   │
│ - Logout                                                     │
└─────────────────────────────────────────────────────────────┘
```

### 3.3 Product Management Flow

#### 3.3.1 Product List View

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Products" in sidebar                     │
│ URL: /admin/products                                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::index()                     │
│ 1. Get page number from query string (?page=1)               │
│ 2. Query: ProductModel::paginate($page, 10)                  │
│ 3. Count total products                                      │
│ 4. Calculate pagination                                      │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ SELECT * FROM products                                       │
│   WHERE deleted_at IS NULL                                   │
│   ORDER BY created_at DESC                                   │
│   LIMIT 10 OFFSET ?                                          │
│                                                              │
│ SELECT COUNT(*) FROM products WHERE deleted_at IS NULL       │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Product List Table                                 │
│ View: admin/products/list.php                                │
│ - "Add New Product" button                                   │
│ - Table columns: ID, Name, Slug, Status, Actions             │
│ - Actions: Edit button, Delete button                        │
│ - Pagination controls                                        │
│ - Status badge (Published/Draft)                             │
└─────────────────────────────────────────────────────────────┘
```

#### 3.3.2 Create Product Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Add New Product"                         │
│ URL: /admin/products/create                                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::create() [GET]              │
│ 1. Generate CSRF token                                       │
│ 2. Load media library for picker                             │
│ 3. Render empty form                                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Product Form                                       │
│ View: admin/products/form.php                                │
│                                                              │
│ Form Fields:                                                 │
│ - Name* (text input)                                         │
│ - Slug* (text input, auto-generated from name)               │
│ - Description (textarea, plain text)                         │
│ - LINE URL (text input)                                      │
│ - QR Code (media picker, single select)                      │
│ - Product Images (media picker, multi-select)                │
│ - Status (radio: Published/Draft)                            │
│ - SEO Title (text input)                                     │
│ - SEO Description (textarea)                                 │
│ - CSRF Token (hidden)                                        │
│ - Submit Button                                              │
│                                                              │
│ JavaScript Features:                                         │
│ - Auto-generate slug from name (kebab-case)                  │
│ - Media picker modal                                         │
│ - Image preview and reorder                                  │
│ - Form validation                                            │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Fill form and submit                            │
│ Method: POST /admin/products/create                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::create() [POST]             │
│ 1. Validate CSRF token (or return 403)                       │
│ 2. Validate required fields:                                 │
│    - name not empty                                          │
│    - slug not empty, unique, valid format                    │
│ 3. Sanitize and prepare data                                 │
│ 4. Begin database transaction                                │
│ 5. ProductModel::create($data) → get product_id              │
│ 6. Parse media_ids and save to product_images                │
│ 7. Commit transaction                                        │
│ 8. Redirect to /admin/products (PRG pattern)                 │
│                                                              │
│ On Validation Error:                                         │
│ - Redisplay form with errors                                 │
│ - Preserve submitted values                                  │
│ - Show error messages                                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ -- Check slug uniqueness                                     │
│ SELECT COUNT(*) FROM products                                │
│   WHERE slug=? AND deleted_at IS NULL AND id != ?            │
│                                                              │
│ -- Insert product                                            │
│ INSERT INTO products                                         │
│   (name, slug, description, line_url, qr_code_media_id,      │
│    status, seo_title, seo_description,                       │
│    created_at, updated_at)                                   │
│ VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())                │
│                                                              │
│ -- Insert product images                                     │
│ INSERT INTO product_images                                   │
│   (product_id, media_id, sort_order)                         │
│ VALUES (?, ?, ?), (?, ?, ?), ...                             │
└─────────────────────────────────────────────────────────────┘
```

#### 3.3.3 Edit Product Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Edit" button in product list             │
│ URL: /admin/products/{id}/edit                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::edit($id) [GET]             │
│ 1. Query: ProductModel::findById($id)                        │
│ 2. If not found: return 404                                  │
│ 3. Query: ProductModel::findImages($id)                      │
│ 4. Prepare form with existing values                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Product Form (pre-filled)                          │
│ - Same form as create                                        │
│ - Fields populated with existing data                        │
│ - Selected images shown in preview                           │
│ - Submit button says "Update Product"                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Modify and submit                               │
│ Method: POST /admin/products/{id}/edit                       │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::edit($id) [POST]            │
│ 1. Validate CSRF token                                       │
│ 2. Validate fields (same as create)                          │
│ 3. Check slug uniqueness (exclude current product)           │
│ 4. Begin transaction                                         │
│ 5. ProductModel::update($id, $data)                          │
│ 6. Delete old product_images for this product                │
│ 7. Insert new product_images from media_ids                  │
│ 8. Commit transaction                                        │
│ 9. Redirect to /admin/products                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ UPDATE products SET                                          │
│   name=?, slug=?, description=?, line_url=?,                 │
│   qr_code_media_id=?, status=?,                              │
│   seo_title=?, seo_description=?, updated_at=NOW()           │
│ WHERE id=? AND deleted_at IS NULL                            │
│                                                              │
│ DELETE FROM product_images WHERE product_id=?                │
│ INSERT INTO product_images (product_id, media_id, sort) ...  │
└─────────────────────────────────────────────────────────────┘
```

#### 3.3.4 Delete Product Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Delete" button                           │
│ JavaScript: Confirm dialog "Are you sure?"                   │
│ Method: POST /admin/products/{id}/delete                     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminProductController::delete($id)                 │
│ 1. Validate CSRF token                                       │
│ 2. ProductModel::softDelete($id)                             │
│ 3. Redirect to /admin/products                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ UPDATE products SET deleted_at=NOW()                         │
│ WHERE id=? AND deleted_at IS NULL                            │
└─────────────────────────────────────────────────────────────┘
```

### 3.4 Media Library Flow

#### 3.4.1 Media Library View

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Media Library" in sidebar                │
│ URL: /admin/media                                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: MediaController::index()                            │
│ 1. Query: MediaModel::findAll()                              │
│ 2. Order by created_at DESC                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ SELECT * FROM media                                          │
│   WHERE deleted_at IS NULL                                   │
│   ORDER BY created_at DESC                                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Media Grid                                         │
│ View: admin/media/index.php                                  │
│                                                              │
│ Upload Form:                                                 │
│ - File input (accept: image/*)                               │
│ - Alt text input                                             │
│ - CSRF token (hidden)                                        │
│ - Upload button                                              │
│                                                              │
│ Media Grid:                                                  │
│ - Thumbnail images (responsive grid)                         │
│ - Filename and size display                                  │
│ - Alt text (editable inline)                                 │
│ - Delete button per image                                    │
│ - Click to select (for picker mode)                          │
└─────────────────────────────────────────────────────────────┘
```

#### 3.4.2 Upload Media Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Select file and click "Upload"                  │
│ Method: POST /admin/media/upload                             │
│ Content-Type: multipart/form-data                            │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: MediaController::upload()                           │
│ 1. Validate CSRF token                                       │
│ 2. Validate $_FILES['file'] exists                           │
│ 3. Validate file size ≤ 5MB                                  │
│ 4. Detect real MIME type with finfo                          │
│ 5. Validate MIME in allowed list                             │
│ 6. Generate random filename (32 hex chars)                   │
│ 7. Create year/month directory structure                     │
│ 8. Move uploaded file                                        │
│ 9. Insert media record                                       │
│ 10. Redirect to /admin/media                                 │
│                                                              │
│ On Validation Error:                                         │
│ - Redisplay media library with error                         │
│ - Do not create media record                                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ File System Operations:                                      │
│ 1. $year = date('Y');   // 2026                              │
│ 2. $month = date('m');  // 06                                │
│ 3. $dir = public/uploads/2026/06/                            │
│ 4. mkdir($dir, 0755, recursive=true)                         │
│ 5. $filename = bin2hex(random_bytes(16)) . '.jpg'            │
│ 6. move_uploaded_file($tmp, $dir . $filename)                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ INSERT INTO media                                            │
│   (filename, filepath, mime_type, alt_text,                  │
│    file_size, created_at)                                    │
│ VALUES (?, ?, ?, ?, ?, NOW())                                │
│                                                              │
│ filepath example: '2026/06/abc123...xyz.jpg'                 │
└─────────────────────────────────────────────────────────────┘
```

#### 3.4.3 Media Picker Integration Flow

```
┌─────────────────────────────────────────────────────────────┐
│ Context: User editing product/portfolio form                 │
│ Action: Click "Select Images" button                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: JavaScript Modal Opens                             │
│ - Load media library via AJAX (optional)                     │
│ - Display media grid in modal                                │
│ - Multi-select mode for galleries                            │
│ - Single-select mode for logo/favicon/QR                     │
│ - Selected items highlighted                                 │
│ - "Confirm Selection" button                                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click images to select                          │
│ JavaScript: Toggle selection, update preview                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Confirm Selection"                       │
│ JavaScript:                                                  │
│ 1. Get selected media IDs                                    │
│ 2. Update hidden input: media_ids = "1,5,3,7"                │
│ 3. Update preview area with thumbnails                       │
│ 4. Enable drag-to-reorder on thumbnails                      │
│ 5. Close modal                                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Form Submission: media_ids sent as comma-separated string    │
│ Backend: Parse and save in sort order                        │
└─────────────────────────────────────────────────────────────┘
```

### 3.5 Menu Management Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Menus" in sidebar                        │
│ URL: /admin/menus                                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: MenuController::index()                             │
│ 1. Query: MenuModel::findAll()                               │
│ 2. Order by sort_order ASC                                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Menu Management Interface                          │
│ View: admin/menus/index.php                                  │
│                                                              │
│ Add/Edit Form:                                               │
│ - Title* (text input)                                        │
│ - URL* (text input)                                          │
│ - Target (select: _self/_blank)                              │
│ - Active (checkbox)                                          │
│ - CSRF token (hidden)                                        │
│ - Submit button                                              │
│                                                              │
│ Menu List:                                                   │
│ - Sortable table/list (drag to reorder)                      │
│ - Columns: Order, Title, URL, Target, Active, Actions        │
│ - Edit button (inline or modal)                              │
│ - Delete button                                              │
│ - Save Order button                                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Drag to reorder menu items                      │
│ JavaScript: Update display order                             │
│ User Action: Click "Save Order"                              │
│ Method: POST /admin/menus/reorder                            │
│ Data: menu_ids[] = [5, 1, 3, 2, 4]                           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: MenuController::reorder()                           │
│ 1. Validate CSRF token                                       │
│ 2. Loop through menu_ids array                               │
│ 3. Update each: sort_order = (index + 1) * 10                │
│ 4. Redirect back to /admin/menus                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ UPDATE menus SET sort_order=10 WHERE id=5                    │
│ UPDATE menus SET sort_order=20 WHERE id=1                    │
│ UPDATE menus SET sort_order=30 WHERE id=3                    │
│ ...                                                          │
└─────────────────────────────────────────────────────────────┘
```

### 3.6 Site Settings Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Settings" in sidebar                     │
│ URL: /admin/settings                                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: SettingsController::index() [GET]                   │
│ 1. Query: SettingsModel::get()                               │
│ 2. Always returns single row (id=1)                          │
│ 3. Load media info for logo/favicon if set                   │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Settings Form                                      │
│ View: admin/settings/index.php                               │
│                                                              │
│ Form Fields:                                                 │
│ - Site Name* (text input)                                    │
│ - Site Description (textarea)                                │
│ - Phone (text input)                                         │
│ - Email (email input)                                        │
│ - LINE URL (text input)                                      │
│ - Facebook URL (text input)                                  │
│ - Address (textarea)                                         │
│ - Google Map Embed Code (textarea)                           │
│ - Logo (media picker, single)                                │
│ - Favicon (media picker, single)                             │
│ - CSRF token (hidden)                                        │
│ - Save Changes button                                        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Update settings and submit                      │
│ Method: POST /admin/settings                                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: SettingsController::index() [POST]                  │
│ 1. Validate CSRF token                                       │
│ 2. Validate site_name not empty                              │
│ 3. Sanitize all inputs                                       │
│ 4. SettingsModel::update($data)                              │
│ 5. Redirect to /admin/settings                               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Query                                         │
│ UPDATE settings SET                                          │
│   site_name=?, site_description=?, phone=?, email=?,         │
│   line_url=?, facebook_url=?, address=?,                     │
│   google_map_embed=?, logo_media_id=?, favicon_media_id=?,   │
│   updated_at=NOW()                                           │
│ WHERE id=1                                                   │
└─────────────────────────────────────────────────────────────┘
```

### 3.7 Page Management Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Pages" in sidebar                        │
│ URL: /admin/pages                                             │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminPageController::index()                        │
│ 1. Query: PageModel::findAll()                               │
│ 2. Fixed pages: Home, About, Contact                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Page List                                          │
│ View: admin/pages/list.php                                   │
│ - Table: ID, Title, Slug, Status, Actions                    │
│ - Edit button per page (no delete for fixed pages)           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Click "Edit" for a page                         │
│ URL: /admin/pages/{id}/edit                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminPageController::edit($id) [GET]                │
│ 1. Query: PageModel::findById($id)                           │
│ 2. Query: PageModel::findSections($id)                       │
│ 3. Load TinyMCE for section content editing                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Frontend: Page Edit Form                                     │
│ View: admin/pages/form.php                                   │
│                                                              │
│ Page Metadata:                                               │
│ - Title* (text input)                                        │
│ - Status (select: Published/Draft)                           │
│ - SEO Title (text input)                                     │
│ - SEO Description (textarea)                                 │
│                                                              │
│ Page Sections (repeatable):                                  │
│ For each section:                                            │
│ - Section Name (text input)                                  │
│ - Content (TinyMCE WYSIWYG editor)                           │
│ - Sort Order (number input)                                  │
│                                                              │
│ - CSRF token (hidden)                                        │
│ - Update Page button                                         │
└─────────────────────────────────────────────────────────────┘

                           ↓
┌─────────────────────────────────────────────────────────────┐
│ User Action: Update content and submit                       │
│ Method: POST /admin/pages/{id}/edit                          │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Backend: AdminPageController::edit($id) [POST]               │
│ 1. Validate CSRF token                                       │
│ 2. Validate title not empty                                  │
│ 3. Begin transaction                                         │
│ 4. PageModel::update($id, page data)                         │
│ 5. Loop through sections array:                              │
│    - Update each section content and sort_order              │
│ 6. Commit transaction                                        │
│ 7. Redirect to /admin/pages                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ Data: Database Queries                                       │
│ UPDATE pages SET                                             │
│   title=?, status=?, seo_title=?, seo_description=?,         │
│   updated_at=NOW()                                           │
│ WHERE id=?                                                   │
│                                                              │
│ UPDATE page_sections SET                                     │
│   section_name=?, content=?, sort_order=?, updated_at=NOW()  │
│ WHERE id=?                                                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. Data Flow Diagrams

### 4.1 Complete Request-Response Cycle

```
┌──────────────┐
│   Browser    │
│  (User CLI)  │
└──────┬───────┘
       │ HTTP Request (GET/POST)
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Entry Point (public/index.php or public/admin/index.php)    │
│  - Load bootstrap.php                                         │
│  - Start session                                              │
│  - Load config                                                │
│  - Initialize router                                          │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Router (core/Router.php)                                     │
│  - Match route pattern                                        │
│  - Extract parameters                                         │
│  - Dispatch to controller                                     │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Controller (app/controllers/*)                               │
│  - Validate CSRF (if POST)                                    │
│  - Validate input                                             │
│  - Call model methods                                         │
│  - Prepare data for view                                      │
│  - Render view or redirect                                    │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Model (app/models/*)                                         │
│  - Build SQL queries                                          │
│  - Execute with PDO prepared statements                       │
│  - Return data arrays                                         │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Database (MySQL)                                             │
│  - Execute query                                              │
│  - Return result set                                          │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  View (app/views/*)                                           │
│  - Receive data from controller                               │
│  - Escape output with e() function                            │
│  - Generate HTML                                              │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────┐
│   Browser    │
│ HTML Response│
└──────────────┘
```

### 4.2 Authentication Flow

```
┌──────────────────────────────────────────────────────────────┐
│  Admin Login Request                                          │
│  POST /admin/login                                            │
│  { username, password, csrf_token }                           │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  AuthController::login()                                      │
│  1. Validate CSRF token                                       │
│  2. Check credentials not empty                               │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  AdminModel::findByUsername($username)                        │
│  Query: SELECT * FROM admins WHERE username=?                 │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Password Verification                                        │
│  password_verify($input, $hash_from_db)                       │
└──────┬───────────────────────────────────────────────────────┘
       │
       ├─ Valid ──────────────────────────────────────┐
       │                                               │
       │                                               ↓
       │                              ┌─────────────────────────┐
       │                              │ Auth::login($admin)      │
       │                              │ - Set $_SESSION['admin'] │
       │                              │ - session_regenerate_id()│
       │                              └────────┬─────────────────┘
       │                                       │
       │                                       ↓
       │                              ┌─────────────────────────┐
       │                              │ Redirect to /admin       │
       │                              └──────────────────────────┘
       │
       └─ Invalid ────────────────────┐
                                      │
                                      ↓
                     ┌─────────────────────────────────┐
                     │ Show error message               │
                     │ Redisplay login form             │
                     └──────────────────────────────────┘
```

### 4.3 Media Upload and Storage Flow

```
┌──────────────────────────────────────────────────────────────┐
│  Browser: File Upload                                         │
│  POST /admin/media/upload                                     │
│  Content-Type: multipart/form-data                            │
│  { file: [binary], alt_text: "...", csrf_token }              │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  MediaController::upload()                                    │
│  - Validate CSRF                                              │
│  - Check $_FILES['file']                                      │
│  - Validate size ≤ 5MB                                        │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  MIME Type Detection                                          │
│  $finfo = new finfo(FILEINFO_MIME_TYPE)                       │
│  $mime = $finfo->file($_FILES['file']['tmp_name'])            │
│  Check if $mime in allowed list                               │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Generate Safe Filename                                       │
│  $random = bin2hex(random_bytes(16))                          │
│  $ext = $allowed_mimes[$mime]  // e.g., 'jpg'                 │
│  $filename = $random . '.' . $ext                             │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Create Directory Structure                                   │
│  $year = date('Y')    // 2026                                 │
│  $month = date('m')   // 06                                   │
│  $dir = public/uploads/2026/06/                               │
│  if (!is_dir($dir)) mkdir($dir, 0755, true)                   │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Move File                                                    │
│  move_uploaded_file($tmp, $dir . $filename)                   │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Save to Database                                             │
│  INSERT INTO media                                            │
│  (filename, filepath, mime_type, alt_text, file_size,         │
│   created_at)                                                 │
│  VALUES                                                       │
│  ('abc...xyz.jpg', '2026/06/abc...xyz.jpg', 'image/jpeg',    │
│   'Alt text', 524288, NOW())                                  │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Redirect                                                     │
│  Location: /admin/media                                       │
│  (PRG pattern)                                                │
└──────────────────────────────────────────────────────────────┘
```

### 4.4 Product Creation with Images Flow

```
┌──────────────────────────────────────────────────────────────┐
│  Browser: Create Product Form Submit                          │
│  POST /admin/products/create                                  │
│  {                                                            │
│    name, slug, description, line_url,                         │
│    qr_code_media_id, media_ids: "3,7,1,5",                    │
│    status, seo_title, seo_description, csrf_token             │
│  }                                                            │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  AdminProductController::create() [POST]                      │
│  1. Validate CSRF                                             │
│  2. Validate required fields                                  │
│  3. Validate slug uniqueness                                  │
│  4. Validate slug format (kebab-case)                         │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Start Database Transaction                                   │
│  $db->beginTransaction()                                      │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Insert Product Record                                        │
│  INSERT INTO products (...) VALUES (...)                      │
│  $product_id = $db->lastInsertId()                            │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Parse Media IDs                                              │
│  $media_ids = explode(',', $_POST['media_ids'])               │
│  // Result: [3, 7, 1, 5]                                      │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Insert Product Images (Loop)                                 │
│  foreach ($media_ids as $index => $media_id):                 │
│    INSERT INTO product_images                                 │
│      (product_id, media_id, sort_order)                       │
│    VALUES                                                     │
│      ($product_id, $media_id, ($index + 1) * 10)              │
│  endforeach                                                   │
│                                                              │
│  Sort order: 10, 20, 30, 40 (allows reordering later)         │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Commit Transaction                                           │
│  $db->commit()                                                │
└──────┬───────────────────────────────────────────────────────┘
       │
       ↓
┌──────────────────────────────────────────────────────────────┐
│  Redirect (PRG Pattern)                                       │
│  Location: /admin/products                                    │
│  Flash message: "Product created successfully"                │
└──────────────────────────────────────────────────────────────┘
```

---

## 5. Frontend UX Patterns

### 5.1 Responsive Design Breakpoints

| Breakpoint | Width | Layout Behavior |
|---|---|---|
| Mobile | < 576px | Single column, stacked navigation |
| Tablet | 576px - 992px | 2-column grid, hamburger menu |
| Desktop | > 992px | 3-column grid, full navigation |

### 5.2 Admin Panel UX Components

**AdminLTE 3 Components Used:**
- Content Header (breadcrumb + page title)
- Card widgets (content containers)
- DataTables (sortable, searchable tables)
- Form elements (validation states)
- Modals (media picker, confirmations)
- Toast notifications (success/error messages)
- Sidebar navigation (collapsible menu)

### 5.3 User Interaction Patterns

**Form Validation:**
- Client-side: HTML5 validation + JavaScript
- Server-side: Always validate on POST
- Error display: Inline messages + preserved values
- Success: Redirect with flash message

**Confirmation Dialogs:**
- Delete actions: JavaScript confirm() dialog
- Destructive actions: Modal with explicit confirmation
- Cancel option always available

**Loading States:**
- Form submit: Disable button, show spinner
- AJAX requests: Loading overlay
- Image uploads: Progress indicator

### 5.4 Public Website UX Patterns

**Navigation:**
- Sticky header on scroll
- Active menu item highlighting
- Breadcrumb navigation on detail pages
- Mobile hamburger menu

**Product/Portfolio Grids:**
- Card-based layout
- Hover effects on cards
- Lazy loading images (optional)
- Responsive grid (1/2/3 columns based on screen)

**Detail Pages:**
- Image gallery with lightbox
- Thumbnail navigation
- Social sharing buttons (LINE, Facebook)
- Call-to-action buttons (contact, LINE chat)

**Contact Page:**
- Embedded Google Maps
- Click-to-call phone numbers
- Click-to-email links
- Social media icon links

---

## 6. JavaScript Components

### 6.1 Media Picker

**File:** `app/views/admin/media/_picker_help.php`

```javascript
// Initialize picker
function openMediaPicker(mode = 'multi') {
    $('#mediaPicker').modal('show');
    window.mediaPickerMode = mode;
}

// Handle selection
function confirmMediaSelection() {
    const selected = $('.media-item.selected').map(function() {
        return $(this).data('media-id');
    }).get();
    
    $('#media_ids').val(selected.join(','));
    updatePreview(selected);
    $('#mediaPicker').modal('hide');
}

// Update preview with thumbnails
function updatePreview(mediaIds) {
    const preview = $('#selected-media-preview');
    preview.empty();
    
    mediaIds.forEach(id => {
        const img = $(`<img src="..." data-id="${id}" draggable="true">`);
        preview.append(img);
    });
    
    enableDragSort(preview);
}
```

### 6.2 Slug Generator

```javascript
// Auto-generate slug from name
$('#product-name').on('input', function() {
    if ($('#slug-auto').is(':checked')) {
        const slug = $(this).val()
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('#product-slug').val(slug);
    }
});
```

### 6.3 Menu Reordering

```javascript
// Sortable menu list
$('#menu-list').sortable({
    handle: '.drag-handle',
    update: function(event, ui) {
        // Mark as changed
        $('#save-order-btn').addClass('btn-warning');
    }
});

// Save order
$('#save-order-btn').on('click', function() {
    const order = $('#menu-list').sortable('toArray', {
        attribute: 'data-menu-id'
    });
    
    $.post('/admin/menus/reorder', {
        menu_ids: order,
        csrf_token: $('[name=csrf_token]').val()
    }, function() {
        location.reload();
    });
});
```

### 6.4 Form Validation

```javascript
// Client-side validation before submit
$('form[data-validate]').on('submit', function(e) {
    const errors = [];
    
    // Check required fields
    $(this).find('[required]').each(function() {
        if (!$(this).val().trim()) {
            errors.push($(this).attr('name') + ' is required');
        }
    });
    
    // Check slug format
    const slug = $('#product-slug').val();
    if (slug && !/^[a-z0-9-]+$/.test(slug)) {
        errors.push('Slug can only contain lowercase letters, numbers, and hyphens');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join('\n'));
        return false;
    }
});
```

---

## 7. CLI Commands Reference

### 7.1 Database Setup

```bash
# Create database and import schema
mysql -u root -p < database/schema.sql

# Or manually:
mysql -u root -p
CREATE DATABASE mkg_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mkg_cms;
SOURCE database/schema.sql;
```

### 7.2 Development Server

```bash
# Using XAMPP (Windows)
# Place project in C:\xampp\htdocs\mkg-cms
# Access: http://localhost/mkg-cms/public

# Using PHP built-in server
cd public
php -S localhost:8000

# Access: http://localhost:8000
```

### 7.3 File Permissions

```bash
# Linux/Mac
chmod -R 755 public/uploads
chmod 644 config/database.php

# Make sure web server can write to uploads
chown -R www-data:www-data public/uploads
```

### 7.4 Composer (if used in future)

```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Dump autoload
composer dump-autoload
```

---

## 8. API Route Summary

### 8.1 Public Routes (Frontend)

| Method | Path | Description | View |
|---|---|---|---|
| GET | `/` | Home page | frontend/home.php |
| GET | `/about` | About page | frontend/about.php |
| GET | `/contact` | Contact page | frontend/contact.php |
| GET | `/products` | Product listing | frontend/products/index.php |
| GET | `/products/{slug}` | Product detail | frontend/products/show.php |
| GET | `/portfolio` | Portfolio listing | frontend/portfolio/index.php |
| GET | `/portfolio/{slug}` | Portfolio detail | frontend/portfolio/show.php |

### 8.2 Admin Routes (Authentication)

| Method | Path | Description | Access |
|---|---|---|---|
| GET | `/admin/login` | Login form | Public |
| POST | `/admin/login` | Process login | Public |
| POST | `/admin/logout` | Process logout | Authenticated |

### 8.3 Admin Routes (Content Management)

| Method | Path | Description | Access |
|---|---|---|---|
| GET | `/admin` | Dashboard | Authenticated |
| GET | `/admin/pages` | Page list | Authenticated |
| GET | `/admin/pages/{id}/edit` | Edit page | Authenticated |
| POST | `/admin/pages/{id}/edit` | Update page | Authenticated |
| GET | `/admin/products` | Product list | Authenticated |
| GET | `/admin/products/create` | Create form | Authenticated |
| POST | `/admin/products/create` | Store product | Authenticated |
| GET | `/admin/products/{id}/edit` | Edit form | Authenticated |
| POST | `/admin/products/{id}/edit` | Update product | Authenticated |
| POST | `/admin/products/{id}/delete` | Delete product | Authenticated |
| GET | `/admin/portfolios` | Portfolio list | Authenticated |
| GET | `/admin/portfolios/create` | Create form | Authenticated |
| POST | `/admin/portfolios/create` | Store portfolio | Authenticated |
| GET | `/admin/portfolios/{id}/edit` | Edit form | Authenticated |
| POST | `/admin/portfolios/{id}/edit` | Update portfolio | Authenticated |
| POST | `/admin/portfolios/{id}/delete` | Delete portfolio | Authenticated |
| GET | `/admin/media` | Media library | Authenticated |
| POST | `/admin/media/upload` | Upload file | Authenticated |
| POST | `/admin/media/{id}/alt` | Update alt text | Authenticated |
| POST | `/admin/media/{id}/delete` | Delete media | Authenticated |
| GET | `/admin/menus` | Menu list | Authenticated |
| POST | `/admin/menus` | Save menu item | Authenticated |
| POST | `/admin/menus/reorder` | Reorder menus | Authenticated |
| POST | `/admin/menus/{id}/delete` | Delete menu | Authenticated |
| GET | `/admin/settings` | Settings form | Authenticated |
| POST | `/admin/settings` | Update settings | Authenticated |

---

## 9. Error Handling Flow

```
┌─────────────────────────────────────────────────────────────┐
│ Error Occurs                                                 │
└──────┬──────────────────────────────────────────────────────┘
       │
       ├─ 404 Not Found ────────────────────────────────────┐
       │                                                     │
       │  - Unknown route                                    │
       │  - Missing product/portfolio                        │
       │  - Unpublished record on public site                │
       │                                                     │
       │  Response: HTTP 404 + custom 404 page               │
       │                                                     │
       ├─ 403 Forbidden ────────────────────────────────────┤
       │                                                     │
       │  - Invalid CSRF token                               │
       │  - Unauthorized admin access                        │
       │                                                     │
       │  Response: HTTP 403 + error message                 │
       │                                                     │
       ├─ Validation Error ─────────────────────────────────┤
       │                                                     │
       │  - Required field missing                           │
       │  - Invalid format                                   │
       │  - Duplicate slug                                   │
       │                                                     │
       │  Response: Redisplay form with errors               │
       │            Preserve submitted values                │
       │                                                     │
       └─ Server Error (500) ──────────────────────────────┤
                                                             │
         - Database connection failed                        │
         - File upload failed                                │
         - Unexpected exception                              │
                                                             │
         Development: Show detailed error                    │
         Production: Show generic error message              │
                     Log error details                       │
                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 10. Performance Optimization Patterns

### 10.1 Database Query Optimization

```php
// Bad: N+1 query problem
$products = $productModel->findAll();
foreach ($products as $product) {
    $images = $productModel->findImages($product['id']); // N queries
}

// Good: Join or batch query
$products = $productModel->findAllWithFirstImage(); // 1 query with JOIN
```

### 10.2 Image Loading Strategy

- **Thumbnails**: Display resized versions in grids
- **Lazy loading**: Load images as they enter viewport
- **Progressive loading**: Show low-res placeholder first
- **CDN**: Serve static assets from CDN in production

### 10.3 Caching Strategy (Future Enhancement)

```php
// Page-level caching
$cache_key = 'product_' . $slug;
if ($cached = Cache::get($cache_key)) {
    return $cached;
}
$product = $productModel->findBySlug($slug);
Cache::set($cache_key, $product, 3600); // 1 hour

// Query result caching
$settings = Cache::remember('site_settings', 3600, function() {
    return SettingsModel::get();
});
```

---

## 11. Security Flow Patterns

### 11.1 CSRF Protection Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User visits form page (GET request)                          │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Server generates CSRF token                                  │
│ $token = Csrf::generate()                                    │
│ $_SESSION['csrf_token'] = bin2hex(random_bytes(32))          │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Form includes hidden CSRF field                              │
│ <input type="hidden" name="csrf_token" value="<?= $token ?>">│
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ User submits form (POST request)                             │
│ Data includes csrf_token from hidden field                   │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Server validates token                                       │
│ Csrf::validate()                                             │
│ Compare $_POST['csrf_token'] === $_SESSION['csrf_token']     │
└──────┬──────────────────────────────────────────────────────┘
       │
       ├─ Valid ─────────────┐
       │                     │
       │                     ↓
       │         ┌──────────────────────┐
       │         │ Process form data     │
       │         │ Execute action        │
       │         └──────────────────────┘
       │
       └─ Invalid ───────────┐
                             │
                             ↓
                ┌──────────────────────────┐
                │ Return HTTP 403          │
                │ "Invalid request"        │
                └──────────────────────────┘
```

### 11.2 File Upload Security Flow

```
┌─────────────────────────────────────────────────────────────┐
│ User selects file and uploads                                │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Server-side Validation                                       │
│ 1. Check file exists in $_FILES                              │
│ 2. Check upload error code === UPLOAD_ERR_OK                 │
│ 3. Check file size ≤ max_upload_bytes                        │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ MIME Type Detection (bypass extension check)                 │
│ $finfo = new finfo(FILEINFO_MIME_TYPE)                       │
│ $mime = $finfo->file($_FILES['file']['tmp_name'])            │
│ // Do NOT trust $_FILES['file']['type'] from client          │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Validate Against Whitelist                                   │
│ $allowed = ['image/jpeg', 'image/png', 'image/gif', ...]     │
│ if (!in_array($mime, array_keys($allowed))) reject           │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Generate Random Filename                                     │
│ $random = bin2hex(random_bytes(16)) // 32 hex chars          │
│ $ext = $allowed[$mime]  // Safe extension from whitelist     │
│ $filename = $random . '.' . $ext                             │
│ // Original filename is NEVER used in storage                │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Store in Uploads Directory                                   │
│ Path: public/uploads/{year}/{month}/{random}.{ext}           │
│ Directory contains .htaccess to block PHP execution:         │
│ <Files *.php>                                                │
│   Require all denied                                         │
│ </Files>                                                     │
└──────┬──────────────────────────────────────────────────────┘
       │
       ↓
┌─────────────────────────────────────────────────────────────┐
│ Save Metadata to Database                                    │
│ Store: original filename (for display only)                  │
│        filepath (relative path)                              │
│        mime_type, file_size, alt_text                        │
└──────────────────────────────────────────────────────────────┘
```

---

## 12. Complete User Journey Examples

### 12.1 Customer Browsing Products

```
1. Customer opens website → /
2. Sees hero section and featured content
3. Clicks "Products" in navigation → /products
4. Sees grid of published products with images
5. Clicks on a product card → /products/{slug}
6. Views product gallery, description, QR code
7. Clicks LINE button → Opens LINE chat
8. Clicks "Contact Us" → /contact
9. Views contact info, map, phone/email
10. Calls or sends message
```

### 12.2 Admin Managing Products

```
1. Admin opens admin panel → /admin → Redirected to /admin/login
2. Enters credentials and submits
3. Session created, redirected to → /admin (dashboard)
4. Clicks "Products" in sidebar → /admin/products
5. Sees paginated product list
6. Clicks "Add New Product" → /admin/products/create
7. Fills form:
   - Name: "Custom Vinyl Banner"
   - Slug: auto-generated "custom-vinyl-banner"
   - Clicks "Select Images" → Media picker modal opens
   - Selects 3 images in order
   - Modal closes, thumbnails shown
   - Fills LINE URL and description
   - Selects QR code image
   - Sets status: Published
   - Fills SEO fields
8. Clicks "Create Product"
9. Server validates, saves to database
10. Redirected to /admin/products
11. Success message shown
12. Product appears in list
13. Admin can click "Edit" to modify or "Delete" to soft-delete
```

### 12.3 Admin Uploading Media

```
1. Admin logged in, clicks "Media Library" → /admin/media
2. Sees existing uploaded images in grid
3. Clicks file input, selects image from computer
4. Enters alt text: "Company logo"
5. Clicks "Upload"
6. Server validates file (size, MIME type)
7. Generates random filename
8. Creates directory: uploads/2026/06/
9. Moves file to: uploads/2026/06/abc123...xyz.jpg
10. Saves record to media table
11. Redirected back to /admin/media
12. New image appears at top of grid
13. Can now be selected in product/portfolio forms
```

---

## 13. Accessibility Features

### 13.1 Semantic HTML

- Use proper heading hierarchy (h1 → h2 → h3)
- Use `<nav>` for navigation
- Use `<main>` for main content
- Use `<article>` for product/portfolio items
- Use `<button>` for clickable actions, `<a>` for links

### 13.2 Alt Text

- All images require alt text (stored in media table)
- Decorative images use empty alt=""
- Informative images describe content

### 13.3 Keyboard Navigation

- All forms accessible via Tab
- Modal dialogs trap focus
- Skip to main content link
- Visible focus indicators

### 13.4 ARIA Labels

```html
<button aria-label="Delete product">
  <i class="fas fa-trash"></i>
</button>

<div role="alert" aria-live="polite">
  Product created successfully
</div>
```

---

*This document serves as the complete reference for frontend, UX-UI, CLI, API, backend, and data flow for the MKG-CMS project.*

**Related Documentation:**
- CLAUDE.md – Project guide for Claude
- api.md – Detailed API route contract
- TSD.md – Technical system design
- architecture.md – System architecture
- SECURITY.md – Security requirements

**Last Updated:** 2026-06-02














