<?php

namespace App\Services;

use App\Interfaces\ShowingRepositoryInterface;
use App\Interfaces\MovieRepositoryInterface;
use App\Interfaces\VenueRepositoryInterface;
use DateTimeImmutable;
use DomainException;
use InvalidArgumentException;

class AdminShowingService
{
    public function __construct(
        private ShowingRepositoryInterface $showingRepository,
        private MovieRepositoryInterface $movieRepository,
        private VenueRepositoryInterface $venueRepository
    ) {
    }

    public function getAllShowings(): array
    {
        return $this->showingRepository->findAllForAdmin();
    }

    public function getShowing(int $showingID): ?array
    {
        return $this->showingRepository->findByID($showingID);
    }

    public function getMovies(): array
    {
        return $this->movieRepository->findAll();
    }

    public function getVenues(): array
    {
        return $this->venueRepository->findAllActive();
    }

    public function createShowing(array $data): int
    {
        $validated = $this->validateShowing($data);

        $this->checkScheduleConflict($validated);

        return $this->showingRepository->create($validated);
    }

    public function updateShowing(
        int $showingID,
        array $data
    ): void {
        if ($this->getShowing($showingID) === null) {
            throw new DomainException("Showing not found.");
        }

        if ($this->showingRepository->hasBookings($showingID)) {
            throw new DomainException(
                "This showing already has bookings."
            );
        }

        $validated = $this->validateShowing($data);

        $this->checkScheduleConflict(
            $validated,
            $showingID
        );

        $this->showingRepository->update(
            $showingID,
            $validated
        );
    }

    public function deleteShowing(int $showingID): bool
    {
        if ($this->showingRepository->hasBookings($showingID)) {
            throw new DomainException(
                "Showing cannot be deleted because it has bookings."
            );
        }

        return $this->showingRepository->delete($showingID);
    }

    private function checkScheduleConflict(
        array $data,
        ?int $excludeID = null
    ): void {
        if ($data["status"] !== "scheduled") {
            return;
        }

        if ($this->showingRepository->hasScheduleConflict(
            $data["venueID"],
            $data["movieID"],
            $data["startDateTime"],
            $excludeID
        )) {
            throw new DomainException(
                "The venue already has a showing at this time."
            );
        }
    }

    private function validateShowing(array $data): array
    {
        foreach ([
            "movieID",
            "venueID",
            "startDateTime",
            "basePrice",
            "isPremiere",
            "status"
        ] as $field) {
            if (
                !isset($data[$field]) ||
                !is_string($data[$field])
            ) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }
        }

        $movieID = filter_var(
            $data["movieID"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        $venueID = filter_var(
            $data["venueID"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!is_int($movieID) || !is_int($venueID)) {
            throw new InvalidArgumentException(
                "Select a valid movie and venue."
            );
        }

        if ($this->movieRepository->findByID($movieID) === null) {
            throw new InvalidArgumentException(
                "Selected movie does not exist."
            );
        }

        $venueFound = false;

        foreach ($this->venueRepository->findAllActive() as $venue) {
            if ((int) $venue["venueID"] === $venueID) {
                $venueFound = true;
                break;
            }
        }

        if (!$venueFound) {
            throw new InvalidArgumentException(
                "Selected venue is not active."
            );
        }

        $date = DateTimeImmutable::createFromFormat(
            "!Y-m-d\TH:i",
            $data["startDateTime"]
        );

        if (
            $date === false ||
            $date->format("Y-m-d\TH:i") !== $data["startDateTime"]
        ) {
            throw new InvalidArgumentException(
                "Enter a valid date and time."
            );
        }

        $status = $data["status"];

        if (!in_array(
            $status,
            ["scheduled", "cancelled", "completed"],
            true
        )) {
            throw new InvalidArgumentException(
                "Invalid showing status."
            );
        }

        if (
            $status === "scheduled" &&
            $date <= new DateTimeImmutable()
        ) {
            throw new InvalidArgumentException(
                "Scheduled showings must be in the future."
            );
        }

        if (!preg_match(
            "/^\d{1,6}(\.\d{1,2})?$/",
            $data["basePrice"]
        )) {
            throw new InvalidArgumentException(
                "Enter a valid ticket price."
            );
        }

        $price = (float) $data["basePrice"];

        if ($data["isPremiere"] !== "0" &&
            $data["isPremiere"] !== "1") {
            throw new InvalidArgumentException(
                "Invalid premiere value."
            );
        }

        return [
            "movieID" => $movieID,
            "venueID" => $venueID,
            "startDateTime" => $date->format("Y-m-d H:i:s"),
            "basePrice" => number_format($price, 2, ".", ""),
            "isPremiere" => (int) $data["isPremiere"],
            "status" => $status
        ];
    }
}
