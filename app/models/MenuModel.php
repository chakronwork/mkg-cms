<?php

declare(strict_types=1);

final class MenuModel extends Model
{
    public function all(bool $activeOnly = false): array
    {
        $sql = 'SELECT * FROM menus';
        if ($activeOnly) {
            $sql .= ' WHERE is_active = 1';
        }
        $stmt = $this->db->prepare($sql . ' ORDER BY sort_order ASC, id ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM menus WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function save(array $data, ?int $id = null): void
    {
        $payload = [
            ':title' => trim((string) $data['title']),
            ':url' => trim((string) $data['url']),
            ':target' => in_array($data['target'] ?? '_self', ['_self', '_blank'], true) ? $data['target'] : '_self',
            ':sort_order' => (int) ($data['sort_order'] ?? 0),
            ':is_active' => !empty($data['is_active']) ? 1 : 0,
        ];

        if ($id !== null) {
            $payload[':id'] = $id;
            $stmt = $this->db->prepare('UPDATE menus SET title = :title, url = :url, target = :target, sort_order = :sort_order, is_active = :is_active WHERE id = :id');
            $stmt->execute($payload);
            return;
        }

        $stmt = $this->db->prepare('INSERT INTO menus (title, url, target, sort_order, is_active) VALUES (:title, :url, :target, :sort_order, :is_active)');
        $stmt->execute($payload);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM menus WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
