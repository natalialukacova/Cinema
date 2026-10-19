<?php

use App\Controllers\Website\HomeController;

$homeController = new HomeController();

$router->get("/", function () use ($homeController) {
    $homeController->index();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});