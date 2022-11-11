<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use PDO;
use Smarty\Smarty;

final class ArticleController
{
    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

    public function show(int $id): void
    {
        $articleRepo = new ArticleRepository($this->pdo);

        $article = $articleRepo->getById($id);
        if ($article === null) {
            header('Location: index.php?page=home', true, 302);
            exit;
        }

        $articleRepo->incrementViews($id);
        $article['views']++;

        $article['body_html'] = nl2br(htmlspecialchars($article['text'], ENT_QUOTES, 'UTF-8'));

        $similar = $articleRepo->getSimilar($id, 3);

        $this->smarty->assign('article', $article);
        $this->smarty->assign('similarArticles', $similar);
        $this->smarty->assign('pageTitle', $article['title']);
        $this->smarty->display('article.tpl');
    }
}
