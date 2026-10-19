<?php

namespace App\Core;

use PDO;

class Database
{
    private PDO $connection;

    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config["host"]};"
             . "dbname={$config["database"]};"
             . "charset={$config["charset"]}";

        $this->connection = new PDO(
            $dsn,
            $config["username"],
            $config["password"]
        );

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->connection->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}