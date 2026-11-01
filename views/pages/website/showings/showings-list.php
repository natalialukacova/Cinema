<main>
    <h1>Upcoming Showings</h1>

    <?php if (empty($showings)): ?>
        <p>No upcoming showings available.</p>
    <?php else: ?>

        <?php foreach ($showings as $showing): ?>
            <article>
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
                    <?= date(
                        "d.m.Y H:i",
                        strtotime($showing["startDateTime"])
                    ) ?>
                </p>

                <p>
                    Price:
                    <?= number_format(
                        (float) $showing["basePrice"],
                        2
                    ) ?> DKK
                </p>

                <a href="<?= htmlspecialchars(
                    rtrim(
                        dirname($_SERVER["SCRIPT_NAME"]),
                        "/\\"
                    ) . "/movie?id="
                    . (int) $showing["movieID"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>">
                    View Movie
                </a>
                
                <a href="<?= htmlspecialchars(
                    rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
                    . "/booking?showingID="
                    . (int) $showing["showingID"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>">
                    Book Tickets
                </a>

            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
