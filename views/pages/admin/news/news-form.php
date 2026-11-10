<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
$isEdit = $mode === "edit";

$action = $isEdit
    ? $basePath . "/admin/news/update?id=" . (int) $newsID
    : $basePath . "/admin/news/create";
?>

<main>
    <h1>
        <?= $isEdit ? "Edit News Article" : "Add News Article" ?>
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
            <label for="title">Title</label>

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
            <label for="content">Content</label>

            <textarea
                id="content"
                name="content"
                rows="8"
                required
            ><?= htmlspecialchars(
                $values["content"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?></textarea>
        </div>

        <div>
            <label for="imageURL">
                Image URL (optional)
            </label>

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

        <div>
            <label for="isPublished">
                Publication Status
            </label>

            <select id="isPublished" name="isPublished">
                <option value="0"
                    <?= (int) ($values["isPublished"] ?? 0) === 0
                        ? "selected" : "" ?>>
                    Draft
                </option>

                <option value="1"
                    <?= (int) ($values["isPublished"] ?? 0) === 1
                        ? "selected" : "" ?>>
                    Published
                </option>
            </select>
        </div>

        <button type="submit">
            <?= $isEdit ? "Save Changes" : "Create Article" ?>
        </button>
    </form>

    <p>
        <a href="<?= htmlspecialchars(
            $basePath . "/admin/news",
            ENT_QUOTES,
            "UTF-8"
        ) ?>">
            Back to News
        </a>
    </p>
</main>
