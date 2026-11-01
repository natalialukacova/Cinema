<?php

use App\Core\Autoloader;
use App\Core\Database;
use App\Core\Router;
use App\Controllers\Website\HomeController;
use App\Repositories\MovieRepository;
use App\Services\MovieService;
use App\Controllers\Auth\AuthController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Controllers\Website\ProfileController;
use App\Controllers\Website\MovieController;
use App\Repositories\ShowingRepository;


session_set_cookie_params([
    "httponly" => true,
    "secure" => !empty($_SERVER["HTTPS"])
        && $_SERVER["HTTPS"] !== "off",
    "samesite" => "Lax"
]);

session_start();

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

$userRepository = new UserRepository($pdo);
$userService = new UserService($userRepository);
$authController = new AuthController($userService);

$profileController = new ProfileController($userService);

$showingRepository = new ShowingRepository($pdo);

$movieController = new MovieController(
    $movieService,
    $showingRepository
);

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