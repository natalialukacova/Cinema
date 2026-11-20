<?php

namespace App\Models;

class OpeningHour
{
    public function __construct(
        private ?int $openingHourID,
        private int $companyID,
        private int $dayOfWeek,
        private ?string $opensAt,
        private ?string $closesAt,
        private bool $isClosed
    ) {
    }

    public function getOpeningHourID(): ?int
    {
        return $this->openingHourID;
    }

    public function getCompanyID(): int
    {
        return $this->companyID;
    }

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function getOpensAt(): ?string
    {
        return $this->opensAt;
    }

    public function getClosesAt(): ?string
    {
        return $this->closesAt;
    }

    public function getIsClosed(): bool
    {
        return $this->isClosed;
    }
}
