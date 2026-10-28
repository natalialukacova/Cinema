<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\UserAccount;
use InvalidArgumentException;
use RuntimeException;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }
    
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): bool {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $email = trim($email);

        // Validate input
        if ($firstName === "" || $lastName === "") {
            throw new InvalidArgumentException(
                "First name and last name are required."
            );
        }

        if (strlen($firstName) > 100 || strlen($lastName) > 100) {
            throw new InvalidArgumentException(
                "Name is too long."
            );
        }

        if (
            strlen($email) > 190 ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            throw new InvalidArgumentException(
                "Invalid email address."
            );
        }

        if (strlen($password) < 6) {
            throw new InvalidArgumentException(
                "Password must contain at least 6 characters."
            );
        }

        // Check if email already exists
        if ($this->userRepository->findByEmail($email) !== null) {
            throw new RuntimeException(
                "Email is already registered."
            );
        }

        // Hash password
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Create user object
        $user = new UserAccount(
            null,
            $firstName,
            $lastName,
            $email,
            $passwordHash
        );

        // Save user
        return $this->userRepository->create($user);
    }
}
