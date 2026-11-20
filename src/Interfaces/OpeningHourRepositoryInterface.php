<?php

namespace App\Interfaces;

interface OpeningHourRepositoryInterface
{
    public function findByCompanyID(int $companyID): array;

    public function saveWeek(int $companyID, array $days): void;
}
