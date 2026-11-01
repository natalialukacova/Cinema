<main>
    <h1>
        <?= htmlspecialchars(
            $movie->getTitle(),
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </h1>

    <?php if ($movie->getImageURL()): ?>
        <img
            src="<?= htmlspecialchars(
                $movie->getImageURL(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            alt="Movie poster"
        >
    <?php endif; ?>

    <p>
        Duration:
        <?= $movie->getDurationMinutes() ?> minutes
    </p>

    <p>
        Age rating:
        <?= htmlspecialchars(
            $movie->getAgeRating(),
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>

    <p>
        Release date:
        <?= htmlspecialchars(
            $movie->getReleaseDate(),
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>

    <?php if ($movie->getDescription()): ?>
        <p>
            <?= htmlspecialchars(
                $movie->getDescription(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    <?php endif; ?>

    <section>
        <h2>Upcoming Showings</h2>

        <?php if (empty($showings)): ?>
            <p>No upcoming showings available.</p>
        <?php else: ?>

            <?php foreach ($showings as $showing): ?>
                <article>
                    <h3>
                        <?= htmlspecialchars(
                            $showing["venueName"],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h3>

                    <p>
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
                </article>
            <?php endforeach; ?>

        <?php endif; ?>
    </section>
</main>
