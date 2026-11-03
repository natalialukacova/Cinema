<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use PDO;

class AdminRepository implements AdminRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function isAdmin(int $userID): bool
    {
        $statement = $this->pdo->prepare(
            "SELECT 1
             FROM UserAccount
             WHERE userID = :userID
               AND userRank = 2
             LIMIT 1"
        );

        $statement->execute([
            "userID" => $userID
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function getBookingStats(): array
    {
        $statement = $this->pdo->query(
            "SELECT
                COUNT(*) AS totalBookings,
                COALESCE(SUM(
                    CASE WHEN status = 'confirmed'
                    THEN 1 ELSE 0 END
                ), 0) AS confirmedBookings,
                COALESCE(SUM(
                    CASE WHEN status = 'pending'
                    THEN 1 ELSE 0 END
                ), 0) AS pendingBookings,
                COALESCE(SUM(
                    CASE WHEN status = 'cancelled'
                    THEN 1 ELSE 0 END
                ), 0) AS cancelledBookings
             FROM Booking"
        );

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function findAllBookings(): array
    {
        $statement = $this->pdo->query(
            "SELECT
                b.bookingID,
                b.bookedAt,
                b.status,
                b.totalPrice,

                u.firstName,
                u.lastName,
                u.email,

                m.title AS movieTitle,
                s.startDateTime,
                v.name AS venueName,

                (
                    SELECT COUNT(*)
                    FROM BookingSeat bs
                    WHERE bs.bookingID = b.bookingID
                ) AS ticketCount

             FROM Booking b
             JOIN UserAccount u
                ON b.userID = u.userID
             JOIN Showing s
                ON b.showingID = s.showingID
             JOIN Movie m
                ON s.movieID = m.movieID
             JOIN Venue v
                ON s.venueID = v.venueID

             ORDER BY b.bookedAt DESC, b.bookingID DESC"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
