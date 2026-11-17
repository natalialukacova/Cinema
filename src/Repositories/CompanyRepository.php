<?php

namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use PDO;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function findFirst(): ?Company
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

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Return Company object or null if no company exists
        return $this->mapRowToCompany($row);
    }

    private function mapRowToCompany(array $row): Company
    {
        // Convert database row into a Company object
        return new Company(
            (int) $row["companyID"],
            $row["name"],
            $row["description"],
            $row["email"],
            $row["phone"],
            $row["address"]
        );
    }
}
