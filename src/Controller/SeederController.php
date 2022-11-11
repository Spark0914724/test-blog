<?php

declare(strict_types=1);

namespace App\Controller;

use PDO;
use Smarty\Smarty;

final class SeederController
{
    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

    public function run(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->pdo->exec('TRUNCATE TABLE article_categories');
        $this->pdo->exec('TRUNCATE TABLE articles');
        $this->pdo->exec('TRUNCATE TABLE categories');
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $this->seedCategories();
        $this->seedArticles();

        header('Content-Type: text/plain; charset=utf-8');
        echo "Seeding completed. Categories and articles have been added.\n";
    }

    private function seedCategories(): void
    {
        $categories = [
            ['Technology', 'Articles about software, programming, and tech trends.'],
            ['Travel', 'Travel guides, tips, and destination stories.'],
            ['Lifestyle', 'Health, wellness, and everyday life.'],
        ];

        $stmt = $this->pdo->prepare('INSERT INTO categories (title, description) VALUES (?, ?)');
        foreach ($categories as $c) {
            $stmt->execute([$c[0], $c[1]]);
        }
    }

    private function seedArticles(): void
    {
        $categoryIds = $this->pdo->query('SELECT id FROM categories ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
        if (empty($categoryIds)) {
            return;
        }

        $articles = [
            ['/uploads/tech1.jpg', 'Getting Started with PHP 8', 'A quick overview of PHP 8 features.', 'PHP 8 brings many improvements...', [0]],
            ['/uploads/tech2.jpg', 'Understanding MySQL Indexes', 'How indexes speed up your queries.', 'Indexes are crucial for performance...', [0]],
            ['/uploads/tech3.jpg', 'Smarty Templates in Practice', 'Clean separation of logic and view.', 'Smarty helps you keep PHP out of HTML...', [0]],
            ['/uploads/travel1.jpg', 'Best Beaches in Europe', 'Top 5 European beach destinations.', 'From Portugal to Greece...', [1]],
            ['/uploads/travel2.jpg', 'Weekend in Paris', 'A short guide to Paris.', 'Two days in the city of light...', [1]],
            ['/uploads/life1.jpg', 'Morning Routine Tips', 'Start your day the right way.', 'A good morning sets the tone...', [2]],
            ['/uploads/life2.jpg', 'Minimalism at Home', 'Declutter your space.', 'Less stuff means less stress...', [2]],
            ['/uploads/tech4.jpg', 'Docker for PHP Developers', 'Run your stack in containers.', 'Docker simplifies local development...', [0, 1]],
        ];

        $ins = $this->pdo->prepare(
            'INSERT INTO articles (image, title, description, text, views, published_at) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $link = $this->pdo->prepare('INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)');

        $baseDate = time() - 86400 * 30;
        foreach ($articles as $i => $a) {
            $published = date('Y-m-d H:i:s', $baseDate + $i * 86400 * 2);
            $ins->execute([$a[0], $a[1], $a[2], $a[3], rand(10, 500), $published]);
            $articleId = (int) $this->pdo->lastInsertId();
            foreach ($a[4] as $catIndex) {
                $link->execute([$articleId, $categoryIds[$catIndex]]);
            }
        }
    }
}
