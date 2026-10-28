<main>
    <h1>Create an Account</h1>

    <?php if (!empty($error)): ?>
        <p role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p role="status">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <div>
            <label for="firstName">First Name</label>
            <input
                type="text"
                id="firstName"
                name="firstName"
                maxlength="100"
                autocomplete="given-name"
                required
            >
        </div>

        <div>
            <label for="lastName">Last Name</label>
            <input
                type="text"
                id="lastName"
                name="lastName"
                maxlength="100"
                autocomplete="family-name"
                required
            >
        </div>

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="190"
                autocomplete="email"
                required
            >
        </div>

        <div>
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="6"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit">
            Create Account
        </button>

    </form>
</main>
