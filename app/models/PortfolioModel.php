<?php

declare(strict_types=1);

final class PortfolioModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, m.filepath AS thumbnail_path FROM portfolios p
             LEFT JOIN portfolio_images pi ON pi.portfolio_id = p.id AND pi.sort_order = (
                SELECT MIN(pi2.sort_order) FROM portfolio_images pi2 WHERE pi2.portfolio_id = p.id
             )
             LEFT JOIN media m ON m.id = pi.media_id AND m.deleted_at IS NULL
             WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC, p.id DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function published(): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.*, m.filepath AS thumbnail_path FROM portfolios p
             LEFT JOIN portfolio_images pi ON pi.portfolio_id = p.id AND pi.sort_order = (
                SELECT MIN(pi2.sort_order) FROM portfolio_images pi2 WHERE pi2.portfolio_id = p.id
             )
             LEFT JOIN media m ON m.id = pi.media_id AND m.deleted_at IS NULL
             WHERE p.status = "published" AND p.deleted_at IS NULL ORDER BY p.created_at DESC, p.id DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM portfolios WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findPublishedBySlug(string $slug): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM portfolios WHERE slug = :slug AND status = "published" AND deleted_at IS NULL');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function images(int $portfolioId): array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, pi.sort_order FROM portfolio_images pi
             INNER JOIN media m ON m.id = pi.media_id
             WHERE pi.portfolio_id = :portfolio_id AND m.deleted_at IS NULL ORDER BY pi.sort_order ASC, pi.id ASC'
        );
        $stmt->execute([':portfolio_id' => $portfolioId]);
        return $stmt->fetchAll();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM portfolios WHERE slug = :slug AND deleted_at IS NULL';
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
        $payload = [
            ':title' => trim((string) $data['title']),
            ':slug' => trim((string) $data['slug']),
            ':description' => trim((string) ($data['description'] ?? '')),
            ':seo_title' => trim((string) ($data['seo_title'] ?? '')),
            ':seo_description' => trim((string) ($data['seo_description'] ?? '')),
            ':status' => in_array($data['status'] ?? 'published', ['published', 'draft'], true) ? $data['status'] : 'draft',
            ':updated_at' => $this->now(),
        ];
        if ($id !== null) {
            $payload[':id'] = $id;
            $stmt = $this->db->prepare(
                'UPDATE portfolios SET title = :title, slug = :slug, description = :description, seo_title = :seo_title,
                 seo_description = :seo_description, status = :status, updated_at = :updated_at WHERE id = :id AND deleted_at IS NULL'
            );
            $stmt->execute($payload);
            $this->syncImages($id, $mediaIds);
            return $id;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO portfolios (title, slug, description, seo_title, seo_description, status, created_at, updated_at)
             VALUES (:title, :slug, :description, :seo_title, :seo_description, :status, :created_at, :updated_at)'
        );
        $payload[':created_at'] = $this->now();
        $stmt->execute($payload);
        $newId = (int) $this->db->lastInsertId();
        $this->syncImages($newId, $mediaIds);
        return $newId;
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE portfolios SET deleted_at = :deleted_at WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':deleted_at' => $this->now(), ':id' => $id]);
    }

    private function syncImages(int $portfolioId, array $mediaIds): void
    {
        $stmt = $this->db->prepare('DELETE FROM portfolio_images WHERE portfolio_id = :portfolio_id');
        $stmt->execute([':portfolio_id' => $portfolioId]);
        $stmt = $this->db->prepare('INSERT INTO portfolio_images (portfolio_id, media_id, sort_order) VALUES (:portfolio_id, :media_id, :sort_order)');
        foreach (array_values(array_filter(array_map('intval', $mediaIds))) as $index => $mediaId) {
            $stmt->execute([':portfolio_id' => $portfolioId, ':media_id' => $mediaId, ':sort_order' => ($index + 1) * 10]);
        }
    }
}
