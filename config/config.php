<?php

declare(strict_types=1);

return [
    'db' => [
        'host'     => getenv('DB_HOST') ?: '127.0.0.1',
        'port'     => (int) (getenv('DB_PORT') ?: 3306),
        'dbname'   => getenv('DB_NAME') ?: 'blog',
        'username' => getenv('DB_USER') ?: 'blog',
        'password' => getenv('DB_PASS') ?: 'blog',
        'charset'  => 'utf8mb4',
    ],
    'app' => [
        'base_path' => dirname(__DIR__),
        'per_page'  => 6,
    ],
];
