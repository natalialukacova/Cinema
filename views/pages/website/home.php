<main>

    <!-- Homepage header -->
    <header>
        <h1>Cinema</h1>
        <p>Discover the movies currently available.</p>
    </header>

    <?php
    // Base path for links
    $basePath = rtrim(
        dirname($_SERVER["SCRIPT_NAME"]),
        "/\\"
    );
    ?>

    <!-- Company information -->
    <section>
        <h2>About Cinema</h2>

        <?php if ($company !== null): ?>
            <h3>
                <?= htmlspecialchars(
                    $company["name"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </h3>

            <p>
                <?= nl2br(htmlspecialchars(
                    $company["description"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                )) ?>
            </p>
        <?php else: ?>
            <p>Cinema information is not available yet.</p>
        <?php endif; ?>
    </section>

    <!-- Today's upcoming showings with booking links -->
    <section>
        <h2>Today's Showings</h2>

        <?php if (empty($todayShowings)): ?>
            <p>No more showings today.</p>
        <?php else: ?>
            <?php foreach ($todayShowings as $showing): ?>
                <article>
                    <h3>
                        <?= htmlspecialchars(
                            $showing->getMovieTitle(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars(
                            $showing->getVenueName(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <?= htmlspecialchars(
                            date(
                                "d.m.Y H:i",
                                strtotime($showing->getStartDateTime())
                            ),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>

                    <p>
                        <?= number_format(
                            (float) $showing->getBasePrice(),
                            2
                        ) ?> DKK
                    </p>

                    <a href="<?= htmlspecialchars(
                        $basePath . "/booking?showingID="
                        . (int) $showing->getShowingID(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>">
                        Book Tickets
                    </a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <p>
            <a href="<?= htmlspecialchars(
                $basePath . "/showings",
                ENT_QUOTES,
                "UTF-8"
            ) ?>">
                View All Showings
            </a>
        </p>
    </section>

    <!-- Latest published news articles -->
    <section>
        <h2>Latest News</h2>

        <?php if (empty($news)): ?>
            <p>No news available yet.</p>
        <?php else: ?>
            <?php foreach ($news as $article): ?>
                <article>
                    <h3>
                        <?= htmlspecialchars(
                            $article["title"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </h3>

                    <p>
                        <?= nl2br(htmlspecialchars(
                            $article["content"],
                            ENT_QUOTES,
                            "UTF-8"
                        )) ?>
                    </p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <p>
            <a href="<?= htmlspecialchars(
                $basePath . "/news",
                ENT_QUOTES,
                "UTF-8"
            ) ?>">
                View All News
            </a>
        </p>
    </section>

    <!-- All available movies with links to movie details -->
    <section>
        <h2>Movies</h2>

        <?php if (empty($movies)): ?>

            <p>No movies are available yet.</p>

        <?php else: ?>

            <div class="movie-list">

                <?php foreach ($movies as $movie): ?>

                    <article class="movie-card">

                        <?php if ($movie->getImageURL()): ?>
                            <img
                                src="<?= htmlspecialchars(
                                    $movie->getImageURL()
                                ) ?>"
                                alt="<?= htmlspecialchars(
                                    $movie->getTitle()
                                ) ?> poster"
                            >
                        <?php endif; ?>

                        <h3>
                            <a href="<?= htmlspecialchars(
                                rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\")
                                . "/movie?id="
                                . (int) $movie->getMovieID(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>">
                                <?= htmlspecialchars(
                                    $movie->getTitle(),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </a>
                        </h3>

                        <p>
                            <?= htmlspecialchars(
                                $movie->getAgeRating()
                            ) ?>
                            ·
                            <?= $movie->getDurationMinutes() ?> minutes
                        </p>

                        <?php if ($movie->getDescription()): ?>
                            <p>
                                <?= htmlspecialchars(
                                    $movie->getDescription()
                                ) ?>
                            </p>
                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</main>