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
                        <td><?= (int) $showing["showingID"] ?></td>

                        <td><?= htmlspecialchars(
                            $showing["movieTitle"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td><?= htmlspecialchars(
                            $showing["venueName"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td><?= htmlspecialchars(
                            $showing["startDateTime"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td>
                            <?= number_format(
                                (float) $showing["basePrice"],
                                2
                            ) ?> DKK
                        </td>

                        <td>
                            <?= (int) $showing["isPremiere"] === 1
                                ? "Yes"
                                : "No" ?>
                        </td>

                        <td><?= htmlspecialchars(
                            $showing["status"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?></td>

                        <td>
                            <a href="<?= htmlspecialchars(
                                $basePath . "/admin/showings/edit?id="
                                . (int) $showing["showingID"],
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
                                    value="<?= (int) $showing["showingID"] ?>"
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
