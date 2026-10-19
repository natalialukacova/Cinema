<?php

use App\Core\Database;
use App\Core\Router;

require_once __DIR__ . "/../src/Core/Database.php";
require_once __DIR__ . "/../src/Core/Router.php";

$config = require __DIR__ . "/../config/database.php";

try {
    $database = new Database($config);
    $pdo = $database->getConnection();
} catch (PDOException $error) {
    die("Database connection failed: " . $error->getMessage());
}

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