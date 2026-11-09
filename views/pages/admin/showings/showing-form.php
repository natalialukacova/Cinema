<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
$isEdit = $mode === "edit";

$action = $isEdit
    ? $basePath . "/admin/showings/update?id=" . (int) $showingID
    : $basePath . "/admin/showings/create";

$dateValue = str_replace(
    " ",
    "T",
    substr($values["startDateTime"] ?? "", 0, 16)
);
?>

<main>
    <h1><?= $isEdit ? "Edit Showing" : "Add Showing" ?></h1>

    <?php if (!empty($error)): ?>
        <p role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>
    <?php endif; ?>

    <form
        action="<?= htmlspecialchars($action, ENT_QUOTES, "UTF-8") ?>"
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
            <label for="movieID">Movie</label>

            <select id="movieID" name="movieID" required>
                <option value="">Select movie</option>

                <?php foreach ($movies as $movie): ?>
                    <option
                        value="<?= (int) $movie->getMovieID() ?>"
                        <?= (string) ($values["movieID"] ?? "")
                            === (string) $movie->getMovieID()
                            ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars(
                            $movie->getTitle(),
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="venueID">Venue</label>

            <select id="venueID" name="venueID" required>
                <option value="">Select venue</option>

                <?php foreach ($venues as $venue): ?>
                    <option
                        value="<?= (int) $venue["venueID"] ?>"
                        <?= (string) ($values["venueID"] ?? "")
                            === (string) $venue["venueID"]
                            ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars(
                            $venue["name"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="startDateTime">Date and Time</label>
            <input
                type="datetime-local"
                id="startDateTime"
                name="startDateTime"
                required
                value="<?= htmlspecialchars(
                    $dateValue,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="basePrice">Ticket Price (DKK)</label>
            <input
                type="number"
                id="basePrice"
                name="basePrice"
                min="0"
                step="0.01"
                required
                value="<?= htmlspecialchars(
                    $values["basePrice"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="isPremiere">Premiere</label>

            <select id="isPremiere" name="isPremiere">
                <option value="0"
                    <?= (int) ($values["isPremiere"] ?? 0) === 0
                        ? "selected" : "" ?>>
                    No
                </option>

                <option value="1"
                    <?= (int) ($values["isPremiere"] ?? 0) === 1
                        ? "selected" : "" ?>>
                    Yes
                </option>
            </select>
        </div>

        <div>
            <label for="status">Status</label>

            <select id="status" name="status">
                <?php foreach (
                    ["scheduled", "cancelled", "completed"] as $status
                ): ?>
                    <option
                        value="<?= $status ?>"
                        <?= ($values["status"] ?? "scheduled")
                            === $status ? "selected" : "" ?>
                    >
                        <?= ucfirst($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">
            <?= $isEdit ? "Save Changes" : "Create Showing" ?>
        </button>
    </form>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/showings",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">Back to Showings</a>
    </p>
</main>
