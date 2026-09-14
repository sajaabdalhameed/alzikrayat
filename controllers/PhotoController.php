<?php
/**
 * PhotoController
 * Handles displaying the photo gallery, showing a single photo with comments,
 * uploading new photos, and deleting photos (with ownership validation).
 */
class PhotoController extends Controller
{
    /**
     * Displays the gallery of all uploaded photos.
     */
    public function index()
    {
        $albumModel = new Photo();
        $fullCollection = $albumModel->getAll();
        $this->render("photos/index", ["fullCollection" => $fullCollection]);
    }

    /**
     * Displays a single photo in detail along with its comments.
     * @param int $requestedId
     */
    public function show($requestedId)
    {
        $albumModel = new Photo();
        $matchedPhoto = $albumModel->findById($requestedId);

        if (!$matchedPhoto) {
            http_response_code(404);
            echo "Photo not found.";
            return;
        }

        $discussionModel = new Comment();
        $attachedComments = $discussionModel->getByPhotoId($requestedId);

        $this->render("photos/show", [
            "matchedPhoto" => $matchedPhoto,
            "attachedComments" => $attachedComments
        ]);
    }

    /**
     * Displays the photo upload form. Requires an active session.
     */
    public function showUploadForm()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
            return;
        }
        $this->render("photos/create");
    }

    /**
     * Validates and stores an uploaded photo file plus its metadata.
     * Requires an active session.
     */
    public function store()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
            return;
        }

        if (empty($_POST["title"]) || empty($_FILES["photo_file"]["name"])) {
            $this->render("photos/create", ["failureNotice" => "Title and photo file are required."]);
            return;
        }

        $permittedTypes = ["jpg", "jpeg", "png", "gif"];
        $rawFileName = $_FILES["photo_file"]["name"];
        $detectedExtension = strtolower(pathinfo($rawFileName, PATHINFO_EXTENSION));

        if (!in_array($detectedExtension, $permittedTypes)) {
            $this->render("photos/create", ["failureNotice" => "Only image files are allowed."]);
            return;
        }

        $uniqueFileName = "photo_" . time() . "_" . uniqid() . "." . $detectedExtension;
        $savePath = __DIR__ . "/../public/images/uploads/" . $uniqueFileName;

        if (!move_uploaded_file($_FILES["photo_file"]["tmp_name"], $savePath)) {
            $this->render("photos/create", ["failureNotice" => "Failed to save the uploaded file."]);
            return;
        }

        $albumModel = new Photo();
        $albumModel->create([
            "user_id" => $_SESSION["user_id"],
            "file_name" => $uniqueFileName,
            "title" => $_POST["title"],
            "description" => $_POST["description"] ?? ""
        ]);

        $this->redirectTo("/alzikrayat/public/photos");
    }

    /**
     * Deletes a photo after verifying the current session user owns it,
     * then removes the physical file from disk.
     * @param int $requestedId
     */
    public function delete($requestedId)
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
            return;
        }

        $albumModel = new Photo();
        $ownedPhoto = $albumModel->findById($requestedId);

        if ($ownedPhoto && $ownedPhoto["user_id"] == $_SESSION["user_id"]) {
            $albumModel->deleteIfOwner($requestedId, $_SESSION["user_id"]);

            $storedFilePath = __DIR__ . "/../public/images/uploads/" . $ownedPhoto["file_name"];
            if (file_exists($storedFilePath)) {
                unlink($storedFilePath);
            }
        }

        $this->redirectTo("/alzikrayat/public/photos");
    }
}