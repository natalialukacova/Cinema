<main>
    <h1>Booking Confirmed</h1>

    <p>
        Booking ID:
        <?= (int) $booking["bookingID"] ?>
    </p>

    <h2>
        <?= htmlspecialchars(
            $booking["movieTitle"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </h2>

    <p>
        Venue:
        <?= htmlspecialchars(
            $booking["venueName"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Date:
        <?= htmlspecialchars(
            $booking["startDateTime"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Seats:
        <?php
        $seats = array_map(
            fn($seat) => $seat["rowLabel"] . $seat["seatNumber"],
            $booking["seats"]
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
            (float) $booking["totalPrice"],
            2
        ) ?> DKK
    </p>

    <p>
        Reservation confirmed. No payment has been processed.
    </p>
</main>
