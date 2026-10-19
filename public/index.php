<?php

use App\Core\Database;

require_once __DIR__ . "/../src/Core/Database.php";

$config = require __DIR__ . "/../config/database.php";

try {
    $database = new Database($config);
    $pdo = $database->getConnection();

    echo "Database connection successful.";
} catch (PDOException $error) {
    die("Database connection failed: " . $error->getMessage());
}