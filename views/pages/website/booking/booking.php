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

    <form
        action="<?= htmlspecialchars(
            rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
            . "/checkout",
            ENT_QUOTES,
            "UTF-8"
        ) ?>"
        method="GET"
    >

        <input
            type="hidden"
            name="showingID"
            value="<?= (int) $showing["showingID"] ?>"
        >

        <label for="ticketCount">Number of tickets</label>

        <select id="ticketCount" name="ticketCount">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>">
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">
            Continue to Checkout
        </button>
    </form>
</main>
