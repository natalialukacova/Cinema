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

$router->get("/login", function () use ($authController) {
    $authController->showLogin();
});

$router->post("/login", function () use ($authController) {
    $authController->login();
});

$router->post("/logout", function () use ($authController) {
    $authController->logout();
});

$router->get("/profile", function () use ($profileController) {
    $profileController->index();
});

$router->get("/movie", function () use ($movieController) {
    $movieController->show();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});