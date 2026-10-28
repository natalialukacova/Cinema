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
    
    public function showLogin(?string $error = null): void
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(
                random_bytes(32)
            );
        }

        View::render(
            "pages/auth/login",
            [
                "title" => "Login",
                "csrfToken" => $_SESSION["csrf_token"],
                "error" => $error
            ]
        );
    }

    public function login(): void
    {
        $token = $_POST["csrf_token"] ?? null;

        // Validate CSRF token
        if (
            !is_string($token) ||
            !isset($_SESSION["csrf_token"]) ||
            !hash_equals($_SESSION["csrf_token"], $token)
        ) {
            http_response_code(403);
            $this->showLogin("Invalid or expired form.");
            return;
        }

        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;

        // Validate input types
        if (
            !is_string($email) ||
            !is_string($password)
        ) {
            http_response_code(400);
            $this->showLogin("Invalid form data.");
            return;
        }

        try {
            $user = $this->userService->login(
                $email,
                $password
            );
        } catch (Throwable $error) {
            error_log("Login error: " . $error->getMessage());
            http_response_code(500);
            $this->showLogin("Unable to sign in right now.");
            return;
        }

        if ($user === null) {
            $this->showLogin("Invalid email or password.");
            return;
        }

        // Prevent session fixation
        session_regenerate_id(true);

        // Store authenticated user
        $_SESSION["user_id"] = $user->getUserID();
        $_SESSION["user_rank"] = $user->getUserRank();

        // Generate new CSRF token
        $_SESSION["csrf_token"] = bin2hex(
            random_bytes(32)
        );

        $basePath = rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );

        header("Location: " . $basePath . "/", true, 303);
        exit;
    }

    public function logout(): void
    {
        $token = $_POST["csrf_token"] ?? null;

        if (
            !is_string($token) ||
            !isset($_SESSION["csrf_token"]) ||
            !hash_equals($_SESSION["csrf_token"], $token)
        ) {
            http_response_code(403);
            echo "Invalid CSRF token.";
            return;
        }

        // Clear session data
        $_SESSION = [];

        // Remove session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(session_name(), "", [
                "expires" => time() - 3600,
                "path" => $params["path"],
                "domain" => $params["domain"],
                "secure" => $params["secure"],
                "httponly" => $params["httponly"],
                "samesite" => $params["samesite"] ?? "Lax"
            ]);
        }

        session_destroy();

        $basePath = rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );

        header("Location: " . $basePath . "/login", true, 303);
        exit;
    }
}
