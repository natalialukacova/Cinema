<?php

namespace App\Repositories;

use DomainException;
use PDO;
use Throwable;

class BookingRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findShowing(int $showingID): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT
                s.showingID,
                s.venueID,
                s.startDateTime,
                s.basePrice,
                m.title AS movieTitle,
                v.name AS venueName
             FROM Showing s
             JOIN Movie m ON s.movieID = m.movieID
             JOIN Venue v ON s.venueID = v.venueID
             WHERE s.showingID = :showingID
               AND s.status = 'scheduled'
               AND s.startDateTime > NOW()
               AND v.isActive = 1"
        );

        $statement->execute(["showingID" => $showingID]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function create(
        int $userID,
        int $showingID,
        int $ticketCount
    ): int {
        $this->connection->beginTransaction();

        try {
            // Lock the showing during reservation
            $statement = $this->connection->prepare(
                "SELECT s.venueID, s.basePrice
                 FROM Showing s
                 JOIN Venue v ON s.venueID = v.venueID
                 WHERE s.showingID = :showingID
                   AND s.status = 'scheduled'
                   AND s.startDateTime > NOW()
                   AND v.isActive = 1
                 FOR UPDATE"
            );

            $statement->execute(["showingID" => $showingID]);
            $showing = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();

            if (!$showing) {
                throw new DomainException(
                    "Showing is not available."
                );
            }

            // Find all seats in the venue
            $statement = $this->connection->prepare(
                "SELECT seatID
                 FROM Seat
                 WHERE venueID = :venueID
                 ORDER BY rowLabel, seatNumber"
            );

            $statement->execute([
                "venueID" => $showing["venueID"]
            ]);

            $allSeats = $statement->fetchAll(PDO::FETCH_COLUMN);

            // Find seats already booked for this showing
            $statement = $this->connection->prepare(
                "SELECT bs.seatID
                 FROM BookingSeat bs
                 JOIN Booking b ON bs.bookingID = b.bookingID
                 WHERE b.showingID = :showingID
                   AND b.status <> 'cancelled'
                 FOR UPDATE"
            );

            $statement->execute(["showingID" => $showingID]);

            $occupied = array_fill_keys(
                $statement->fetchAll(PDO::FETCH_COLUMN),
                true
            );

            $available = [];

            foreach ($allSeats as $seatID) {
                if (!isset($occupied[$seatID])) {
                    $available[] = (int) $seatID;
                }
            }

            if (count($available) < $ticketCount) {
                throw new DomainException(
                    "Not enough seats available."
                );
            }

            $selectedSeats = array_slice(
                $available,
                0,
                $ticketCount
            );

            $totalPrice = number_format(
                (float) $showing["basePrice"] * $ticketCount,
                2,
                ".",
                ""
            );

            // Create confirmed booking
            $statement = $this->connection->prepare(
                "INSERT INTO Booking
                    (userID, showingID, status, totalPrice)
                 VALUES
                    (:userID, :showingID, 'confirmed', :totalPrice)"
            );

            $statement->execute([
                "userID" => $userID,
                "showingID" => $showingID,
                "totalPrice" => $totalPrice
            ]);

            $bookingID = (int) $this->connection->lastInsertId();

            // Assign selected seats
            $statement = $this->connection->prepare(
                "INSERT INTO BookingSeat (bookingID, seatID)
                 VALUES (:bookingID, :seatID)"
            );

            foreach ($selectedSeats as $seatID) {
                $statement->execute([
                    "bookingID" => $bookingID,
                    "seatID" => $seatID
                ]);
            }

            $this->connection->commit();

            return $bookingID;

        } catch (Throwable $error) {
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $error;
        }
    }

    public function findForUser(
        int $bookingID,
        int $userID
    ): ?array {
        $statement = $this->connection->prepare(
            "SELECT
                b.bookingID,
                b.totalPrice,
                b.status,
                s.startDateTime,
                m.title AS movieTitle,
                v.name AS venueName
             FROM Booking b
             JOIN Showing s ON b.showingID = s.showingID
             JOIN Movie m ON s.movieID = m.movieID
             JOIN Venue v ON s.venueID = v.venueID
             WHERE b.bookingID = :bookingID
               AND b.userID = :userID"
        );

        $statement->execute([
            "bookingID" => $bookingID,
            "userID" => $userID
        ]);

        $booking = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            return null;
        }

        $statement = $this->connection->prepare(
            "SELECT s.rowLabel, s.seatNumber
             FROM BookingSeat bs
             JOIN Seat s ON bs.seatID = s.seatID
             WHERE bs.bookingID = :bookingID
             ORDER BY s.rowLabel, s.seatNumber"
        );

        $statement->execute(["bookingID" => $bookingID]);

        $booking["seats"] = $statement->fetchAll(
            PDO::FETCH_ASSOC
        );

        return $booking;
    }
}
