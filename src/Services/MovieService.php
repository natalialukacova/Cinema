<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use App\Interfaces\ShowingRepositoryInterface;
use App\Models\Movie;
use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;

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
    
    public function createMovie(array $data): int
    {
        $movie = $this->validateMovie($data);

        return $this->movieRepository->create($movie);
    }

    public function updateMovie(int $movieID, array $data): void
    {
        if ($this->getMovieByID($movieID) === null) {
            throw new DomainException("Movie not found.");
        }

        $movie = $this->validateMovie($data, $movieID);

        $this->movieRepository->update($movie);
    }

    public function deleteMovie(int $movieID): bool
    {
        return $this->movieRepository->delete($movieID);
    }

    private function validateMovie(
        array $data,
        ?int $movieID = null
    ): Movie {
        $required = [
            "title",
            "releaseDate",
            "durationMinutes",
            "description",
            "ageRating",
            "imageURL"
        ];

        foreach ($required as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field])
            ) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }
        }

        $title = trim($data["title"]);
        $releaseDate = trim($data["releaseDate"]);
        $description = trim($data["description"]);
        $ageRating = trim($data["ageRating"]);
        $imageURL = trim($data["imageURL"]);

        if ($title === "" || strlen($title) > 200) {
            throw new InvalidArgumentException(
                "Title must contain 1-200 characters."
            );
        }

        $date = DateTimeImmutable::createFromFormat(
            "!Y-m-d",
            $releaseDate
        );

        if (
            $date === false ||
            $date->format("Y-m-d") !== $releaseDate
        ) {
            throw new InvalidArgumentException(
                "Enter a valid release date."
            );
        }

        $duration = filter_var(
            $data["durationMinutes"],
            FILTER_VALIDATE_INT,
            ["options" => [
                "min_range" => 1,
                "max_range" => 600
            ]]
        );

        if (!is_int($duration)) {
            throw new InvalidArgumentException(
                "Duration must be between 1 and 600 minutes."
            );
        }

        if (strlen($description) > 10000) {
            throw new InvalidArgumentException(
                "Description is too long."
            );
        }

        if (
            $ageRating === "" ||
            strlen($ageRating) > 10
        ) {
            throw new InvalidArgumentException(
                "Age rating must contain 1-10 characters."
            );
        }

        if ($imageURL !== "") {
            if (
                strlen($imageURL) > 500 ||
                !filter_var($imageURL, FILTER_VALIDATE_URL) ||
                !preg_match("~^https?://~i", $imageURL)
            ) {
                throw new InvalidArgumentException(
                    "Enter a valid HTTP or HTTPS image URL."
                );
            }
        }

        return new Movie(
            $movieID,
            $title,
            $releaseDate,
            $duration,
            $description !== "" ? $description : null,
            $ageRating,
            $imageURL !== "" ? $imageURL : null
        );
    }
}
