<?php

namespace App\Interfaces;

use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function findFirst(): ?Company;
}
