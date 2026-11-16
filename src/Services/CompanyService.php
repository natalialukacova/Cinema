<?php

namespace App\Services;

use App\Interfaces\CompanyRepositoryInterface;

class CompanyService
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function getCompany(): ?array
    {
        return $this->companyRepository->findFirst();
    }
}
