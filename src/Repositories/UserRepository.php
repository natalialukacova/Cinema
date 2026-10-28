<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\UserAccount;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function findByEmail(string $email): ?UserAccount
    {
        $statement = $this->connection->prepare(
            "SELECT *
            FROM UserAccount
            WHERE email = :email"
        );

        $statement->execute([
            "email" => $email
        ]);

        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return new UserAccount(
            (int) $row["userID"],
            $row["firstName"],
            $row["lastName"],
            $row["email"],
            $row["passwordHash"],
            (int) $row["userRank"]
        );
    }
    
    public function create(UserAccount $user): bool
    {
        $statement = $this->connection->prepare(
            "INSERT INTO UserAccount
                (firstName, lastName, email, passwordHash)
            VALUES
                (:firstName, :lastName, :email, :passwordHash)"
        );

        return $statement->execute([
            "firstName" => $user->getFirstName(),
            "lastName" => $user->getLastName(),
            "email" => $user->getEmail(),
            "passwordHash" => $user->getPasswordHash()
        ]);
    }

}
