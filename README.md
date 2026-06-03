# mkg-cms

Custom CMS for Mae Klong Graphic, built with lightweight PHP MVC, PDO, MySQL, Bootstrap, and TinyMCE.

## Docker (Development)

The fastest way to run the CMS locally. Requires Docker with the Compose plugin.

```bash
docker compose up -d --build
```

This starts PHP 8.3 + Apache, a MySQL 8.0 database seeded from `database/schema.sql`,
and Adminer. The project directory is bind-mounted, so code changes are live.

```text
Frontend: http://localhost:8080/
Admin:    http://localhost:8080/admin/   (admin / xxxx — change before non-local use)
Adminer:  http://localhost:8081/         (System: MySQL, Server: db, User: mkg, Password: mkg_secret, Database: mkg_cms)
```

Stop the stack with `docker compose down`, or `docker compose down -v` to also drop the
database volume and re-seed on the next start.

The XAMPP instructions below remain valid; the app serves from the document root under
Docker and from the `/mkg-cms/public/` subpath under XAMPP.

## Requirements

- XAMPP with PHP 8.3-compatible runtime
- MySQL 8.0-compatible database
- Apache document root pointing to this project under `htdocs`

## Quick Setup

1. Configure database credentials in `config/database.php`.
2. Import the schema and seed data:

```powershell
C:\xampp\mysql\bin\mysql.exe -h 127.0.0.1 -u root -e "source C:/xampp/htdocs/mkg-cms/database/schema.sql"
```

3. Confirm uploads and sessions are writable:

```text
public/uploads/
storage/sessions/
```

4. Open the CMS:

```text
Frontend: http://localhost/mkg-cms/public/
Admin:    http://localhost/mkg-cms/public/admin/
```

Seed admin login for local setup:

```text
Username: admin
Password: xxxx
```

Change this password before using the CMS outside local development.

## Included Modules

- Public pages: home, about, products, portfolio, contact
- Admin auth with sessions, CSRF, and POST-only logout
- Admin dashboard, pages, products, portfolios, media, menus, and settings
- Media upload validation with MIME checks, randomized filenames, and dated upload folders
- Soft delete for media, products, and portfolios

## Documentation

- `SRS.md`: Software requirements
- `TSD.md`: Technical specification
- `SECURITY.md`: Security baseline
- `testing.md`: Manual test plan
- `database/schema.sql`: MySQL schema and seed data
