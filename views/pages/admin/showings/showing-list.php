<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
?>

<main>
    <h1>Manage Showings</h1>

    <?php if (!empty($message)): ?>
        <p role="status">
            <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/showings/create",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">Add New Showing</a>
    </p>

    <?php if (empty($showings)): ?>
        <p>No showings available.</p>
    <?php else: ?>

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movie</th>
                    <th>Venue</th>
                    <th>Date & Time</th>
                    <th>Price</th>
                    <th>Premiere</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($showings as $showing): ?>
                    <tr>
                        <td><?= (int) $showing->getShowingID() ?></td>

                        <td><?= htmlspecialchars(
                            $showing->getMovieTitle(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td><?= htmlspecialchars(
                            $showing->getVenueName(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td><?= htmlspecialchars(
                            $showing->getStartDateTime(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td>
                            <?= number_format(
                                (float) $showing->getBasePrice(),
                                2
                            ) ?> DKK
                        </td>

                        <td>
                            <?= $showing->getIsPremiere() ? "Yes" : "No" ?>
                        </td>

                        <td><?= htmlspecialchars(
                            $showing->getStatus(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td>
                            <a href="<?= htmlspecialchars(
                                $basePath . "/admin/showings/edit?id="
                                . (int) $showing->getShowingID(),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>">Edit</a>

                            <form
                                action="<?= htmlspecialchars(
                                    $basePath . "/admin/showings/delete",
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
                                    name="showingID"
                                    value="<?= (int) $showing->getShowingID() ?>"
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
        ) ?>">Back to Dashboard</a>
    </p>
</main>
