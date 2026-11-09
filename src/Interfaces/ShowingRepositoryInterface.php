<?php

namespace App\Interfaces;

interface ShowingRepositoryInterface
{
    public function findUpcomingByMovieID(int $movieID): array;

    public function findUpcomingAll(): array;

    public function findAllForAdmin(): array;

    public function findByID(int $showingID): ?array;

    public function create(array $data): int;

    public function update(int $showingID, array $data): void;

    public function delete(int $showingID): bool;

    public function hasBookings(int $showingID): bool;

    public function hasScheduleConflict(
        int $venueID,
        int $movieID,
        string $startDateTime,
        ?int $excludeID = null
    ): bool;
}
