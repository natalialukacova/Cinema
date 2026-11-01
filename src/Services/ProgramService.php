<?php

namespace App\Services;

use App\Repositories\VenueRepository;
use App\Repositories\ShowingRepository;

class ProgramService
{
    public function __construct(
        private VenueRepository $venueRepository,
        private ShowingRepository $showingRepository
    ) {
    }

    public function getVenues(): array
    {
        return $this->venueRepository->findAllActive();
    }

    public function getUpcomingShowings(): array
    {
        return $this->showingRepository->findUpcomingAll();
    }
}
