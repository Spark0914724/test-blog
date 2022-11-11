<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use PDO;
use Smarty\Smarty;

final class HomeController
{
    private const RECENT_POSTS_COUNT = 3;

    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

    public function index(): void
    {
        $categoryRepo = new CategoryRepository($this->pdo);
        $articleRepo = new ArticleRepository($this->pdo);

        $categories = $categoryRepo->getCategoriesWithArticleCount();
        $categoriesWithRecent = [];

        foreach ($categories as $cat) {
            $categoriesWithRecent[] = [
                'category' => $cat,
                'recent_posts' => $articleRepo->getRecentByCategoryId($cat['id'], self::RECENT_POSTS_COUNT),
            ];
        }

        $this->smarty->assign('categoriesWithRecent', $categoriesWithRecent);
        $this->smarty->assign('pageTitle', 'Home');
        $this->smarty->display('home.tpl');
    }
}
