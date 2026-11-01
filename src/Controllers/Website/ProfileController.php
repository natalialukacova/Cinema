<?php

namespace App\Controllers\Website;

use App\Core\View;
use App\Services\UserService;

class ProfileController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): void
    {
        $basePath = rtrim(
            dirname($_SERVER["SCRIPT_NAME"]),
            "/\\"
        );

        // Check authentication
        if (!isset($_SESSION["user_id"])) {
            header("Location: " . $basePath . "/login", true, 302);
            exit;
        }

        $userID = filter_var(
            $_SESSION["user_id"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($userID === false) {
            unset($_SESSION["user_id"], $_SESSION["user_rank"]);
            header("Location: " . $basePath . "/login", true, 302);
            exit;
        }

        // Load user from database
        $user = $this->userService->getUserByID($userID);

        if ($user === null) {
            unset($_SESSION["user_id"], $_SESSION["user_rank"]);
            header("Location: " . $basePath . "/login", true, 302);
            exit;
        }

        View::render(
            "pages/website/profile",
            [
                "title" => "My Profile",
                "user" => $user
            ]
        );
    }
}