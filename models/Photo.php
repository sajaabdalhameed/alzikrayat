<?php
/**
 * Photo
 * Handles all database operations related to the photos table:
 * storing new photo metadata, fetching all photos, fetching a single photo,
 * and deleting a photo (with ownership check).
 */
class Photo extends Model
{
    /**
     * Inserts a new photo record linked to the uploading user.
     * @param array $uploadDetails Associative array with user_id, file_name, title, description
     * @return bool True on success
     */
    public function create($uploadDetails)
    {
        $storeQuery = "INSERT INTO photos (user_id, file_name, title, description)
                        VALUES (:userId, :fileName, :title, :description)";

        $dbStatement = $this->databaseHandle->prepare($storeQuery);
        return $dbStatement->execute([
            ":userId" => $uploadDetails["user_id"],
            ":fileName" => $uploadDetails["file_name"],
            ":title" => $uploadDetails["title"],
            ":description" => $uploadDetails["description"]
        ]);
    }

    /**
     * Fetches all uploaded photos joined with the uploader's full name.
     * @return array
     */
    public function getAll()
    {
        $fetchQuery = "SELECT photos.*, users.first_name, users.last_name 
                       FROM photos 
                       JOIN users ON photos.user_id = users.id 
                       ORDER BY photos.date_time DESC";
        $stmtHandle = $this->databaseHandle->prepare($fetchQuery);
        $stmtHandle->execute();
        return $stmtHandle->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a single photo record by ID joined with uploader details.
     * @param int $photoId
     * @return array|false
     */
    public function findById($photoId)
    {
        $fetchQuery = "SELECT photos.*, users.first_name, users.last_name 
                       FROM photos 
                       JOIN users ON photos.user_id = users.id 
                       WHERE photos.id = :id LIMIT 1";
        $stmtHandle = $this->databaseHandle->prepare($fetchQuery);
        $stmtHandle->execute([':id' => $photoId]);
        return $stmtHandle->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes a photo record only if it belongs to the active session user.
     * @param int $photoId
     * @param int $userId
     * @return bool
     */
    public function deleteIfOwner($photoId, $userId)
    {
        $deleteQuery = "DELETE FROM photos WHERE id = :photoId AND user_id = :userId";
        $stmtHandle = $this->databaseHandle->prepare($deleteQuery);
        return $stmtHandle->execute([
            ':photoId' => $photoId,
            ':userId' => $userId
        ]);
    }
}