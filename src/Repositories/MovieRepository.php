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
}