<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use InvalidArgumentException;

class BookingService
{
    public function __construct(
        private BookingRepository $bookingRepository
    ) {
    }

    public function getShowing(int $showingID): ?array
    {
        return $this->bookingRepository->findShowing($showingID);
    }

    public function book(
        int $userID,
        int $showingID,
        int $ticketCount
    ): int {
        if ($ticketCount < 1 || $ticketCount > 5) {
            throw new InvalidArgumentException(
                "You can book between 1 and 5 tickets."
            );
        }

        return $this->bookingRepository->create(
            $userID,
            $showingID,
            $ticketCount
        );
    }

    public function getBooking(
        int $bookingID,
        int $userID
    ): ?array {
        return $this->bookingRepository->findForUser(
            $bookingID,
            $userID
        );
    }
}
