<main>

    <header>
        <h1>Cinema</h1>
        <p>Discover the movies currently available.</p>
    </header>

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