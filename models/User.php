<?php
/**
 * User
 * Handles all database operations related to the users table:
 * creating new users, finding a user by email, and finding a user by id.
 */
class User extends Model
{
    /**
     * Inserts a new user into the database with a hashed password.
     * @param array $registrationData Associative array with first_name, last_name, email, password
     * @return bool True on success
     */
    public function create($registrationData)
    {
        $securedPassword = password_hash($registrationData["password"], PASSWORD_BCRYPT);

        $insertQuery = "INSERT INTO users (first_name, last_name, email, password)
                     VALUES (:firstName, :lastName, :email, :password)";

        $stmtHandle = $this->databaseHandle->prepare($insertQuery);
        return $stmtHandle->execute([
            ":firstName" => $registrationData["first_name"],
            ":lastName" => $registrationData["last_name"],
            ":email" => $registrationData["email"],
            ":password" => $securedPassword
        ]);
    }

    /**
     * Finds a single user row by their email address.
     * @param string $lookupEmail
     * @return array|false The user row, or false if not found
     */
    public function findByEmail($lookupEmail)
    {
        $selectQuery = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmtHandle = $this->databaseHandle->prepare($selectQuery);
        $stmtHandle->execute([":email" => $lookupEmail]);
        return $stmtHandle->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Finds a single user row by their id.
     * @param int $lookupId
     * @return array|false The user row, or false if not found
     */
    public function findById($lookupId)
    {
        $selectQuery = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmtHandle = $this->databaseHandle->prepare($selectQuery);
        $stmtHandle->execute([":id" => $lookupId]);
        return $stmtHandle->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Counts how many registered users exist. Used for homepage stats.
     * @return int
     */
    public function countAll()
    {
        $countQuery = "SELECT COUNT(*) AS totalCount FROM users";
        $stmtHandle = $this->databaseHandle->prepare($countQuery);
        $stmtHandle->execute();
        return (int) $stmtHandle->fetch(PDO::FETCH_ASSOC)["totalCount"];
    }
}