<?php
/**
 * Model
 * Abstract base class for all Model classes (User, Photo, Comment).
 * Provides a shared database connection to any model that extends it.
 */
abstract class Model
{
    protected $databaseHandle;

    /**
     * Gets the shared PDO connection from DatabaseConnection when a Model is created.
     */
    public function __construct()
    {
        $this->databaseHandle = DatabaseConnection::getConnection();
    }
}