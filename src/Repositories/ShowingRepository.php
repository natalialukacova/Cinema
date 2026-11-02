<?php

namespace App\Repositories;

use PDO;
use App\Interfaces\ShowingRepositoryInterface;

class ShowingRepository implements ShowingRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findUpcomingByMovieID(int $movieID): array
    {
        $statement = $this->connection->prepare(
            "SELECT
                s.showingID,
                s.startDateTime,
                s.basePrice,
                v.name AS venueName
             FROM Showing s
             INNER JOIN Venue v
                ON s.venueID = v.venueID
             WHERE s.movieID = :movieID
               AND s.status = 'scheduled'
               AND s.startDateTime >= NOW()
               AND v.isActive = 1
             ORDER BY s.startDateTime ASC"
        );

        $statement->execute([
            "movieID" => $movieID
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findUpcomingAll(): array
    {
        $statement = $this->connection->query(
            "SELECT
                s.showingID,
                s.movieID,
                s.startDateTime,
                s.basePrice,
                m.title AS movieTitle,
                v.name AS venueName
            FROM Showing s
            INNER JOIN Movie m
                ON s.movieID = m.movieID
            INNER JOIN Venue v
                ON s.venueID = v.venueID
            WHERE s.status = 'scheduled'
            AND s.startDateTime >= NOW()
            AND v.isActive = 1
            ORDER BY s.startDateTime ASC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
