<?php

namespace App\Services;

use App\Interfaces\VenueRepositoryInterface;
use App\Interfaces\ShowingRepositoryInterface;

class ProgramService
{
    public function __construct(
        private VenueRepositoryInterface $venueRepository,
        private ShowingRepositoryInterface $showingRepository
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
