<?php

namespace App\Services;

use App\Interfaces\AdminRepositoryInterface;

class AdminService
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function isAdmin(int $userID): bool
    {
        return $this->adminRepository->isAdmin($userID);
    }

    public function getBookingStats(): array
    {
        return $this->adminRepository->getBookingStats();
    }

    public function getAllBookings(): array
    {
        return $this->adminRepository->findAllBookings();
    }
}
