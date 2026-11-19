<?php

namespace App\Models;

class Booking
{
    public function __construct(
        private int $bookingID,
        private int $userID,
        private int $showingID,
        private string $bookedAt,
        private string $status,
        private string $totalPrice,
        private string $startDateTime,
        private string $movieTitle,
        private string $venueName,
        private int $ticketCount,
        private array $seats = []
    ) {
    }

    public function getBookingID(): int
    {
        return $this->bookingID;
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function getShowingID(): int
    {
        return $this->showingID;
    }

    public function getBookedAt(): string
    {
        return $this->bookedAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotalPrice(): string
    {
        return $this->totalPrice;
    }

    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    public function getMovieTitle(): string
    {
        return $this->movieTitle;
    }

    public function getVenueName(): string
    {
        return $this->venueName;
    }

    public function getTicketCount(): int
    {
        return $this->ticketCount;
    }

    public function getSeats(): array
    {
        return $this->seats;
    }
}
