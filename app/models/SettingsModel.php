<?php

declare(strict_types=1);

final class SettingsModel extends Model
{
    public function get(): array
    {
        $stmt = $this->db->prepare(
            'SELECT s.*, logo.filepath AS logo_path, favicon.filepath AS favicon_path
             FROM settings s
             LEFT JOIN media logo ON logo.id = s.logo_media_id AND logo.deleted_at IS NULL
             LEFT JOIN media favicon ON favicon.id = s.favicon_media_id AND favicon.deleted_at IS NULL
             ORDER BY s.id ASC LIMIT 1'
        );
        $stmt->execute();
        return $stmt->fetch() ?: ['site_name' => 'Mae Klong Graphic', 'site_description' => ''];
    }

    public function update(array $data): void
    {
        $existing = $this->get();
        $payload = [
            ':site_name' => trim((string) $data['site_name']),
            ':site_description' => trim((string) ($data['site_description'] ?? '')),
            ':phone' => trim((string) ($data['phone'] ?? '')),
            ':email' => trim((string) ($data['email'] ?? '')),
            ':line_url' => normalize_line_url($data['line_url'] ?? ''),
            ':facebook_url' => trim((string) ($data['facebook_url'] ?? '')),
            ':address' => trim((string) ($data['address'] ?? '')),
            ':google_map_embed' => trim((string) ($data['google_map_embed'] ?? '')),
            ':logo_media_id' => $this->nullableInt($data['logo_media_id'] ?? null),
            ':favicon_media_id' => $this->nullableInt($data['favicon_media_id'] ?? null),
            ':updated_at' => $this->now(),
        ];

        if (!empty($existing['id'])) {
            $payload[':id'] = (int) $existing['id'];
            $stmt = $this->db->prepare(
                'UPDATE settings SET site_name = :site_name, site_description = :site_description, phone = :phone,
                 email = :email, line_url = :line_url, facebook_url = :facebook_url, address = :address,
                 google_map_embed = :google_map_embed, logo_media_id = :logo_media_id,
                 favicon_media_id = :favicon_media_id, updated_at = :updated_at WHERE id = :id'
            );
            $stmt->execute($payload);
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO settings (site_name, site_description, phone, email, line_url, facebook_url, address,
             google_map_embed, logo_media_id, favicon_media_id, updated_at)
             VALUES (:site_name, :site_description, :phone, :email, :line_url, :facebook_url, :address,
             :google_map_embed, :logo_media_id, :favicon_media_id, :updated_at)'
        );
        $stmt->execute($payload);
    }

    private function nullableInt(mixed $value): ?int
    {
        $value = (int) $value;
        return $value > 0 ? $value : null;
    }
}
