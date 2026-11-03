<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Services\AdminService;

class AdminController
{
    public function __construct(
        private AdminService $adminService
    ) {
    }

    private function requireAdmin(): void
    {
        $userID = filter_var(
            $_SESSION["user_id"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        // Not logged in
        if (!is_int($userID)) {
            $basePath = rtrim(
                dirname($_SERVER["SCRIPT_NAME"]),
                "/\\"
            );

            header("Location: " . $basePath . "/login");
            exit;
        }

        // Logged in, but not an administrator
        if (!$this->adminService->isAdmin($userID)) {
            http_response_code(403);
            echo "403 - Access denied.";
            exit;
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $stats = $this->adminService->getBookingStats();

        View::render(
            "pages/admin/dashboard",
            [
                "title" => "Admin Dashboard",
                "stats" => $stats
            ]
        );
    }

    public function bookings(): void
    {
        $this->requireAdmin();

        $bookings = $this->adminService
            ->getAllBookings();

        View::render(
            "pages/admin/bookings/bookings",
            [
                "title" => "Booking Overview",
                "bookings" => $bookings
            ]
        );
    }
}
