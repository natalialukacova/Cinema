<?php

use App\Controllers\Website\HomeController;

require_once __DIR__
    . "/../src/Controllers/Website/HomeController.php";

$homeController = new HomeController();

$router->get("/", function () use ($homeController) {
    $homeController->index();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});