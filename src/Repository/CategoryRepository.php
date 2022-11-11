<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(private PDO $pdo) {}

   

    /**
     * @return array{id: int, title: string, description: string|null}|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, title, description FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['id'] = (int) $row['id'];
        return $row;
    }
}
