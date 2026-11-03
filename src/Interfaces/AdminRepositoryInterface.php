<?php

namespace App\Interfaces;

interface AdminRepositoryInterface
{
    public function isAdmin(int $userID): bool;

    public function getBookingStats(): array;

    public function findAllBookings(): array;
}
