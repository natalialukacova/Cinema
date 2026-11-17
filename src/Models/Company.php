<?php

namespace App\Models;

class Company
{
    public function __construct(
        private int $companyID,
        private string $name,
        private ?string $description,
        private string $email,
        private ?string $phone,
        private ?string $address
    ) {
    }

    public function getCompanyID(): int
    {
        return $this->companyID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
}
