<?php

namespace App\Interfaces;

use App\Models\Booking;

interface BookingRepositoryInterface
{
    public function findShowing(int $showingID): ?array;

    public function create(
        int $userID,
        int $showingID,
        int $ticketCount
    ): int;

    public function findForUser(
        int $bookingID,
        int $userID
    ): ?Booking;

    public function findByUserID(int $userID): array;
}
