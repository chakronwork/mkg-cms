CREATE DATABASE IF NOT EXISTS mkg_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mkg_cms;

CREATE TABLE admins (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(80) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admins_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE pages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  seo_title VARCHAR(255) NULL,
  seo_description TEXT NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE page_sections (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  page_id INT UNSIGNED NOT NULL,
  section_key VARCHAR(100) NOT NULL,
  section_name VARCHAR(150) NOT NULL,
  content LONGTEXT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_page_sections_page_id (page_id),
  CONSTRAINT fk_page_sections_page FOREIGN KEY (page_id) REFERENCES pages (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL,
  filepath VARCHAR(512) NOT NULL,
  mime_type VARCHAR(100) NOT NULL,
  alt_text VARCHAR(255) NULL,
  file_size INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE products (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NULL,
  line_url VARCHAR(512) NULL,
  qr_code_media_id INT UNSIGNED NULL,
  seo_title VARCHAR(255) NULL,
  seo_description TEXT NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_products_slug (slug),
  KEY idx_products_status_deleted (status, deleted_at),
  CONSTRAINT fk_products_qr_media FOREIGN KEY (qr_code_media_id) REFERENCES media (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE product_images (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT UNSIGNED NOT NULL,
  media_id INT UNSIGNED NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_product_images_product (product_id),
  CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
  CONSTRAINT fk_product_images_media FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE portfolios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NULL,
  seo_title VARCHAR(255) NULL,
  seo_description TEXT NULL,
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_portfolios_slug (slug),
  KEY idx_portfolios_status_deleted (status, deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE portfolio_images (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  portfolio_id INT UNSIGNED NOT NULL,
  media_id INT UNSIGNED NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_portfolio_images_portfolio (portfolio_id),
  CONSTRAINT fk_portfolio_images_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios (id) ON DELETE CASCADE,
  CONSTRAINT fk_portfolio_images_media FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menus (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  url VARCHAR(512) NOT NULL,
  target ENUM('_self','_blank') NOT NULL DEFAULT '_self',
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  KEY idx_menus_active_sort (is_active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE hero_slides (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  media_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NULL,
  subtitle TEXT NULL,
  link_url VARCHAR(512) NULL,
  link_label VARCHAR(120) NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_hero_slides_active_sort (is_active, sort_order),
  CONSTRAINT fk_hero_slides_media FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  site_name VARCHAR(255) NOT NULL,
  site_description TEXT NULL,
  phone VARCHAR(30) NULL,
  email VARCHAR(255) NULL,
  line_url VARCHAR(512) NULL,
  facebook_url VARCHAR(512) NULL,
  address TEXT NULL,
  google_map_embed TEXT NULL,
  logo_media_id INT UNSIGNED NULL,
  favicon_media_id INT UNSIGNED NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_settings_logo FOREIGN KEY (logo_media_id) REFERENCES media (id) ON DELETE SET NULL,
  CONSTRAINT fk_settings_favicon FOREIGN KEY (favicon_media_id) REFERENCES media (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (username, password_hash, full_name, created_at, updated_at)
VALUES ('admin', '$2y$12$04Di0Jv7.hItepbr/Bogl.i84t09igud.zcz7tcpCqGgdqUvbktBK', 'Administrator', NOW(), NOW());

INSERT INTO pages (title, slug, seo_title, seo_description, status, created_at, updated_at)
VALUES
('Home', 'home', 'Mae Klong Graphic', 'Sign shop products and portfolio.', 'published', NOW(), NOW()),
('About', 'about', 'About Mae Klong Graphic', 'About our sign-making services.', 'published', NOW(), NOW()),
('Contact', 'contact', 'Contact Mae Klong Graphic', 'Contact us for signs and printing.', 'published', NOW(), NOW());

INSERT INTO page_sections (page_id, section_key, section_name, content, sort_order, created_at, updated_at)
SELECT id, 'hero', 'Hero', '<h1>Mae Klong Graphic</h1><p>Custom signs, vinyl, stickers, and print work.</p>', 10, NOW(), NOW()
FROM pages WHERE slug = 'home';

INSERT INTO page_sections (page_id, section_key, section_name, content, sort_order, created_at, updated_at)
SELECT id, 'intro', 'Intro', '<p>Edit this page section from the admin panel.</p>', 10, NOW(), NOW()
FROM pages WHERE slug IN ('about', 'contact');

INSERT INTO menus (title, url, target, sort_order, is_active)
VALUES
('หน้าแรก', '/', '_self', 10, 1),
('เกี่ยวกับเรา', '/about', '_self', 20, 1),
('สินค้า', '/products', '_self', 30, 1),
('ผลงาน', '/portfolio', '_self', 40, 1),
('ติดต่อเรา', '/contact', '_self', 50, 1);

INSERT INTO settings (site_name, site_description, phone, email, line_url, facebook_url, address, google_map_embed, updated_at)
VALUES ('Mae Klong Graphic', 'Custom sign and print shop CMS.', '', '', '', '', '', '', NOW());
