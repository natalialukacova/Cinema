<?php

namespace App\Interfaces;

interface CompanyRepositoryInterface
{
    public function findFirst(): ?array;
}
