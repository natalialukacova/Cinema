<main>
    <h1>Admin Dashboard</h1>

    <p>Welcome to the Cinema administration.</p>

    <h2>Booking Statistics</h2>

    <p>
        Total bookings:
        <?= (int) $stats["totalBookings"] ?>
    </p>

    <p>
        Confirmed bookings:
        <?= (int) $stats["confirmedBookings"] ?>
    </p>

    <p>
        Pending bookings:
        <?= (int) $stats["pendingBookings"] ?>
    </p>

    <p>
        Cancelled bookings:
        <?= (int) $stats["cancelledBookings"] ?>
    </p>

    <h2>Management</h2>

    <a href="<?= htmlspecialchars(
        rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
        . "/admin/bookings",
        ENT_QUOTES,
        "UTF-8"
    ) ?>">
        View All Bookings
    </a>

    <p>
        <a href="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/admin/movies",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Manage Movies
        </a>
    </p>
    <p>
        <a href="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/admin/showings",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Manage Showings
        </a>
    </p>
</main>
