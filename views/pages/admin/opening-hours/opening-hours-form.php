<?php
$basePath = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/\\");

$dayNames = [
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
    7 => "Sunday"
];

// Organize saved opening hours by day
$hoursByDay = [];

foreach ($hours as $hour) {
    $hoursByDay[$hour->getDayOfWeek()] = $hour;
}
?>

<main>
    <h1>Manage Opening Hours</h1>

    <p>Set the opening hours for each day of the week.</p>

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
            $basePath . "/admin/opening-hours",
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

        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Opens At</th>
                    <th>Closes At</th>
                    <th>Closed</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($dayNames as $day => $dayName): ?>
                    <?php
                    $hour = $hoursByDay[$day] ?? null;

                    $opensAt = substr($hour?->getOpensAt() ?? "", 0, 5);
                    $closesAt = substr($hour?->getClosesAt() ?? "", 0, 5);
                    ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $dayName,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </td>

                        <td>
                            <label for="opens-<?= $day ?>">
                                Opening time
                            </label>

                            <input
                                type="time"
                                id="opens-<?= $day ?>"
                                name="days[<?= $day ?>][opensAt]"
                                value="<?= htmlspecialchars(
                                    $opensAt,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                            >
                        </td>

                        <td>
                            <label for="closes-<?= $day ?>">
                                Closing time
                            </label>

                            <input
                                type="time"
                                id="closes-<?= $day ?>"
                                name="days[<?= $day ?>][closesAt]"
                                value="<?= htmlspecialchars(
                                    $closesAt,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                            >
                        </td>

                        <td>
                            <label for="closed-<?= $day ?>">
                                <input
                                    type="checkbox"
                                    id="closed-<?= $day ?>"
                                    name="days[<?= $day ?>][isClosed]"
                                    value="1"
                                    <?= ($hour?->getIsClosed() ?? false)
                                        ? "checked" : "" ?>
                                >
                                Closed
                            </label>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            Closed days will not have opening or closing times.
        </p>

        <button type="submit">
            Save Opening Hours
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
