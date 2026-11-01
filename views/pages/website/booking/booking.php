<main>
    <h1>Book Tickets</h1>

    <h2>
        <?= htmlspecialchars(
            $showing["movieTitle"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </h2>

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
        Price per ticket:
        <?= number_format(
            (float) $showing["basePrice"],
            2
        ) ?> DKK
    </p>

    <?php if (!empty($error)): ?>
        <p role="alert">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">

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

        <label for="ticketCount">
            Number of tickets
        </label>

        <select id="ticketCount" name="ticketCount">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>">
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">
            Confirm Booking
        </button>
    </form>
</main>
