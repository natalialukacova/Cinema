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
    
    public function findAllForAdmin(): array
    {
        $statement = $this->connection->query(
            "SELECT
                s.showingID,
                s.movieID,
                s.venueID,
                s.startDateTime,
                s.basePrice,
                s.isPremiere,
                s.status,
                m.title AS movieTitle,
                v.name AS venueName
            FROM Showing s
            JOIN Movie m ON s.movieID = m.movieID
            JOIN Venue v ON s.venueID = v.venueID
            ORDER BY s.startDateTime DESC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByID(int $showingID): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT *
            FROM Showing
            WHERE showingID = :showingID"
        );

        $statement->execute([
            "showingID" => $showingID
        ]);

        $showing = $statement->fetch(PDO::FETCH_ASSOC);

        return $showing ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->connection->prepare(
            "INSERT INTO Showing (
                movieID, venueID, startDateTime,
                basePrice, isPremiere, status
            ) VALUES (
                :movieID, :venueID, :startDateTime,
                :basePrice, :isPremiere, :status
            )"
        );

        $statement->execute($data);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $showingID, array $data): void
    {
        $statement = $this->connection->prepare(
            "UPDATE Showing
            SET
                movieID = :movieID,
                venueID = :venueID,
                startDateTime = :startDateTime,
                basePrice = :basePrice,
                isPremiere = :isPremiere,
                status = :status
            WHERE showingID = :showingID
            AND NOT EXISTS (
                SELECT 1
                FROM Booking b
                WHERE b.showingID = :bookingShowingID
            )"
        );

        $statement->execute([
            ...$data,
            "showingID" => $showingID,
            "bookingShowingID" => $showingID
        ]);

        if ($this->hasBookings($showingID)) {
            throw new \DomainException(
                "This showing has bookings and cannot be edited."
            );
        }
    }

    public function delete(int $showingID): bool
    {
        $statement = $this->connection->prepare(
            "DELETE FROM Showing
            WHERE showingID = :showingID
            AND NOT EXISTS (
                SELECT 1
                FROM Booking b
                WHERE b.showingID = :bookingShowingID
            )"
        );

        $statement->execute([
            "showingID" => $showingID,
            "bookingShowingID" => $showingID
        ]);

        return $statement->rowCount() > 0;
    }

    public function hasBookings(int $showingID): bool
    {
        $statement = $this->connection->prepare(
            "SELECT 1
            FROM Booking
            WHERE showingID = :showingID
            LIMIT 1"
        );

        $statement->execute([
            "showingID" => $showingID
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function hasScheduleConflict(
        int $venueID,
        int $movieID,
        string $startDateTime,
        ?int $excludeID = null
    ): bool {
        $statement = $this->connection->prepare(
            "SELECT 1
            FROM Showing s
            JOIN Movie existingMovie
                ON s.movieID = existingMovie.movieID
            JOIN Movie newMovie
                ON newMovie.movieID = :newMovieID
            WHERE s.venueID = :venueID
            AND s.status = 'scheduled'
            AND s.showingID <> COALESCE(:excludeID, 0)
            AND s.startDateTime < DATE_ADD(
                :newStartForEnd,
                INTERVAL newMovie.durationMinutes MINUTE
            )
            AND DATE_ADD(
                s.startDateTime,
                INTERVAL existingMovie.durationMinutes MINUTE
            ) > :newStartForStart
            LIMIT 1"
        );

        $statement->execute([
            "newMovieID" => $movieID,
            "venueID" => $venueID,
            "excludeID" => $excludeID,
            "newStartForEnd" => $startDateTime,
            "newStartForStart" => $startDateTime
        ]);

        return $statement->fetchColumn() !== false;
    }
}
