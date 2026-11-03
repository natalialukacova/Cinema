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

$router->get("/venues", function () use ($programController) {
    $programController->venues();
});

$router->get("/showings", function () use ($programController) {
    $programController->showings();
});

$router->get("/booking", function () use ($bookingController) {
    $bookingController->show();
});

$router->post("/booking", function () use ($bookingController) {
    $bookingController->create();
});

$router->get("/booking-confirmation", function () use ($bookingController) {
    $bookingController->confirmation();
});

$router->get("/my-bookings", function () use ($bookingController) {
    $bookingController->myBookings();
});

$router->get("/checkout", function () use ($bookingController) {
    $bookingController->checkout();
});

$router->get("/admin", function () use ($adminController) {
    $adminController->dashboard();
});

$router->get("/admin/bookings", function () use ($adminController) {
    $adminController->bookings();
});

$router->get("/admin/movies", function () use ($adminMovieController) {
    $adminMovieController->index();
});

$router->get("/admin/movies/create", function () use ($adminMovieController) {
    $adminMovieController->create();
});

$router->post("/admin/movies/create", function () use ($adminMovieController) {
    $adminMovieController->store();
});

$router->get("/admin/movies/edit", function () use ($adminMovieController) {
    $adminMovieController->edit();
});

$router->post("/admin/movies/update", function () use ($adminMovieController) {
    $adminMovieController->update();
});

$router->post("/admin/movies/delete", function () use ($adminMovieController) {
    $adminMovieController->delete();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});