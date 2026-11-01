<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;

class MovieService
{
    private MovieRepositoryInterface $movieRepository;

    public function __construct(
        MovieRepositoryInterface $movieRepository
    ) {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMovies(): array
    {
        return $this->movieRepository->findAll();
    }

    public function getMovieByID(int $movieID): ?\App\Models\Movie
    {
        return $this->movieRepository->findByID($movieID);
    }
}