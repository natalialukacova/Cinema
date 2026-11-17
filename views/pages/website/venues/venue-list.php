<main>
    <h1>Cinema Venues</h1>

    <?php if (empty($venues)): ?>
        <p>No venues available.</p>
    <?php else: ?>

        <?php foreach ($venues as $venue): ?>
            <article>
                <h2>
                    <?= htmlspecialchars(
                        $venue->getName(),
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </h2>

                <p>
                    <?= $venue->getSeatCount() ?>
                    seats
                </p>

                <?php if ($venue->getDescription()): ?>
                    <p>
                        <?= htmlspecialchars(
                            $venue->getDescription(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
