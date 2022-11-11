<?php

declare(strict_types=1);

use App\Config\Database;

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\SeederController;
use Smarty\Smarty;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';
$basePath = $config['app']['base_path'];

foreach (['templates_c', 'cache'] as $dir) {
    $path = $basePath . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

$smarty = new Smarty();
$smarty->setTemplateDir($basePath . '/templates');
$smarty->setCompileDir($basePath . '/templates_c');
$smarty->setConfigDir($basePath . '/configs');
$smarty->setCacheDir($basePath . '/cache');
$smarty->setEscapeHtml(true);

$pdo = Database::getConnection($config['db']);

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

if ($action === 'seed') {
    $controller = new SeederController($pdo, $smarty, $config);
    $controller->run();
    exit;
}

switch ($page) {
    case 'home':
        $controller = new HomeController($pdo, $smarty, $config);
        $controller->index();
        break;
    case 'category':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $controller = new CategoryController($pdo, $smarty, $config);
        $controller->show($id);
        break;
    default:
        header('Location: index.php?page=home', true, 302);
        exit;
}
