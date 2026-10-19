<?php

use App\Core\Autoloader;
use App\Core\Database;
use App\Core\Router;
use App\Controllers\Website\HomeController;
use App\Repositories\MovieRepository;
use App\Services\MovieService;

require_once __DIR__
    . "/../src/Core/Autoloader.php";

Autoloader::register();

$config = require __DIR__ . "/../config/database.php";

try {
    $database = new Database($config);
    $pdo = $database->getConnection();
} catch (PDOException $error) {
    die("Database connection failed: " . $error->getMessage());
}

$movieRepository = new MovieRepository($pdo);
$movieService = new MovieService($movieRepository);
$homeController = new HomeController($movieService);

$basePath = rtrim(
    dirname($_SERVER["SCRIPT_NAME"]),
    "/\\"
);

$router = new Router(
    $_SERVER["REQUEST_URI"],
    $_SERVER["REQUEST_METHOD"],
    $basePath
);

require __DIR__ . "/../routes/web.php";