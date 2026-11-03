<?php

namespace App\Repositories;

use App\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;
use PDO;

class MovieRepository implements MovieRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $statement = $this->connection->query(
            "SELECT *
             FROM Movie
             ORDER BY title"
        );

        $rows = $statement->fetchAll();
        $movies = [];

        foreach ($rows as $row) {
            $movies[] = $this->createMovieFromRow($row);
        }

        return $movies;
    }

    public function findByID(int $movieID): ?Movie
    {
        $statement = $this->connection->prepare(
            "SELECT *
             FROM Movie
             WHERE movieID = :movieID"
        );

        $statement->execute([
            "movieID" => $movieID
        ]);

        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return $this->createMovieFromRow($row);
    }

    private function createMovieFromRow(array $row): Movie
    {
        return new Movie(
            (int) $row["movieID"],
            $row["title"],
            $row["releaseDate"],
            (int) $row["durationMinutes"],
            $row["description"],
            $row["ageRating"],
            $row["imageURL"]
        );
    }
    
    public function create(Movie $movie): int
    {
        $statement = $this->connection->prepare(
            "INSERT INTO Movie (
                title, releaseDate, durationMinutes,
                description, ageRating, imageURL
            ) VALUES (
                :title, :releaseDate, :durationMinutes,
                :description, :ageRating, :imageURL
            )"
        );

        $statement->execute([
            "title" => $movie->getTitle(),
            "releaseDate" => $movie->getReleaseDate(),
            "durationMinutes" => $movie->getDurationMinutes(),
            "description" => $movie->getDescription(),
            "ageRating" => $movie->getAgeRating(),
            "imageURL" => $movie->getImageURL()
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(Movie $movie): void
    {
        $statement = $this->connection->prepare(
            "UPDATE Movie SET
                title = :title,
                releaseDate = :releaseDate,
                durationMinutes = :durationMinutes,
                description = :description,
                ageRating = :ageRating,
                imageURL = :imageURL
            WHERE movieID = :movieID"
        );

        $statement->execute([
            "movieID" => $movie->getMovieID(),
            "title" => $movie->getTitle(),
            "releaseDate" => $movie->getReleaseDate(),
            "durationMinutes" => $movie->getDurationMinutes(),
            "description" => $movie->getDescription(),
            "ageRating" => $movie->getAgeRating(),
            "imageURL" => $movie->getImageURL()
        ]);
    }

    public function delete(int $movieID): bool
    {
        // Do not delete films with existing showings
        $statement = $this->connection->prepare(
            "DELETE FROM Movie
            WHERE movieID = :movieID
            AND NOT EXISTS (
                SELECT 1 FROM Showing
                WHERE Showing.movieID = :showingMovieID
            )"
        );

        $statement->execute([
            "movieID" => $movieID,
            "showingMovieID" => $movieID
        ]);

        return $statement->rowCount() > 0;
    }
}