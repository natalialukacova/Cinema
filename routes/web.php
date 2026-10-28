<?php

$router->get("/", function () use ($homeController) {
    $homeController->index();
});

$router->get("/register", function () use ($authController) {
    $authController->showRegister();
});

$router->post("/register", function () use ($authController) {
    $authController->register();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});