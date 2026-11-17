<?php

namespace App\Repositories;

use PDO;
use App\Interfaces\VenueRepositoryInterface;
use App\Models\Venue;

class VenueRepository implements VenueRepositoryInterface
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
                v.companyID,
                v.name,
                v.description,
                v.isActive,
                COUNT(s.seatID) AS seatCount
             FROM Venue v
             LEFT JOIN Seat s
                ON v.venueID = s.venueID
             WHERE v.isActive = 1
             GROUP BY
                v.venueID,
                v.companyID,
                v.name,
                v.description,
                v.isActive
             ORDER BY v.name"
        );

        return $this->mapRowsToVenues(
            $statement->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    private function mapRowToVenue(array $row): Venue
    {
        // Convert database row into a Venue object
        return new Venue(
            (int) $row["venueID"],
            (int) $row["companyID"],
            $row["name"],
            $row["description"],
            (bool) $row["isActive"],
            (int) $row["seatCount"]
        );
    }

    private function mapRowsToVenues(array $rows): array
    {
        return array_map(
            fn(array $row): Venue => $this->mapRowToVenue($row),
            $rows
        );
    }
}
