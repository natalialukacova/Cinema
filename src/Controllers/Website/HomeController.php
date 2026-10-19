<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\MovieService;

class HomeController
{
    private MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(): void
    {
        $movies = $this->movieService->getAllMovies();

        View::render(
            "pages/Website/home",
            [
                "title" => "Cinema",
                "movies" => $movies
            ]
        );
    }
}