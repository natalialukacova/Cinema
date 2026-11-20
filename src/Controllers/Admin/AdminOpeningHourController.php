<?php

namespace App\Controllers\Admin;

use App\Core\AdminGuard;
use App\Core\View;
use App\Services\OpeningHourService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class AdminOpeningHourController
{
    public function __construct(
        private OpeningHourService $openingHourService,
        private AdminGuard $adminGuard
    ) {
    }

    private function basePath(): string
    {
        return rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );
    }

    // Generate or return the current CSRF token
    private function csrfToken(): string
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
    }

    // Check the submitted CSRF token
    private function checkCsrf(): void
    {
        $token = $_POST["csrf_token"] ?? null;

        if (
            !is_string($token) ||
            !hash_equals($this->csrfToken(), $token)
        ) {
            http_response_code(403);
            echo "403 - Invalid CSRF token.";
            exit;
        }
    }

    // Display the opening hours form
    private function form(
        ?string $error = null,
        ?string $message = null
    ): void {
        View::render(
            "pages/admin/opening-hours/opening-hours-form",
            [
                "title" => "Manage Opening Hours",
                "hours" => $this->openingHourService->getHours(),
                "error" => $error,
                "message" => $message,
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    // Load existing opening hours
    public function edit(): void
    {
        $this->adminGuard->requireAdmin();

        try {
            $message = $_SESSION["opening_hours_message"] ?? null;
            unset($_SESSION["opening_hours_message"]);

            $this->form(null, $message);

        } catch (DomainException $error) {
            http_response_code(404);
            echo "Company not found.";
        }
    }

    // Validate and save the weekly opening hours
    public function update(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        try {
            $days = $_POST["days"] ?? null;

            if (!is_array($days)) {
                throw new InvalidArgumentException(
                    "Invalid opening hours data."
                );
            }

            $this->openingHourService->updateHours($days);

            $_SESSION["opening_hours_message"] =
                "Opening hours updated successfully.";

            header(
                "Location: " . $this->basePath()
                . "/admin/opening-hours",
                true,
                303
            );
            exit;

        } catch (InvalidArgumentException $error) {
            $this->form($error->getMessage());

        } catch (DomainException $error) {
            http_response_code(404);
            echo "Company not found.";

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "Could not update opening hours."
            );
        }
    }
}
