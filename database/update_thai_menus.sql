SET NAMES utf8mb4;

UPDATE menus
SET title = CASE url
  WHEN '/' THEN 'หน้าแรก'
  WHEN '/about' THEN 'เกี่ยวกับเรา'
  WHEN '/products' THEN 'สินค้า'
  WHEN '/portfolio' THEN 'ผลงาน'
  WHEN '/contact' THEN 'ติดต่อเรา'
  ELSE title
END
WHERE url IN ('/', '/about', '/products', '/portfolio', '/contact');
