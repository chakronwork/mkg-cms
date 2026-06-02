<?php

declare(strict_types=1);

final class AdminModel extends Model
{
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM admins WHERE username = :username AND deleted_at IS NULL');
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
}
