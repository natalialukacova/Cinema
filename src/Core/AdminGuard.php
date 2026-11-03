<?php

namespace App\Core;

use App\Services\AdminService;

class AdminGuard
{
    public function __construct(
        private AdminService $adminService
    ) {
    }

    public function requireAdmin(): void
    {
        $userID = filter_var(
            $_SESSION["user_id"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!is_int($userID)) {
            $basePath = rtrim(
                dirname($_SERVER["SCRIPT_NAME"]),
                "/\\"
            );

            header("Location: " . $basePath . "/login");
            exit;
        }

        if (!$this->adminService->isAdmin($userID)) {
            http_response_code(403);
            echo "403 - Access denied.";
            exit;
        }
    }
}
