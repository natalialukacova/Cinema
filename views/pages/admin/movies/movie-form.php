<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");

$isEdit = $mode === "edit";

$action = $basePath . (
    $isEdit
        ? "/admin/movies/update?id=" . (int) $movieID
        : "/admin/movies/create"
);
?>

<main>
    <h1>
        <?= $isEdit ? "Edit Movie" : "Add Movie" ?>
    </h1>

    <?php if (!empty($error)): ?>
        <p role="alert">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>
    <?php endif; ?>

    <form
        action="<?= htmlspecialchars(
            $action,
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

        <div>
            <label for="title">Movie Title</label>
            <input
                type="text"
                id="title"
                name="title"
                maxlength="200"
                required
                value="<?= htmlspecialchars(
                    $values["title"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="releaseDate">Release Date</label>
            <input
                type="date"
                id="releaseDate"
                name="releaseDate"
                required
                value="<?= htmlspecialchars(
                    $values["releaseDate"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="durationMinutes">
                Duration (minutes)
            </label>
            <input
                type="number"
                id="durationMinutes"
                name="durationMinutes"
                min="1"
                max="600"
                required
                value="<?= htmlspecialchars(
                    $values["durationMinutes"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                maxlength="10000"
                rows="5"
            ><?= htmlspecialchars(
                $values["description"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?></textarea>
        </div>

        <div>
            <label for="ageRating">Age Rating</label>
            <input
                type="text"
                id="ageRating"
                name="ageRating"
                maxlength="10"
                placeholder="12A"
                required
                value="<?= htmlspecialchars(
                    $values["ageRating"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="imageURL">Image URL (optional)</label>
            <input
                type="url"
                id="imageURL"
                name="imageURL"
                maxlength="500"
                value="<?= htmlspecialchars(
                    $values["imageURL"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <button type="submit">
            <?= $isEdit ? "Save Changes" : "Create Movie" ?>
        </button>
    </form>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/movies",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Back to Movies
        </a>
    </p>
</main>
