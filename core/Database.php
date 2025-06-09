<?php

require_once __DIR__ . '/../config/config.php';

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            die("Verbindung zur Datenbank fehlgeschlagen: " . $this->connection->connect_error);
        }

        // UTF-8 für Sonderzeichen
        $this->connection->set_charset("utf8mb4");
    }

    // Singleton-Zugriff
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
