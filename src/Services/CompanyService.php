<?php

namespace App\Services;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;

class CompanyService
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function getCompany(): ?Company
    {
        return $this->companyRepository->findFirst();
    }
}
