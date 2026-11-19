<?php

namespace App\Interfaces;

use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function findFirst(): ?Company;

    public function update(int $companyID, array $data): void;
}
