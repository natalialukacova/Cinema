<?php

namespace App\Interfaces;

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
    ): ?array;

    public function findByUserID(int $userID): array;
}
