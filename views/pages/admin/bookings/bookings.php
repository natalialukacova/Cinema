<main>
    <h1>Booking Overview</h1>

    <?php if (empty($bookings)): ?>
        <p>No bookings found.</p>
    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Movie</th>
                    <th>Venue</th>
                    <th>Showing</th>
                    <th>Tickets</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>
                            <?= (int) $booking["bookingID"] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["firstName"]
                                . " "
                                . $booking["lastName"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["email"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["movieTitle"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["venueName"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["startDateTime"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= (int) $booking["ticketCount"] ?>
                        </td>

                        <td>
                            <?= number_format(
                                (float) $booking["totalPrice"],
                                2
                            ) ?> DKK
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $booking["status"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <p>
        <a href="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/admin",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Back to Dashboard
        </a>
    </p>
</main>
