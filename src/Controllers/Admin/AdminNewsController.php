<?php

namespace App\Controllers\Admin;

use App\Core\AdminGuard;
use App\Core\View;
use App\Services\NewsService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class AdminNewsController
{
    public function __construct(
        private NewsService $newsService,
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
        $values = [];

        foreach (["title", "content", "imageURL"] as $field) {
            $value = $_POST[$field] ?? "";

            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }

            $values[$field] = $value;
        }

        $status = $_POST["isPublished"] ?? "0";

        if (!is_string($status)) {
            throw new InvalidArgumentException(
                "Invalid publication status."
            );
        }

        $values["isPublished"] = $status;

        return $values;
    }

    private function form(
        string $mode,
        array $values = [],
        ?int $newsID = null,
        ?string $error = null
    ): void {
        View::render(
            "pages/admin/news/news-form",
            [
                "title" => $mode === "edit"
                    ? "Edit News"
                    : "Add News",
                "mode" => $mode,
                "values" => $values,
                "newsID" => $newsID,
                "error" => $error,
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    private function redirectToList(string $message): void
    {
        $_SESSION["admin_news_message"] = $message;

        header(
            "Location: " . $this->basePath() . "/admin/news",
            true,
            303
        );
        exit;
    }

    public function index(): void
    {
        $this->adminGuard->requireAdmin();

        $news = $this->newsService->getAllNews();

        $message = $_SESSION["admin_news_message"] ?? null;
        unset($_SESSION["admin_news_message"]);

        View::render(
            "pages/admin/news/news-list",
            [
                "title" => "Manage News",
                "news" => $news,
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
            $this->newsService->createNews($values);

            $this->redirectToList(
                "News article created successfully."
            );

        } catch (InvalidArgumentException $error) {
            $this->form(
                "create", $values, null, $error->getMessage()
            );

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "create", $values, null,
                "Could not create the news article."
            );
        }
    }

    public function edit(): void
    {
        $this->adminGuard->requireAdmin();

        $newsID = $this->getID();

        $news = $newsID !== null
            ? $this->newsService->getNewsByID($newsID)
            : null;

        if ($news === null) {
            http_response_code(404);
            echo "News article not found.";
            return;
        }

        $this->form("edit", $news, $newsID);
    }

    public function update(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $newsID = $this->getID();

        if ($newsID === null) {
            http_response_code(404);
            echo "News article not found.";
            return;
        }

        $values = [];

        try {
            $values = $this->postValues();

            $this->newsService->updateNews(
                $newsID,
                $values
            );

            $this->redirectToList(
                "News article updated successfully."
            );

        } catch (InvalidArgumentException $error) {
            $this->form(
                "edit", $values, $newsID, $error->getMessage()
            );

        } catch (DomainException $error) {
            http_response_code(404);
            echo "News article not found.";

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "edit", $values, $newsID,
                "Could not update the news article."
            );
        }
    }

    public function delete(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $newsID = filter_input(
            INPUT_POST,
            "newsID",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!is_int($newsID)) {
            http_response_code(400);
            echo "Invalid news ID.";
            return;
        }

        try {
            $deleted = $this->newsService
                ->deleteNews($newsID);

            $this->redirectToList(
                $deleted
                    ? "News article deleted successfully."
                    : "News article not found."
            );

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->redirectToList(
                "Could not delete the news article."
            );
        }
    }
}
