<?php

namespace App\Models;

class Venue
{
    public function __construct(
        private int $venueID,
        private int $companyID,
        private string $name,
        private ?string $description,
        private bool $isActive,
        private int $seatCount
    ) {
    }

    public function getVenueID(): int
    {
        return $this->venueID;
    }

    public function getCompanyID(): int
    {
        return $this->companyID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getSeatCount(): int
    {
        return $this->seatCount;
    }
}
