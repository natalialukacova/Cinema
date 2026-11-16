<?php

namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use PDO;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findFirst(): ?array
    {
        // Get the first company from the database
        // Use query() because the SQL has no user input
        $statement = $this->connection->query(
            "SELECT
                companyID,
                name,
                description,
                email,
                phone,
                address
             FROM Company
             ORDER BY companyID
             LIMIT 1"
        );

        // Return company data or null if no company exists
        $company = $statement->fetch(PDO::FETCH_ASSOC);

        return $company ?: null;
    }
}
