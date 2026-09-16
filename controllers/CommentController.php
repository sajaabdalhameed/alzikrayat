<?php
/**
 * CommentController
 * Handles submitting a new comment on a photo. Requires an active session.
 * Supports both a normal form POST (redirect back to the photo page) and
 * an AJAX request (returns JSON so the page can append the comment without
 * a full reload).
 */
class CommentController extends Controller
{
    /**
     * Validates and stores a new comment, then either returns a JSON payload
     * (for AJAX requests) or redirects back to the photo page (normal form post).
     */
    public function store()
    {
        $isAjaxRequest = (
            isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
            strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest"
        );

        if (!isset($_SESSION["user_id"])) {
            if ($isAjaxRequest) {
                $this->respondJson(["success" => false, "message" => "Please login first."], 401);
                return;
            }
            $this->redirectTo("/alzikrayat/public/login");
            return;
        }

        $targetPhotoRef = $_POST["photo_id"] ?? null;
        $enteredText = trim($_POST["comment"] ?? "");

        if (empty($targetPhotoRef) || empty($enteredText)) {
            if ($isAjaxRequest) {
                $this->respondJson(["success" => false, "message" => "Comment text is required."], 422);
                return;
            }
            $this->redirectTo("/alzikrayat/public/photo/" . $targetPhotoRef);
            return;
        }

        $discussionModel = new Comment();
        $discussionModel->create([
            "photo_id" => $targetPhotoRef,
            "user_id" => $_SESSION["user_id"],
            "comment" => $enteredText
        ]);

        if ($isAjaxRequest) {
            $this->respondJson([
                "success" => true,
                "comment" => [
                    "authorName" => $_SESSION["first_name"],
                    "text" => $enteredText
                ]
            ]);
            return;
        }

        $this->redirectTo("/alzikrayat/public/photo/" . $targetPhotoRef);
    }

    /**
     * Sends a JSON response and stops execution.
     * @param array $payload
     * @param int $statusCode
     */
    private function respondJson($payload, $statusCode = 200)
    {
        http_response_code($statusCode);
        header("Content-Type: application/json");
        echo json_encode($payload);
        exit;
    }
}
