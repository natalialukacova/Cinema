<?php

namespace App\Repositories;

use PDO;

class ShowingRepository
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
}
