<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class ArticleRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * Get N most recent articles in a category (by published_at).
     * @return list<array{id: int, image: string|null, title: string, description: string|null, published_at: string|null, views: int}>
     */
    public function getRecentByCategoryId(int $categoryId, int $limit = 3): array
    {
        $sql = <<<'SQL'
            SELECT a.id, a.image, a.title, a.description, a.published_at, a.views
            FROM articles a
            INNER JOIN article_categories ac ON ac.article_id = a.id AND ac.category_id = ?
            WHERE a.published_at IS NOT NULL
            ORDER BY a.published_at DESC
            LIMIT ?
        SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->castArticleList($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get paginated articles for a category with optional sort.
     * $sort: "date" or "views"
     * $direction: "asc" or "desc"
     * @return list<array<string, mixed>>
     */
    public function getByCategoryId(
        int $categoryId,
        int $limit,
        int $offset,
        string $sort = 'date',
        string $direction = 'desc'
    ): array {
        $direction = strtolower($direction) === 'asc' ? 'ASC' : 'DESC';
        if ($sort === 'views') {
            $orderBy = "a.views {$direction}, a.published_at {$direction}";
        } else {
            $orderBy = "a.published_at {$direction}, a.views {$direction}";
        }
        $sql = <<<SQL
            SELECT a.id, a.image, a.title, a.description, a.published_at, a.views
            FROM articles a
            INNER JOIN article_categories ac ON ac.article_id = a.id AND ac.category_id = ?
            WHERE a.published_at IS NOT NULL
            ORDER BY {$orderBy}
            LIMIT ? OFFSET ?
        SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $this->castArticleList($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countByCategoryId(int $categoryId): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM article_categories ac
             INNER JOIN articles a ON a.id = ac.article_id AND a.published_at IS NOT NULL
             WHERE ac.category_id = ?'
        );
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @return array{id: int, image: string|null, title: string, description: string|null, text: string, views: int, published_at: string|null, categories: list<array{id: int, title: string}>}|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, image, title, description, text, views, published_at FROM articles WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $row['id'] = (int) $row['id'];
        $row['views'] = (int) $row['views'];
        $row['categories'] = $this->getCategoriesForArticle((int) $row['id']);
        return $row;
    }

    /**
     * Increment view count for an article.
     */
    public function incrementViews(int $id): void
    {
        $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = ?')->execute([$id]);
    }

    /**
     * Get 3 similar articles (same category, exclude current, by date).
     * @return list<array{id: int, image: string|null, title: string, description: string|null, published_at: string|null}>
     */
    public function getSimilar(int $articleId, int $limit = 3): array
    {
        $sql = <<<'SQL'
            SELECT a.id, a.image, a.title, a.description, a.published_at
            FROM articles a
            INNER JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id IN (
                SELECT category_id FROM article_categories WHERE article_id = ?
            )
            AND a.id != ?
            AND a.published_at IS NOT NULL
            GROUP BY a.id
            ORDER BY a.published_at DESC
            LIMIT ?
        SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $articleId, PDO::PARAM_INT);
        $stmt->bindValue(2, $articleId, PDO::PARAM_INT);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->castArticleList($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @return list<array{id: int, title: string}>
     */
    private function getCategoriesForArticle(int $articleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.title FROM categories c
             INNER JOIN article_categories ac ON ac.category_id = c.id
             WHERE ac.article_id = ? ORDER BY c.title'
        );
        $stmt->execute([$articleId]);
        $list = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $list[] = ['id' => (int) $r['id'], 'title' => $r['title']];
        }
        return $list;
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<array<string, mixed>>
     */
    private function castArticleList(array $rows): array
    {
        foreach ($rows as &$row) {
            $row['id'] = (int) $row['id'];
            $row['views'] = (int) ($row['views'] ?? 0);
        }
        return $rows;
    }
}
