<main>
    <h1>Cinema News</h1>

    <?php if (empty($news)): ?>
        <p>No news available.</p>
    <?php else: ?>

        <?php foreach ($news as $article): ?>
            <article>
                <h2>
                    <?= htmlspecialchars(
                        $article["title"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </h2>

                <p>
                    Published:
                    <?= htmlspecialchars(
                        $article["publishedAt"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <?php if (!empty($article["imageURL"])): ?>
                    <img
                        src="<?= htmlspecialchars(
                            $article["imageURL"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        alt=""
                        width="300"
                    >
                <?php endif; ?>

                <p>
                    <?= nl2br(htmlspecialchars(
                        $article["content"],
                        ENT_QUOTES,
                        "UTF-8"
                    )) ?>
                </p>
            </article>

            <hr>
        <?php endforeach; ?>

    <?php endif; ?>
</main>
