<?php
/**
 * CommentController
 * Handles submitting a new comment on a photo. Requires an active session.
 */
class CommentController extends Controller
{
    /**
     * Validates and stores a new comment, then redirects back to the photo page.
     */
    public function store()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
            return;
        }

        $targetPhotoRef = $_POST["photo_id"] ?? null;
        $enteredText = trim($_POST["comment"] ?? "");

        if (empty($targetPhotoRef) || empty($enteredText)) {
            $this->redirectTo("/alzikrayat/public/photo/" . $targetPhotoRef);
            return;
        }

        $discussionModel = new Comment();
        $discussionModel->create([
            "photo_id" => $targetPhotoRef,
            "user_id" => $_SESSION["user_id"],
            "comment" => $enteredText
        ]);

        $this->redirectTo("/alzikrayat/public/photo/" . $targetPhotoRef);
    }
}