<?php

namespace App\Repositories;

use App\Interfaces\OpeningHourRepositoryInterface;
use App\Models\OpeningHour;
use PDO;
use Throwable;

class OpeningHourRepository implements OpeningHourRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    // Get all opening hours for a specific company
    public function findByCompanyID(int $companyID): array
    {
        $statement = $this->connection->prepare(
            "SELECT
                openingHourID,
                companyID,
                dayOfWeek,
                opensAt,
                closesAt,
                isClosed
             FROM OpeningHour
             WHERE companyID = :companyID
             ORDER BY dayOfWeek"
        );

        $statement->execute([
            "companyID" => $companyID
        ]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $row): OpeningHour => $this->mapRowToOpeningHour($row),
            $rows
        );
    }

    // Insert or update opening hours for the entire week
    public function saveWeek(int $companyID, array $days): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO OpeningHour (
                companyID, dayOfWeek,
                opensAt, closesAt, isClosed
            ) VALUES (
                :companyID, :dayOfWeek,
                :opensAt, :closesAt, :isClosed
            )
            -- Update existing opening hours instead of creating a duplicate record
            ON DUPLICATE KEY UPDATE
                opensAt = VALUES(opensAt),
                closesAt = VALUES(closesAt),
                isClosed = VALUES(isClosed)"
        );

        // Save the entire week in one transaction
        $this->connection->beginTransaction();

        try {
            foreach ($days as $day) {
                $statement->execute([
                    "companyID" => $companyID,
                    "dayOfWeek" => $day["dayOfWeek"],
                    "opensAt" => $day["opensAt"],
                    "closesAt" => $day["closesAt"],
                    "isClosed" => $day["isClosed"]
                ]);
            }

            $this->connection->commit();

        } catch (Throwable $error) {
            // Undo all changes if an error occurs
            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            throw $error;
        }
    }

    // Convert database row into an OpeningHour object
    private function mapRowToOpeningHour(array $row): OpeningHour
    {
        return new OpeningHour(
            (int) $row["openingHourID"],
            (int) $row["companyID"],
            (int) $row["dayOfWeek"],
            $row["opensAt"],
            $row["closesAt"],
            (bool) $row["isClosed"]
        );
    }
}
