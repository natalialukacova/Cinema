<main>
    <h1>My Profile</h1>

    <section>
        <h2>Personal Information</h2>

        <p>
            <strong>First Name:</strong>
            <?= htmlspecialchars(
                $user->getFirstName(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p>
            <strong>Last Name:</strong>
            <?= htmlspecialchars(
                $user->getLastName(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?= htmlspecialchars(
                $user->getEmail(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    </section>
</main>
