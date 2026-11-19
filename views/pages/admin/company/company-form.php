<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");
?>

<main>
    <h1>Manage Company</h1>

    <p>Edit your cinema information.</p>

    <?php if (!empty($message)): ?>
        <p role="status">
            <?= htmlspecialchars(
                $message,
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </p>
    <?php endif; ?>

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
            $basePath . "/admin/company",
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

        <!-- Company details -->
        <div>
            <label for="name">Company Name</label>

            <input
                type="text"
                id="name"
                name="name"
                maxlength="150"
                required
                value="<?= htmlspecialchars(
                    $values["name"] ?? "",
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
                rows="6"
            ><?= htmlspecialchars(
                $values["description"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?></textarea>
        </div>

        <!-- Contact information -->
        <div>
            <label for="email">Email</label>

            <input
                type="email"
                id="email"
                name="email"
                maxlength="190"
                required
                value="<?= htmlspecialchars(
                    $values["email"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="phone">Phone</label>

            <input
                type="text"
                id="phone"
                name="phone"
                maxlength="30"
                value="<?= htmlspecialchars(
                    $values["phone"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <div>
            <label for="address">Address</label>

            <input
                type="text"
                id="address"
                name="address"
                maxlength="255"
                value="<?= htmlspecialchars(
                    $values["address"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
            >
        </div>

        <button type="submit">
            Save Changes
        </button>
    </form>

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
