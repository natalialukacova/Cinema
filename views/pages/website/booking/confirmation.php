<main>
    <h1>Booking Confirmed</h1>

    <p>
        Booking ID:
        <?= (int) $booking->getBookingID() ?>
    </p>

    <h2>
        <?= htmlspecialchars(
            $booking->getMovieTitle(),
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </h2>

    <p>
        Venue:
        <?= htmlspecialchars(
            $booking->getVenueName(),
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Date:
        <?= htmlspecialchars(
            $booking->getStartDateTime(),
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Seats:
        <?php
        $seats = array_map(
            fn($seat) => $seat["rowLabel"] . $seat["seatNumber"],
            $booking->getSeats()
        );
        ?>
        <?= htmlspecialchars(
            implode(", ", $seats),
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Total:
        <?= number_format(
            (float) $booking->getTotalPrice(),
            2
        ) ?> DKK
    </p>

    <p>
        Reservation confirmed. No payment has been processed.
    </p>
</main>
