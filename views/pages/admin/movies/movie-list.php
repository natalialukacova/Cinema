<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
?>

<main>
    <h1>Manage Movies</h1>

    <?php if (!empty($message)): ?>
        <p role="status">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/movies/create",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Add New Movie
        </a>
    </p>

    <?php if (empty($movies)): ?>
        <p>No movies available.</p>
    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Release Date</th>
                    <th>Duration</th>
                    <th>Age Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?= (int) $movie->getMovieID() ?></td>

                        <td>
                            <?= htmlspecialchars(
                                $movie->getTitle(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $movie->getReleaseDate(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <?= (int) $movie->getDurationMinutes() ?> min
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $movie->getAgeRating(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <a href="<?= htmlspecialchars(
                                $basePath . "/admin/movies/edit?id="
                                . (int) $movie->getMovieID(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>">
                                Edit
                            </a>

                            <form
                                action="<?= htmlspecialchars(
                                    $basePath . "/admin/movies/delete",
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
                                    name="movieID"
                                    value="<?= (int) $movie->getMovieID() ?>"
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
