<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use App\Interfaces\ShowingRepositoryInterface;
use App\Models\Movie;

class MovieService
{
    public function __construct(
        private MovieRepositoryInterface $movieRepository,
        private ShowingRepositoryInterface $showingRepository
    ) {
    }

    public function getAllMovies(): array
    {
        return $this->movieRepository->findAll();
    }

    public function getMovieByID(int $movieID): ?Movie
    {
        return $this->movieRepository->findByID($movieID);
    }

    public function getUpcomingShowingsByMovieID(int $movieID): array
    {
        return $this->showingRepository
            ->findUpcomingByMovieID($movieID);
    }
}
