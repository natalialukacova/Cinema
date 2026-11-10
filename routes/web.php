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

$router->get("/admin/showings", function () use ($adminShowingController) {
    $adminShowingController->index();
});

$router->get("/admin/showings/create", function () use ($adminShowingController) {
    $adminShowingController->create();
});

$router->post("/admin/showings/create", function () use ($adminShowingController) {
    $adminShowingController->store();
});

$router->get("/admin/showings/edit", function () use ($adminShowingController) {
    $adminShowingController->edit();
});

$router->post("/admin/showings/update", function () use ($adminShowingController) {
    $adminShowingController->update();
});

$router->post("/admin/showings/delete", function () use ($adminShowingController) {
    $adminShowingController->delete();
});

$router->get("/admin/news", function () use ($adminNewsController) {
    $adminNewsController->index();
});

$router->get("/admin/news/create", function () use ($adminNewsController) {
    $adminNewsController->create();
});

$router->post("/admin/news/create", function () use ($adminNewsController) {
    $adminNewsController->store();
});

$router->get("/admin/news/edit", function () use ($adminNewsController) {
    $adminNewsController->edit();
});

$router->post("/admin/news/update", function () use ($adminNewsController) {
    $adminNewsController->update();
});

$router->post("/admin/news/delete", function () use ($adminNewsController) {
    $adminNewsController->delete();
});

$router->get("/news", function () use ($newsController) {
    $newsController->index();
});

$router->notFound(function () {
    echo "<h1>404</h1>";
    echo "<p>Page not found.</p>";
});