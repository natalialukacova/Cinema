<?php

namespace App\Interfaces;

use App\Models\UserAccount;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?UserAccount;

    public function findByID(int $userID): ?UserAccount;

    public function create(UserAccount $user): bool;
}