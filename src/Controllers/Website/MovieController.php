<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\MovieService;

class MovieController
{
    public function __construct(
        private MovieService $movieService
    ) {
    }

    public function show(): void
    {
        $movieID = filter_input(
            INPUT_GET,
            "id",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!$movieID) {
            http_response_code(404);
            echo "Movie not found.";
            return;
        }

        $movie = $this->movieService->getMovieByID($movieID);

        if ($movie === null) {
            http_response_code(404);
            echo "Movie not found.";
            return;
        }

        $showings = $this->movieService
            ->getUpcomingShowingsByMovieID($movieID);

        View::render(
            "pages/website/movies/movie-details",
            [
                "title" => $movie->getTitle(),
                "movie" => $movie,
                "showings" => $showings
            ]
        );
    }
}
