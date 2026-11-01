<?php

namespace App\Repositories;

use PDO;

class VenueRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findAllActive(): array
    {
        $statement = $this->connection->query(
            "SELECT
                v.venueID,
                v.name,
                v.description,
                COUNT(s.seatID) AS seatCount
             FROM Venue v
             LEFT JOIN Seat s
                ON v.venueID = s.venueID
             WHERE v.isActive = 1
             GROUP BY
                v.venueID,
                v.name,
                v.description
             ORDER BY v.name"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
