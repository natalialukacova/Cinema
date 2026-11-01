<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\ProgramService;

class ProgramController
{
    public function __construct(
        private ProgramService $programService
    ) {
    }

    public function venues(): void
    {
        View::render(
            "pages/website/venues/venue-list",
            [
                "title" => "Cinema Venues",
                "venues" => $this->programService->getVenues()
            ]
        );
    }

    public function showings(): void
    {
        View::render(
            "pages/website/showings/showings-list",
            [
                "title" => "Movie Showings",
                "showings" => $this->programService
                    ->getUpcomingShowings()
            ]
        );
    }
}
