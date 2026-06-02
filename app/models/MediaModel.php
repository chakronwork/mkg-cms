<?php

declare(strict_types=1);

final class MediaModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM media WHERE deleted_at IS NULL ORDER BY created_at DESC, id DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO media (filename, filepath, mime_type, alt_text, file_size, created_at)
             VALUES (:filename, :filepath, :mime_type, :alt_text, :file_size, :created_at)'
        );
        $stmt->execute([
            ':filename' => $data['filename'],
            ':filepath' => $data['filepath'],
            ':mime_type' => $data['mime_type'],
            ':alt_text' => $data['alt_text'] ?? '',
            ':file_size' => (int) $data['file_size'],
            ':created_at' => $this->now(),
        ]);
    }

    public function updateAlt(int $id, string $altText): void
    {
        $stmt = $this->db->prepare('UPDATE media SET alt_text = :alt_text WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':alt_text' => $altText, ':id' => $id]);
    }

    public function softDelete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE media SET deleted_at = :deleted_at WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':deleted_at' => $this->now(), ':id' => $id]);
    }
}
