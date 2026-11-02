<main>
    <h1>Checkout</h1>

    <h2>Order Summary</h2>

    <p>
        Movie:
        <?= htmlspecialchars(
            $showing["movieTitle"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Venue:
        <?= htmlspecialchars(
            $showing["venueName"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Date:
        <?= htmlspecialchars(
            $showing["startDateTime"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p>
        Tickets:
        <?= (int) $ticketCount ?>
    </p>

    <p>
        Price per ticket:
        <?= number_format(
            (float) $showing["basePrice"],
            2
        ) ?> DKK
    </p>

    <h3>
        Total:
        <?= htmlspecialchars(
            $totalPrice,
            ENT_QUOTES,
            "UTF-8"
        ) ?> DKK
    </h3>

    <h2>Payment Method</h2>

    <p>Pay at Cinema</p>
    <p>
        No online payment will be collected.
        Your seats will be reserved when you confirm.
    </p>

    <form
        action="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/booking?showingID="
            . (int) $showing["showingID"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>"
        method="POST"
    >
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken,
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >

        <input
            type="hidden"
            name="showingID"
            value="<?= (int) $showing["showingID"] ?>"
        >

        <input
            type="hidden"
            name="ticketCount"
            value="<?= (int) $ticketCount ?>"
        >

        <button type="submit">
            Confirm Reservation
        </button>
    </form>

    <p>
        <a href="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/booking?showingID="
            . (int) $showing["showingID"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Change Ticket Quantity
        </a>
    </p>
</main>
