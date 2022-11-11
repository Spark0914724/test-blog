<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return list<array{id: int, title: string, description: string|null, article_count: int}>
     */
    public function getCategoriesWithArticleCount(): array
    {
        $sql = <<<'SQL'
            SELECT c.id, c.title, c.description,
                   COUNT(ac.article_id) AS article_count
            FROM categories c
            LEFT JOIN article_categories ac ON ac.category_id = c.id
            GROUP BY c.id
            HAVING article_count > 0
            ORDER BY c.title
        SQL;
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row['id'] = (int) $row['id'];
            $row['article_count'] = (int) $row['article_count'];
        }
        return $rows;
    }

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
