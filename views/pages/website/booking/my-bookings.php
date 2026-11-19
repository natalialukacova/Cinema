<main>
    <h1>My Bookings</h1>

    <?php if (empty($bookings)): ?>
        <p>You have no bookings yet.</p>
    <?php else: ?>

        <?php foreach ($bookings as $booking): ?>
            <article>
                <h2>
                    <?= htmlspecialchars(
                        $booking->getMovieTitle(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </h2>

                <p>
                    Booking #<?= (int) $booking->getBookingID() ?>
                </p>

                <p>
                    Venue:
                    <?= htmlspecialchars(
                        $booking->getVenueName(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <p>
                    Showing:
                    <?= htmlspecialchars(
                        $booking->getStartDateTime(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <p>
                    Tickets:
                    <?= (int) $booking->getTicketCount() ?>
                </p>

                <p>
                    Total:
                    <?= number_format(
                        (float) $booking->getTotalPrice(),
                        2
                    ) ?> DKK
                </p>

                <p>
                    Status:
                    <?= htmlspecialchars(
                        $booking->getStatus(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <a href="<?= htmlspecialchars(
                    rtrim(
                        dirname($_SERVER["SCRIPT_NAME"]),
                        "/\\"
                    ) . "/booking-confirmation?id="
                    . (int) $booking->getBookingID(),
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>">
                    View Booking
                </a>
            </article>

            <hr>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
