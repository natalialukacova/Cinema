<?php

namespace App\Interfaces;

interface ShowingRepositoryInterface
{
    public function findUpcomingByMovieID(int $movieID): array;

    public function findUpcomingAll(): array;
}
