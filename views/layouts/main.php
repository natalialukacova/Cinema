<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title ?? "Cinema", ENT_QUOTES, 'UTF-8') ?>
    </title>
</head>
<body>

<?php
$basePath = rtrim(
    dirname($_SERVER["SCRIPT_NAME"]),
    "/\\"
);
?>

<nav>
    <a href="<?= htmlspecialchars(
        $basePath . "/",
        ENT_QUOTES,
        'UTF-8'
    ) ?>">Home</a>

    <a href="<?= htmlspecialchars(
        $basePath . "/venues",
        ENT_QUOTES,
        "UTF-8"
    ) ?>">Venues</a>

    <a href="<?= htmlspecialchars(
        $basePath . "/showings",
        ENT_QUOTES,
        "UTF-8"
    ) ?>">Showings</a>

    <?php if (isset($_SESSION["user_id"])): ?>

        <a href="<?= htmlspecialchars(
            $basePath . "/profile",
            ENT_QUOTES,
            'UTF-8'
        ) ?>">
            My Profile
        </a>

        <span>Logged in</span>

        <form
            action="<?= htmlspecialchars(
                $basePath . "/logout",
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            method="POST"
        >
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION["csrf_token"] ?? "",
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <button type="submit">Logout</button>
        </form>

    <?php else: ?>

        <a href="<?= htmlspecialchars(
            $basePath . "/login",
            ENT_QUOTES,
            'UTF-8'
        ) ?>">Login</a>

        <a href="<?= htmlspecialchars(
            $basePath . "/register",
            ENT_QUOTES,
            'UTF-8'
        ) ?>">Register</a>

    <?php endif; ?>
</nav>

    <?= $content ?>

</body>
</html>