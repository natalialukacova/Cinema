<?php

namespace App\Models;

class UserAccount
{
    public function __construct(
        private ?int $userID,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $passwordHash,
        private int $userRank = 1
    ) {
    }

    public function getUserID(): ?int
    {
        return $this->userID;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getUserRank(): int
    {
        return $this->userRank;
    }
}