<main>
    <h1>Cinema Venues</h1>

    <?php if (empty($venues)): ?>
        <p>No venues available.</p>
    <?php else: ?>

        <?php foreach ($venues as $venue): ?>
            <article>
                <h2>
                    <?= htmlspecialchars(
                        $venue["name"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </h2>

                <p>
                    <?= (int) $venue["seatCount"] ?>
                    seats
                </p>

                <?php if ($venue["description"]): ?>
                    <p>
                        <?= htmlspecialchars(
                            $venue["description"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
