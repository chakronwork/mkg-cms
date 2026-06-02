<?php

declare(strict_types=1);

final class PageModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE deleted_at IS NULL ORDER BY id ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE id = :id AND deleted_at IS NULL');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findPublishedBySlug(string $slug): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE slug = :slug AND status = "published" AND deleted_at IS NULL');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function sections(int $pageId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM page_sections WHERE page_id = :page_id ORDER BY sort_order ASC, id ASC');
        $stmt->execute([':page_id' => $pageId]);
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data, array $sections): void
    {
        $stmt = $this->db->prepare(
            'UPDATE pages SET title = :title, seo_title = :seo_title, seo_description = :seo_description,
             status = :status, updated_at = :updated_at WHERE id = :id AND deleted_at IS NULL'
        );
        $stmt->execute([
            ':title' => trim((string) $data['title']),
            ':seo_title' => trim((string) ($data['seo_title'] ?? '')),
            ':seo_description' => trim((string) ($data['seo_description'] ?? '')),
            ':status' => in_array($data['status'] ?? 'published', ['published', 'draft'], true) ? $data['status'] : 'draft',
            ':updated_at' => $this->now(),
            ':id' => $id,
        ]);

        foreach ($sections as $sectionId => $section) {
            $stmt = $this->db->prepare(
                'UPDATE page_sections SET section_name = :section_name, content = :content, sort_order = :sort_order,
                 updated_at = :updated_at WHERE id = :id AND page_id = :page_id'
            );
            $stmt->execute([
                ':section_name' => trim((string) ($section['section_name'] ?? '')),
                ':content' => (string) ($section['content'] ?? ''),
                ':sort_order' => (int) ($section['sort_order'] ?? 0),
                ':updated_at' => $this->now(),
                ':id' => (int) $sectionId,
                ':page_id' => $id,
            ]);
        }
    }
}
