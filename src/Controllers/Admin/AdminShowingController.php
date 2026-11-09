<?php

namespace App\Controllers\Admin;

use App\Core\AdminGuard;
use App\Core\View;
use App\Services\AdminShowingService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class AdminShowingController
{
    public function __construct(
        private AdminShowingService $showingService,
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

    private function csrfToken(): string
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
    }

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

    private function getID(): ?int
    {
        $id = filter_input(
            INPUT_GET,
            "id",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        return is_int($id) ? $id : null;
    }

    private function postValues(): array
    {
        $fields = [
            "movieID",
            "venueID",
            "startDateTime",
            "basePrice",
            "status"
        ];

        $values = [];

        foreach ($fields as $field) {
            $value = $_POST[$field] ?? "";

            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }

            $values[$field] = $value;
        }

        $premiere = $_POST["isPremiere"] ?? "0";

        if (!is_string($premiere)) {
            throw new InvalidArgumentException(
                "Invalid form data."
            );
        }

        $values["isPremiere"] = $premiere;

        return $values;
    }

    private function form(
        string $mode,
        array $values = [],
        ?int $showingID = null,
        ?string $error = null
    ): void {
        View::render(
            "pages/admin/showings/showing-form",
            [
                "title" => $mode === "edit"
                    ? "Edit Showing"
                    : "Add Showing",
                "mode" => $mode,
                "values" => $values,
                "showingID" => $showingID,
                "error" => $error,
                "movies" => $this->showingService->getMovies(),
                "venues" => $this->showingService->getVenues(),
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    private function redirectToList(string $message): void
    {
        $_SESSION["admin_showing_message"] = $message;

        header(
            "Location: " . $this->basePath()
            . "/admin/showings",
            true,
            303
        );
        exit;
    }

    public function index(): void
    {
        $this->adminGuard->requireAdmin();

        $showings = $this->showingService->getAllShowings();

        $message = $_SESSION["admin_showing_message"] ?? null;
        unset($_SESSION["admin_showing_message"]);

        View::render(
            "pages/admin/showings/showing-list",
            [
                "title" => "Manage Showings",
                "showings" => $showings,
                "message" => $message,
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    public function create(): void
    {
        $this->adminGuard->requireAdmin();
        $this->form("create");
    }

    public function store(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $values = [];

        try {
            $values = $this->postValues();

            $this->showingService->createShowing($values);

            $this->redirectToList(
                "Showing created successfully."
            );

        } catch (InvalidArgumentException | DomainException $error) {
            $this->form(
                "create",
                $values,
                null,
                $error->getMessage()
            );

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "create",
                $values,
                null,
                "Could not create the showing."
            );
        }
    }

    public function edit(): void
    {
        $this->adminGuard->requireAdmin();

        $showingID = $this->getID();

        $showing = $showingID !== null
            ? $this->showingService->getShowing($showingID)
            : null;

        if ($showing === null) {
            http_response_code(404);
            echo "Showing not found.";
            return;
        }

        $this->form(
            "edit",
            $showing,
            $showingID
        );
    }

    public function update(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $showingID = $this->getID();

        if ($showingID === null ||
            $this->showingService->getShowing($showingID) === null) {
            http_response_code(404);
            echo "Showing not found.";
            return;
        }

        $values = [];

        try {
            $values = $this->postValues();

            $this->showingService->updateShowing(
                $showingID,
                $values
            );

            $this->redirectToList(
                "Showing updated successfully."
            );

        } catch (InvalidArgumentException | DomainException $error) {
            $this->form(
                "edit",
                $values,
                $showingID,
                $error->getMessage()
            );

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "edit",
                $values,
                $showingID,
                "Could not update the showing."
            );
        }
    }

    public function delete(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $showingID = filter_input(
            INPUT_POST,
            "showingID",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!is_int($showingID)) {
            http_response_code(400);
            echo "Invalid showing ID.";
            return;
        }

        try {
            $deleted = $this->showingService
                ->deleteShowing($showingID);

            $this->redirectToList(
                $deleted
                    ? "Showing deleted successfully."
                    : "Showing not found."
            );

        } catch (DomainException $error) {
            $this->redirectToList($error->getMessage());

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->redirectToList(
                "Could not delete the showing."
            );
        }
    }
}
