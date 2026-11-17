<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
?>

<main>
    <h1>Manage News</h1>

    <?php if (!empty($message)): ?>
        <p role="status">
            <?= htmlspecialchars(
                $message, ENT_QUOTES, "UTF-8"
            ) ?>
        </p>
    <?php endif; ?>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/news/create",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Add News Article
        </a>
    </p>

    <?php if (empty($news)): ?>
        <p>No news articles available.</p>
    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Published At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($news as $article): ?>
                    <tr>
                        <td>
                            <?= (int) $article->getNewsID() ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $article->getTitle(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= $article->getIsPublished()
                                ? "Published"
                                : "Draft" ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $article->getPublishedAt() ?? "-",
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <a href="<?= htmlspecialchars(
                                $basePath . "/admin/news/edit?id="
                                . (int) $article->getNewsID(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>">
                                Edit
                            </a>

                            <form
                                action="<?= htmlspecialchars(
                                    $basePath . "/admin/news/delete",
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
                                    name="newsID"
                                    value="<?= (int) $article->getNewsID() ?>"
                                >

                                <button type="submit">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Back to Dashboard
        </a>
    </p>
</main>
