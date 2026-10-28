<?php

namespace App\Controllers\Auth;

use App\Core\View;
use App\Services\UserService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class AuthController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function showRegister(?string $error = null): void
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(
                random_bytes(32)
            );
        }

        $success = $_SESSION["registration_success"] ?? null;
        unset($_SESSION["registration_success"]);

        View::render(
            "pages/auth/register",
            [
                "title" => "Register",
                "csrfToken" => $_SESSION["csrf_token"],
                "error" => $error,
                "success" => $success
            ]
        );
    }

    public function register(): void
    {
        // Verify CSRF token
        $token = $_POST["csrf_token"] ?? null;

        if (
            !is_string($token) ||
            !isset($_SESSION["csrf_token"]) ||
            !hash_equals($_SESSION["csrf_token"], $token)
        ) {
            http_response_code(403);
            $this->showRegister(
                "Invalid or expired form. Please try again."
            );
            return;
        }

        // Check submitted field types
        $fields = ["firstName", "lastName", "email", "password"];

        foreach ($fields as $field) {
            if (
                !isset($_POST[$field]) ||
                !is_string($_POST[$field])
            ) {
                $this->showRegister("Invalid form data.");
                return;
            }
        }

        try {
            $created = $this->userService->register(
                $_POST["firstName"],
                $_POST["lastName"],
                $_POST["email"],
                $_POST["password"]
            );

            if (!$created) {
                throw new RuntimeException(
                    "Registration failed."
                );
            }

            // Rotate CSRF token after successful registration
            $_SESSION["csrf_token"] = bin2hex(
                random_bytes(32)
            );

            $_SESSION["registration_success"] =
                "Account created successfully!";

            $basePath = rtrim(
                dirname($_SERVER["SCRIPT_NAME"]),
                "/\\"
            );

            header(
                "Location: " . $basePath . "/register",
                true,
                303
            );
            exit;

        } catch (InvalidArgumentException $error) {
            $message = $error->getMessage();

        } catch (Throwable $error) {
            // Do not expose database errors to users
            $message = (
                $error instanceof RuntimeException &&
                $error->getMessage() === "Email is already registered."
            )
                ? "Email is already registered."
                : "Registration failed. Please try again.";
        }

        $this->showRegister($message);
    }
}
