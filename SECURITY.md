# SECURITY — Web Security Baseline

**Project:** mkg-cms CMS  
**Version:** 1.0.0  
**Date:** 2026-06-02  
**Standard:** OWASP Top 10 (2021) — Applied to PHP/MySQL CMS context

---

## Table of Contents

1. [Authentication & Session](#1-authentication--session)
2. [CSRF Protection](#2-csrf-protection)
3. [SQL Injection Prevention](#3-sql-injection-prevention)
4. [Cross-Site Scripting (XSS)](#4-cross-site-scripting-xss)
5. [File Upload Security](#5-file-upload-security)
6. [Access Control](#6-access-control)
7. [Security Headers](#7-security-headers)
8. [Password Policy](#8-password-policy)
9. [Error Handling](#9-error-handling)
10. [OWASP Top 10 Coverage](#10-owasp-top-10-coverage)
11. [Security Checklist](#11-security-checklist)

---

## 1. Authentication & Session

### Requirements

- Passwords stored using `password_hash()` with `PASSWORD_BCRYPT`
- Passwords verified using `password_verify()` only — never compared directly
- Session ID regenerated immediately after login
- Session destroyed completely on logout
- No "remember me" feature in v1.0

### Implementation

```php
// Login
public function login(): void
{
    $admin = $this->model->findByUsername($_POST['username']);

    if (!$admin || !password_verify($_POST['password'], $admin['password_hash'])) {
        // Intentionally vague — do not reveal which field is wrong
        $this->view('auth/login', ['error' => 'Invalid username or password']);
        return;
    }

    session_regenerate_id(true);  // Prevent session fixation
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $this->redirect('/admin');
}

// Logout
public function logout(): void
{
    $this->verifyCsrf();          // Logout must also be CSRF-protected
    $_SESSION = [];
    session_destroy();
    $this->redirect('/admin/login');
}
```

### Session Configuration (`php.ini` / `session_start` options)

```php
session_start([
    'cookie_httponly' => true,   // JS cannot access session cookie
    'cookie_samesite' => 'Lax',  // Limits cross-site cookie sending
    'use_strict_mode' => true,   // Reject uninitialized session IDs
]);
```

### What to Avoid

| ❌ Don't | ✅ Do |
|---|---|
| `if ($pass == $storedPass)` | `password_verify($pass, $hash)` |
| `md5()` or `sha1()` for passwords | `password_hash($pass, PASSWORD_BCRYPT)` |
| Skip `session_regenerate_id()` | Always regenerate after login |
| Store admin ID in a cookie | Use server-side `$_SESSION` only |

---

## 2. CSRF Protection

Cross-Site Request Forgery forces a logged-in user's browser to perform unwanted actions. Every state-changing request (POST/PUT/DELETE) must include a verified token.

### Token Generation

```php
// core/Csrf.php
class Csrf
{
    public static function generate(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verify(string $token): bool
    {
        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
```

> **Why `hash_equals()`?** Prevents timing attacks — it compares strings in constant time regardless of where they differ.

### Usage in Forms

```html
<form method="POST" action="/admin/products/create">
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(Csrf::generate(), ENT_QUOTES, 'UTF-8') ?>">
    <!-- ... -->
</form>
```

### Verification in Controllers

```php
protected function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!Csrf::verify($token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}
```

### Rules

- Every POST form includes a `csrf_token` hidden field
- Token is verified **before** any database write
- CSRF protection is **never disabled**, including for "simple" forms like delete confirmations
- Logout is also CSRF-protected (POST form, not a `<a href>` link)

---

## 3. SQL Injection Prevention

SQL injection allows attackers to manipulate database queries by injecting malicious SQL through user input.

### Rule: PDO Prepared Statements — Always

```php
// ✅ CORRECT — named placeholder
$stmt = $this->db->prepare(
    'SELECT * FROM products WHERE slug = :slug AND deleted_at IS NULL'
);
$stmt->execute([':slug' => $slug]);

// ✅ CORRECT — positional placeholder
$stmt = $this->db->prepare(
    'SELECT * FROM admins WHERE id = ?'
);
$stmt->execute([$id]);
```

```php
// ❌ NEVER — string concatenation with user input
$query = "SELECT * FROM products WHERE slug = '" . $slug . "'";
$result = $this->db->query($query);

// ❌ NEVER — even with escaping, this is fragile
$slug = $this->db->quote($slug);
$query = "SELECT * FROM products WHERE slug = $slug";
```

### Dynamic ORDER BY / Column Names

Column and direction names cannot be parameterized. Use an allowlist:

```php
$allowedColumns = ['name', 'created_at', 'status'];
$allowedDirs    = ['ASC', 'DESC'];

$col = in_array($_GET['sort'] ?? '', $allowedColumns) ? $_GET['sort'] : 'created_at';
$dir = in_array($_GET['dir']  ?? '', $allowedDirs)    ? $_GET['dir']  : 'DESC';

$stmt = $this->db->prepare("SELECT * FROM products ORDER BY {$col} {$dir}");
```

### LIKE Queries

Wildcards in LIKE require escaping separately:

```php
$search = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
$stmt = $this->db->prepare('SELECT * FROM products WHERE name LIKE :search');
$stmt->execute([':search' => $search]);
```

---

## 4. Cross-Site Scripting (XSS)

XSS allows attackers to inject JavaScript into pages viewed by other users. In a CMS, the admin panel itself can be targeted if stored content is reflected without escaping.

### Output Escaping Rule

**Every** variable rendered in a view that originates from user input or the database must be escaped:

```php
// ✅ Always use this
echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

// Shorthand helper (define in core)
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// In views
<h1><?= e($product['name']) ?></h1>
<input type="text" value="<?= e($product['name']) ?>">
```

### TinyMCE Content (Stored HTML)

Content saved through TinyMCE is intentionally HTML — it should **not** be escaped on output. However:

- Only admin users can write TinyMCE content
- TinyMCE is configured to strip `<script>` tags client-side
- Consider server-side HTML purification (e.g. `strip_tags()` with allowlist) if untrusted users ever submit content

```php
// For TinyMCE content — output raw (admin-only input)
echo $section['content'];  // Only safe because only admins write this

// For everything else — always escape
echo e($product['name']);
```

### Content Security Policy

See [Section 7 — Security Headers](#7-security-headers).

---

## 5. File Upload Security

File uploads are a common attack vector. Uploaded files must never be trusted.

### Validation Pipeline

```php
public function upload(): void
{
    // 1. Check PHP upload error
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error: ' . $_FILES['file']['error']);
    }

    // 2. Check file size (5 MB max)
    $maxBytes = 5 * 1024 * 1024;
    if ($_FILES['file']['size'] > $maxBytes) {
        $this->flashError('File size must not exceed 5 MB.');
        return;
    }

    // 3. Detect real MIME type — DO NOT trust $_FILES['type']
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($_FILES['file']['tmp_name']);

    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedMimes, true)) {
        $this->flashError('Only image files (JPEG, PNG, GIF, WebP) are allowed.');
        return;
    }

    // 4. Generate safe filename — never use original name on disk
    $ext      = match($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    };
    $safeName = bin2hex(random_bytes(16)) . '.' . $ext;

    // 5. Build destination path
    $dir  = PUBLIC_PATH . '/uploads/' . date('Y') . '/' . date('m') . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $dest = $dir . $safeName;

    // 6. Move file
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    // 7. Insert media record
    $this->model->create([
        'filename'   => $_FILES['file']['name'],  // Original name stored for display only
        'filepath'   => '/uploads/' . date('Y') . '/' . date('m') . '/' . $safeName,
        'mime_type'  => $mimeType,
        'file_size'  => $_FILES['file']['size'],
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}
```

### Rules Summary

| Rule | Reason |
|---|---|
| Never trust `$_FILES['type']` | Browsers can fake MIME type |
| Use `finfo` for real MIME detection | Reads actual file magic bytes |
| Generate random filename on disk | Prevents path traversal and enumeration |
| Store original filename only in DB | For display purposes only |
| Upload directory outside web root (ideal) or with `.htaccess` blocking PHP execution | Prevent uploaded PHP files from executing |

### `.htaccess` for Uploads Directory

Place in `public/uploads/.htaccess`:

```apache
# Block direct PHP execution in uploads directory
<FilesMatch "\.ph(p[0-9]?|tml)$">
    Deny from all
</FilesMatch>

Options -Indexes
```

---

## 6. Access Control

### Admin Route Protection

Every admin route must check for an authenticated session:

```php
// core/Auth.php
class Auth
{
    public static function requireAdmin(): void
    {
        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}

// In every admin controller constructor or base controller
public function __construct()
{
    Auth::requireAdmin();
}
```

### Principle of Least Privilege

- DB user used by the application should only have `SELECT, INSERT, UPDATE, DELETE` on the app database — no `DROP`, `CREATE`, `FILE`, or `GRANT`
- Admin panel is only accessible at `/admin` — no public-facing write endpoints

### Object-Level Authorization

When editing a record by ID, always verify it exists and belongs to the expected context:

```php
// ✅ Correct — verify record exists before editing
$product = $this->model->findById((int)$_GET['id']);
if (!$product) {
    $this->notFound();
    return;
}
```

---

## 7. Security Headers

Set these headers on every response. Add to `public/.htaccess` or in a base controller method called before any output:

```apache
# public/.htaccess
Header always set X-Content-Type-Options "nosniff"
Header always set X-Frame-Options "SAMEORIGIN"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "camera=(), microphone=(), geolocation=()"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' https://cdn.tiny.cloud https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' data: blob:; frame-src https://www.google.com;"
```

Or in PHP before any output:

```php
protected function setSecurityHeaders(): void
{
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("Referrer-Policy: strict-origin-when-cross-origin");
}
```

| Header | Purpose |
|---|---|
| `X-Content-Type-Options: nosniff` | Prevents MIME-type sniffing attacks |
| `X-Frame-Options: SAMEORIGIN` | Prevents clickjacking via `<iframe>` |
| `Referrer-Policy` | Controls how much referrer info is sent |
| `Content-Security-Policy` | Restricts which resources can load |

---

## 8. Password Policy

### Storage

```php
// Hash on create/change password
$hash = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);

// Verify on login
if (password_verify($plainPassword, $storedHash)) { ... }

// Check if rehash needed (after cost increase)
if (password_needs_rehash($storedHash, PASSWORD_BCRYPT, ['cost' => 12])) {
    $newHash = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);
    $this->model->updateHash($adminId, $newHash);
}
```

### Minimum Requirements (v1.0)

- Minimum length: **8 characters**
- No maximum length (bcrypt handles any input length safely after 72 bytes internally)
- No complexity rules enforced in v1.0 (single known admin user)

### What to Never Do

```php
// ❌ Never
md5($password)
sha1($password)
base64_encode($password)
$password  // storing plain text
```

---

## 9. Error Handling

### Development vs Production

```php
// config/app.php
define('APP_ENV', 'development');  // Change to 'production' on server

// Bootstrap
if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
```

### Rules

- **Never display stack traces or SQL errors to users in production**
- Log all errors to file — review regularly
- Generic user-facing error: "Something went wrong. Please try again."
- PDO: Use `PDO::ERRMODE_EXCEPTION` so errors throw catchable exceptions

```php
// config/database.php
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,  // Use real prepared statements
]);
```

---

## 10. OWASP Top 10 Coverage

| # | OWASP Risk (2021) | Status | Mitigation |
|---|---|---|---|
| A01 | Broken Access Control | ✅ Addressed | Auth middleware on all `/admin` routes; object-level checks |
| A02 | Cryptographic Failures | ✅ Addressed | bcrypt for passwords; HTTPS in production |
| A03 | Injection (SQL) | ✅ Addressed | PDO prepared statements only; no string concatenation |
| A04 | Insecure Design | ✅ Addressed | Soft delete; CSRF; security-first controller pattern |
| A05 | Security Misconfiguration | ⚠️ Partial | Security headers defined; production config TBD |
| A06 | Vulnerable Components | ⚠️ Partial | AdminLTE/Bootstrap via CDN; keep versions updated |
| A07 | Identity/Auth Failures | ✅ Addressed | Session regeneration; vague error messages; bcrypt |
| A08 | Software/Data Integrity | ✅ Addressed | CSRF tokens; no unsafe deserialization |
| A09 | Logging & Monitoring | ⚠️ Partial | PHP error log enabled; no audit trail in v1.0 |
| A10 | SSRF | ✅ N/A | No server-side URL fetching in this CMS |

---

## 11. Security Checklist

Use this checklist before marking any feature complete:

### Authentication
- [ ] Passwords hashed with `password_hash()` (never plain text)
- [ ] Login uses `password_verify()`
- [ ] `session_regenerate_id(true)` called after login
- [ ] Logout destroys session completely
- [ ] Login error message is generic (no field-specific clues)

### CSRF
- [ ] Every POST form includes `<input type="hidden" name="csrf_token">`
- [ ] Every controller POST handler calls `$this->verifyCsrf()` first
- [ ] CSRF token generated with `random_bytes(32)` and stored in session

### SQL
- [ ] All queries use PDO prepared statements
- [ ] No user input concatenated into any SQL string
- [ ] Dynamic ORDER BY uses an allowlist, not direct user input

### XSS
- [ ] All user-supplied data in views wrapped with `htmlspecialchars()`
- [ ] TinyMCE output rendered raw only in confirmed admin-only views
- [ ] Security headers set (`X-Content-Type-Options`, `X-Frame-Options`, CSP)

### File Upload
- [ ] MIME type verified with `finfo`, not `$_FILES['type']`
- [ ] File size checked before processing
- [ ] Filename randomized on disk
- [ ] Upload directory has `.htaccess` blocking PHP execution

### Access Control
- [ ] All `/admin` controllers call `Auth::requireAdmin()` in constructor
- [ ] DB operations fetch by ID and verify record exists before update/delete
- [ ] No admin functionality accessible via GET request (only POST for writes)

### Error Handling
- [ ] `APP_ENV=production` disables `display_errors`
- [ ] PDO uses `ERRMODE_EXCEPTION`
- [ ] No internal error details exposed in HTTP responses

---

*Document owner: First (Chakron) · Mae Klong Graphic Internship Project*  
*Security baseline based on OWASP Top 10 (2021) — https://owasp.org/Top10/*
