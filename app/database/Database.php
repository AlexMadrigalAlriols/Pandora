<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static $connection;

    /**
     * Retrieves the database connection.
     *
     * @return PDO The database connection.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/database.php';
            
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $username = $config['user'];
            $password = $config['password'];

            try {
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
