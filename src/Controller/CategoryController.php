<?php

declare(strict_types=1);

namespace App\Controller;



use PDO;
use Smarty\Smarty;

final class CategoryController
{
    public function __construct(
        private PDO $pdo,
        private Smarty $smarty,
        private array $config
    ) {}

}
