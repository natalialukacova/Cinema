<?php

namespace App\Models;

class Showing
{
    public function __construct(
        private ?int $showingID,
        private int $movieID,
        private int $venueID,
        private string $startDateTime,
        private string $basePrice,
        private bool $isPremiere,
        private string $status,
        private ?string $movieTitle = null,
        private ?string $venueName = null
    ) {
    }

    public function getShowingID(): ?int
    {
        return $this->showingID;
    }

    public function getMovieID(): int
    {
        return $this->movieID;
    }

    public function getVenueID(): int
    {
        return $this->venueID;
    }

    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    public function getBasePrice(): string
    {
        return $this->basePrice;
    }

    public function getIsPremiere(): bool
    {
        return $this->isPremiere;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMovieTitle(): ?string
    {
        return $this->movieTitle;
    }

    public function getVenueName(): ?string
    {
        return $this->venueName;
    }
}
