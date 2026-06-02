CREATE TABLE IF NOT EXISTS hero_slides (
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
