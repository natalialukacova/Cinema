<?php

namespace App\Interfaces;

interface VenueRepositoryInterface
{
    public function findAllActive(): array;
}
