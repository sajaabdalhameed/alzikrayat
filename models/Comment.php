<?php
/**
 * Comment
 * Handles all database operations related to the comments table:
 * storing a new comment and fetching all comments for a specific photo.
 */
class Comment extends Model
{
    /**
     * Inserts a new comment linked to a photo and the commenting user.
     * @param array $submittedData Associative array with photo_id, user_id, comment
     * @return bool True on success
     */
    public function create($submittedData)
    {
        $addQuery = "INSERT INTO comments (photo_id, user_id, comment)
                        VALUES (:photoId, :userId, :commentText)";

        $queryHandle = $this->databaseHandle->prepare($addQuery);
        return $queryHandle->execute([
            ":photoId" => $submittedData["photo_id"],
            ":userId" => $submittedData["user_id"],
            ":commentText" => $submittedData["comment"]
        ]);
    }

    /**
     * Returns all comments for a given photo, ordered oldest first,
     * joined with the commenter's name.
     * @param int $ownerPhotoId
     * @return array List of comment rows
     */
    public function getByPhotoId($ownerPhotoId)
    {
        $listQuery = "SELECT comments.*, users.first_name, users.last_name
                        FROM comments
                        JOIN users ON comments.user_id = users.id
                        WHERE comments.photo_id = :photoId
                        ORDER BY comments.date_time ASC";

        $queryHandle = $this->databaseHandle->prepare($listQuery);
        $queryHandle->execute([":photoId" => $ownerPhotoId]);
        return $queryHandle->fetchAll(PDO::FETCH_ASSOC);
    }
}