<?php

declare(strict_types=1);

final class ProductModel extends Model
{
    public function all(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, m.filepath AS thumbnail_path FROM products p
             LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.sort_order = (
                SELECT MIN(pi2.sort_order) FROM product_images pi2 WHERE pi2.product_id = p.id
             )
             LEFT JOIN media m ON m.id = pi.media_id AND m.deleted_at IS NULL
             WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC, p.id DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM products WHERE deleted_at IS NULL');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function paginate(int $limit, int $offset): array
    {
        return $this->all($limit, $offset);
    }

    public function published(): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, m.filepath AS thumbnail_path FROM products p
             LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.sort_order = (
                SELECT MIN(pi2.sort_order) FROM product_images pi2 WHERE pi2.product_id = p.id
             )
             LEFT JOIN media m ON m.id = pi.media_id AND m.deleted_at IS NULL
             WHERE p.status = "published" AND p.deleted_at IS NULL ORDER BY p.created_at DESC, p.id DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findPublishedBySlug(string $slug): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, qr.filepath AS qr_code_path FROM products p
             LEFT JOIN media qr ON qr.id = p.qr_code_media_id AND qr.deleted_at IS NULL
             WHERE p.slug = :slug AND p.status = "published" AND p.deleted_at IS NULL'
        );
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function images(int $productId): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, pi.sort_order FROM product_images pi
             INNER JOIN media m ON m.id = pi.media_id
             WHERE pi.product_id = :product_id AND m.deleted_at IS NULL ORDER BY pi.sort_order ASC, pi.id ASC'
        );
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM products WHERE slug = :slug AND deleted_at IS NULL';
        $params = [':slug' => $slug];
        if ($exceptId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function save(array $data, array $mediaIds, ?int $id = null): int
    {
        $payload = $this->payload($data);
        if ($id !== null) {
            $payload[':id'] = $id;
            $stmt = $this->db->prepare(
                'UPDATE products SET name = :name, slug = :slug, description = :description, line_url = :line_url,
                 qr_code_media_id = :qr_code_media_id, seo_title = :seo_title, seo_description = :seo_description,
                 status = :status, updated_at = :updated_at WHERE id = :id AND deleted_at IS NULL'
            );
            $stmt->execute($payload);
            $this->syncImages($id, $mediaIds);
            return $id;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO products (name, slug, description, line_url, qr_code_media_id, seo_title, seo_description,
             status, created_at, updated_at) VALUES (:name, :slug, :description, :line_url, :qr_code_media_id,
             :seo_title, :seo_description, :status, :created_at, :updated_at)'
        );
        $payload[':created_at'] = $this->now();
        $stmt->execute($payload);
        $newId = (int) $this->db->lastInsertId();
        $this->syncImages($newId, $mediaIds);
        return $newId;
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE products SET deleted_at = :deleted_at WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':deleted_at' => $this->now(), ':id' => $id]);
    }

    private function payload(array $data): array
    {
        $qrId = (int) ($data['qr_code_media_id'] ?? 0);
        return [
            ':name' => trim((string) $data['name']),
            ':slug' => trim((string) $data['slug']),
            ':description' => trim((string) ($data['description'] ?? '')),
            ':line_url' => normalize_line_url($data['line_url'] ?? ''),
            ':qr_code_media_id' => $qrId > 0 ? $qrId : null,
            ':seo_title' => trim((string) ($data['seo_title'] ?? '')),
            ':seo_description' => trim((string) ($data['seo_description'] ?? '')),
            ':status' => in_array($data['status'] ?? 'published', ['published', 'draft'], true) ? $data['status'] : 'draft',
            ':updated_at' => $this->now(),
        ];
    }

    private function syncImages(int $productId, array $mediaIds): void
    {
        $stmt = $this->db->prepare('DELETE FROM product_images WHERE product_id = :product_id');
        $stmt->execute([':product_id' => $productId]);
        $stmt = $this->db->prepare('INSERT INTO product_images (product_id, media_id, sort_order) VALUES (:product_id, :media_id, :sort_order)');
        foreach (array_values(array_filter(array_map('intval', $mediaIds))) as $index => $mediaId) {
            $stmt->execute([':product_id' => $productId, ':media_id' => $mediaId, ':sort_order' => ($index + 1) * 10]);
        }
    }
}
