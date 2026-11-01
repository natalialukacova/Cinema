<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\BookingService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class BookingController
{
    public function __construct(
        private BookingService $bookingService
    ) {
    }

    private function basePath(): string
    {
        return rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );
    }

    private function userID(): ?int
    {
        $id = filter_var(
            $_SESSION["user_id"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        return $id === false ? null : (int) $id;
    }

    private function requireLogin(): int
    {
        $userID = $this->userID();

        if ($userID === null) {
            header(
                "Location: " . $this->basePath() . "/login",
                true,
                302
            );
            exit;
        }

        return $userID;
    }

    public function show(?string $error = null): void
    {
        $this->requireLogin();

        $showingID = filter_var(
            $_GET["showingID"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($showingID === false) {
            http_response_code(404);
            echo "Showing not found.";
            return;
        }

        $showing = $this->bookingService->getShowing(
            (int) $showingID
        );

        if ($showing === null) {
            http_response_code(404);
            echo "Showing not found.";
            return;
        }

        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        View::render(
            "pages/website/booking/booking",
            [
                "title" => "Book Tickets",
                "showing" => $showing,
                "csrfToken" => $_SESSION["csrf_token"],
                "error" => $error
            ]
        );
    }

    public function create(): void
    {
        $userID = $this->requireLogin();

        $token = $_POST["csrf_token"] ?? null;

        if (
            !is_string($token) ||
            !isset($_SESSION["csrf_token"]) ||
            !hash_equals($_SESSION["csrf_token"], $token)
        ) {
            http_response_code(403);
            $this->show("Invalid or expired form.");
            return;
        }

        $showingID = filter_var(
            $_POST["showingID"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        $ticketCount = filter_var(
            $_POST["ticketCount"] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($showingID === false || $ticketCount === false) {
            http_response_code(400);
            $this->show("Invalid booking data.");
            return;
        }

        try {
            $bookingID = $this->bookingService->book(
                $userID,
                (int) $showingID,
                (int) $ticketCount
            );

            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

            header(
                "Location: " . $this->basePath()
                . "/booking-confirmation?id=" . $bookingID,
                true,
                303
            );
            exit;

        } catch (DomainException | InvalidArgumentException $error) {
            $this->show($error->getMessage());

        } catch (Throwable $error) {
            error_log("Booking error: " . $error->getMessage());
            $this->show(
                "Booking failed. Please try again."
            );
        }
    }

    public function confirmation(): void
    {
        $userID = $this->requireLogin();

        $bookingID = filter_var(
            $_GET["id"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($bookingID === false) {
            http_response_code(404);
            echo "Booking not found.";
            return;
        }

        $booking = $this->bookingService->getBooking(
            (int) $bookingID,
            $userID
        );

        if ($booking === null) {
            http_response_code(404);
            echo "Booking not found.";
            return;
        }

        View::render(
            "pages/website/booking/confirmation",
            [
                "title" => "Booking Confirmation",
                "booking" => $booking
            ]
        );
    }
}
