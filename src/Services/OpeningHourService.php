<?php

namespace App\Services;

use App\Interfaces\OpeningHourRepositoryInterface;
use App\Interfaces\CompanyRepositoryInterface;
use DomainException;
use InvalidArgumentException;

class OpeningHourService
{
    public function __construct(
        private OpeningHourRepositoryInterface $openingHourRepository,
        private CompanyRepositoryInterface $companyRepository
    ) {
    }

    public function getHours(): array
    {
        $company = $this->companyRepository->findFirst();

        if ($company === null) {
            return [];
        }

        return $this->openingHourRepository->findByCompanyID(
            $company->getCompanyID()
        );
    }

    public function updateHours(array $days): void
    {
        $company = $this->companyRepository->findFirst();

        if ($company === null) {
            throw new DomainException("Company not found.");
        }

        // Require data for exactly seven days
        if (count($days) !== 7) {
            throw new InvalidArgumentException(
                "Opening hours must include all seven days."
            );
        }

        $validated = [];

        for ($day = 1; $day <= 7; $day++) {
            if (
                !isset($days[$day]) ||
                !is_array($days[$day])
            ) {
                throw new InvalidArgumentException(
                    "Invalid opening hours data."
                );
            }

            $data = $days[$day];
            $closedValue = $data["isClosed"] ?? "0";

            if (
                !is_string($closedValue) ||
                !in_array($closedValue, ["0", "1"], true)
            ) {
                throw new InvalidArgumentException(
                    "Invalid closed status."
                );
            }

            $isClosed = $closedValue === "1";
            $opensAt = null;
            $closesAt = null;

            if (!$isClosed) {
                $opensAt = $data["opensAt"] ?? null;
                $closesAt = $data["closesAt"] ?? null;

                // Check the HH:MM time format
                $timePattern = "/^([01]\d|2[0-3]):[0-5]\d$/";

                if (
                    !is_string($opensAt) ||
                    !is_string($closesAt) ||
                    !preg_match($timePattern, $opensAt) ||
                    !preg_match($timePattern, $closesAt)
                ) {
                    throw new InvalidArgumentException(
                        "Enter valid opening and closing times."
                    );
                }

                // Closing time must be later than opening time
                if ($closesAt <= $opensAt) {
                    throw new InvalidArgumentException(
                        "Closing time must be later than opening time."
                    );
                }

                $opensAt .= ":00";
                $closesAt .= ":00";
            }

            $validated[] = [
                "dayOfWeek" => $day,
                "opensAt" => $opensAt,
                "closesAt" => $closesAt,
                "isClosed" => (int) $isClosed
            ];
        }

        // Save only after all seven days pass validation
        $this->openingHourRepository->saveWeek(
            $company->getCompanyID(),
            $validated
        );
    }
}
