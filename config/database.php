<?php
/**
 * DatabaseConnection
 * Handles a single shared PDO connection to the database using the Singleton pattern.
 * Ensures only one connection instance is created and reused across the whole application.
 */
class DatabaseConnection
{
    private static $sharedInstance = null;
    private $activeLink;

    private $serverHost = "localhost";
    private $targetDbName = "alzikrayat";
    private $dbUsername = "root";
    private $dbSecret = "";

    /**
     * Private constructor to prevent creating multiple instances from outside the class.
     * Establishes the PDO connection and sets error mode to exceptions.
     */
    private function __construct()
    {
        try {
            $connectionString = "mysql:host={$this->serverHost};dbname={$this->targetDbName};charset=utf8mb4";
            $this->activeLink = new PDO($connectionString, $this->dbUsername, $this->dbSecret);
            $this->activeLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $linkFailure) {
            die("Database connection failed: " . $linkFailure->getMessage());
        }
    }

    /**
     * Returns the single shared PDO connection instance.
     * Creates it on first call, then reuses it on every later call.
     * @return PDO
     */
    public static function getConnection()
    {
        if (self::$sharedInstance === null) {
            self::$sharedInstance = new self();
        }
        return self::$sharedInstance->activeLink;
    }
}