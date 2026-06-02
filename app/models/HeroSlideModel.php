<?php

declare(strict_types=1);

final class HeroSlideModel extends Model
{
    public function all(bool $activeOnly = false): array
    {
        $sql = 'SELECT hs.*, m.filepath, m.alt_text
                FROM hero_slides hs
                INNER JOIN media m ON m.id = hs.media_id AND m.deleted_at IS NULL';
        if ($activeOnly) {
            $sql .= ' WHERE hs.is_active = 1';
        }

        $stmt = $this->db->prepare($sql . ' ORDER BY hs.sort_order ASC, hs.id ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function save(array $data, ?int $id = null): void
    {
        $payload = [
            ':media_id' => (int) ($data['media_id'] ?? 0),
            ':title' => trim((string) ($data['title'] ?? '')),
            ':subtitle' => trim((string) ($data['subtitle'] ?? '')),
            ':link_url' => trim((string) ($data['link_url'] ?? '')),
            ':link_label' => trim((string) ($data['link_label'] ?? '')),
            ':sort_order' => (int) ($data['sort_order'] ?? 0),
            ':is_active' => !empty($data['is_active']) ? 1 : 0,
            ':updated_at' => $this->now(),
        ];

        if ($id !== null) {
            $payload[':id'] = $id;
            $stmt = $this->db->prepare(
                'UPDATE hero_slides
                 SET media_id = :media_id, title = :title, subtitle = :subtitle, link_url = :link_url,
                     link_label = :link_label, sort_order = :sort_order, is_active = :is_active,
                     updated_at = :updated_at
                 WHERE id = :id'
            );
            $stmt->execute($payload);
            return;
        }

        $payload[':created_at'] = $this->now();
        $stmt = $this->db->prepare(
            'INSERT INTO hero_slides
             (media_id, title, subtitle, link_url, link_label, sort_order, is_active, created_at, updated_at)
             VALUES
             (:media_id, :title, :subtitle, :link_url, :link_label, :sort_order, :is_active, :created_at, :updated_at)'
        );
        $stmt->execute($payload);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM hero_slides WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
