<?php

declare(strict_types=1);

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}
