<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use PDO;
use Smarty\Smarty;

final class CategoryController
{
    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

    public function show(int $id): void
    {
        $categoryRepo = new CategoryRepository($this->pdo);
        $articleRepo = new ArticleRepository($this->pdo);

        $category = $categoryRepo->getById($id);
        if ($category === null) {
            header('Location: index.php?page=home', true, 302);
            exit;
        }

        $perPage = $this->config['app']['per_page'];
        $page = max(1, (int) ($_GET['p'] ?? 1));

        $rawSort = $_GET['sort'] ?? null;
        if ($rawSort === null || $rawSort === '') {
            // No sort specified: default to views
            $sort = 'views';
        } else {
            // Explicit sort: only 'views' or 'date' are allowed; anything else falls back to date
            $sort = $rawSort === 'views' ? 'views' : 'date';
        }
        $dirParam = strtolower((string) ($_GET['dir'] ?? 'desc'));
        $direction = $dirParam === 'asc' ? 'asc' : 'desc';

        $total = $articleRepo->countByCategoryId($id);
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $articles = $articleRepo->getByCategoryId($id, $perPage, $offset, $sort, $direction);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('currentPage', $page);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('total', $total);
        $this->smarty->assign('sort', $sort);
        $this->smarty->assign('direction', $direction);
        $this->smarty->assign('pageTitle', $category['title']);
        $this->smarty->display('category.tpl');
    }
}
