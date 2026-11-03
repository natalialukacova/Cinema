<?php

namespace App\Controllers\Admin;

use App\Core\AdminGuard;
use App\Core\View;
use App\Models\Movie;
use App\Services\MovieService;
use DomainException;
use InvalidArgumentException;
use Throwable;

class AdminMovieController
{
    public function __construct(
        private MovieService $movieService,
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

    private function getMovieID(): ?int
    {
        $id = filter_input(
            INPUT_GET,
            "id",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        return is_int($id) ? $id : null;
    }

    private function postData(): array
    {
        $fields = [
            "title",
            "releaseDate",
            "durationMinutes",
            "description",
            "ageRating",
            "imageURL"
        ];

        $data = [];

        foreach ($fields as $field) {
            $value = $_POST[$field] ?? "";

            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    "Invalid form data."
                );
            }

            $data[$field] = $value;
        }

        return $data;
    }

    private function valuesFromMovie(Movie $movie): array
    {
        return [
            "title" => $movie->getTitle(),
            "releaseDate" => $movie->getReleaseDate(),
            "durationMinutes" => (string) $movie->getDurationMinutes(),
            "description" => $movie->getDescription() ?? "",
            "ageRating" => $movie->getAgeRating(),
            "imageURL" => $movie->getImageURL() ?? ""
        ];
    }

    private function form(
        string $mode,
        array $values = [],
        ?int $movieID = null,
        ?string $error = null
    ): void {
        View::render(
            "pages/admin/movies/movie-form",
            [
                "title" => $mode === "edit"
                    ? "Edit Movie"
                    : "Add Movie",
                "mode" => $mode,
                "values" => $values,
                "movieID" => $movieID,
                "error" => $error,
                "csrfToken" => $this->csrfToken()
            ]
        );
    }

    private function redirectToList(string $message): void
    {
        $_SESSION["admin_movie_message"] = $message;

        header(
            "Location: " . $this->basePath() . "/admin/movies",
            true,
            303
        );
        exit;
    }

    public function index(): void
    {
        $this->adminGuard->requireAdmin();

        $movies = $this->movieService->getAllMovies();

        $message = $_SESSION["admin_movie_message"] ?? null;
        unset($_SESSION["admin_movie_message"]);

        View::render(
            "pages/admin/movies/movie-list",
            [
                "title" => "Manage Movies",
                "movies" => $movies,
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
            $values = $this->postData();
            $this->movieService->createMovie($values);

            $this->redirectToList("Movie created successfully.");

        } catch (InvalidArgumentException $error) {
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
                "Could not save the movie."
            );
        }
    }

    public function edit(): void
    {
        $this->adminGuard->requireAdmin();

        $movieID = $this->getMovieID();

        $movie = $movieID !== null
            ? $this->movieService->getMovieByID($movieID)
            : null;

        if ($movie === null) {
            http_response_code(404);
            echo "Movie not found.";
            return;
        }

        $this->form(
            "edit",
            $this->valuesFromMovie($movie),
            $movieID
        );
    }

    public function update(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $movieID = $this->getMovieID();

        if ($movieID === null) {
            http_response_code(404);
            echo "Movie not found.";
            return;
        }

        $values = [];

        try {
            $values = $this->postData();

            $this->movieService->updateMovie(
                $movieID,
                $values
            );

            $this->redirectToList("Movie updated successfully.");

        } catch (InvalidArgumentException $error) {
            $this->form(
                "edit",
                $values,
                $movieID,
                $error->getMessage()
            );

        } catch (DomainException $error) {
            http_response_code(404);
            echo "Movie not found.";

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->form(
                "edit",
                $values,
                $movieID,
                "Could not update the movie."
            );
        }
    }

    public function delete(): void
    {
        $this->adminGuard->requireAdmin();
        $this->checkCsrf();

        $movieID = filter_input(
            INPUT_POST,
            "movieID",
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!is_int($movieID)) {
            http_response_code(400);
            echo "Invalid movie ID.";
            return;
        }

        try {
            $deleted = $this->movieService
                ->deleteMovie($movieID);

            $this->redirectToList(
                $deleted
                    ? "Movie deleted successfully."
                    : "Movie cannot be deleted. It may have existing showings."
            );

        } catch (Throwable $error) {
            error_log($error->getMessage());

            $this->redirectToList(
                "Could not delete the movie."
            );
        }
    }
}
