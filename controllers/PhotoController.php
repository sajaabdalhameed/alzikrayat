<?php
/**
 * PhotoController
 * Handles viewing photos, uploading new photos, applies custom image filters,
 * and deleting photo records.
 */
class PhotoController extends Controller
{
    /**
     * Displays all photos in the gallery.
     */
    public function index()
    {
        $photoModel = new Photo();
        $photosList = $photoModel->getAll();
        $this->render("photos/index", ["photosList" => $photosList]);
    }

    /**
     * Renders the photo upload form page.
     */
    public function showUploadForm()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
        }
        $this->render("photos/create");
    }

    /**
     * Renders the photo detail view along with comments.
     */
    public function show($photoId)
    {
        $photoModel = new Photo();
        $matchedPhoto = $photoModel->findById($photoId);

        if (!$matchedPhoto) {
            $this->redirectTo("/alzikrayat/public/photos");
        }

        $commentModel = new Comment();
        $attachedComments = $commentModel->getByPhotoId($photoId);

        $filePath = __DIR__ . "/../public/images/uploads/" . $matchedPhoto["file_name"];
        $hasOriginalBackup = file_exists($this->getOriginalBackupPath($filePath));

        $this->render("photos/show", [
            "matchedPhoto" => $matchedPhoto,
            "attachedComments" => $attachedComments,
            "hasOriginalBackup" => $hasOriginalBackup
        ]);
    }

    /**
     * Processes photo upload and saves metadata.
     */
    public function store()
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
        }

        $photoTitle = trim($_POST["title"] ?? "");
        $photoDescription = trim($_POST["description"] ?? "");
        $uploadedFile = $_FILES["photo_file"] ?? null;

        if (empty($photoTitle) || !$uploadedFile || $uploadedFile["error"] !== UPLOAD_ERR_OK) {
            $this->render("photos/create", ["failureNotice" => "Please select a valid image file and provide a title."]);
            return;
        }

        $rawExtension = pathinfo($uploadedFile["name"], PATHINFO_EXTENSION);
        $extension = strtolower($rawExtension);
        $supportedFormats = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($extension, $supportedFormats)) {
            $this->render("photos/create", ["failureNotice" => "Only JPG, PNG and GIF images are allowed."]);
            return;
        }

        $uniqueFileName = "photo_" . time() . "_" . uniqid() . "." . $extension;
        $uploadsDirectory = __DIR__ . "/../public/images/uploads/";

        if (!is_dir($uploadsDirectory)) {
            mkdir($uploadsDirectory, 0777, true);
        }

        $destinationPath = $uploadsDirectory . $uniqueFileName;

        if (move_uploaded_file($uploadedFile["tmp_name"], $destinationPath)) {
            $photoModel = new Photo();
            $photoModel->create([
                "user_id" => $_SESSION["user_id"],
                "file_name" => $uniqueFileName,
                "title" => $photoTitle,
                "description" => $photoDescription
            ]);

            $this->redirectTo("/alzikrayat/public/photos");
        } else {
            $this->render("photos/create", ["failureNotice" => "Failed to save the image file."]);
        }
    }

    /**
     * Deletes a photo if it belongs to the active session user.
     */
    public function delete($photoId)
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
        }

        $photoModel = new Photo();
        $matchedPhoto = $photoModel->findById($photoId);

        if ($matchedPhoto && $matchedPhoto["user_id"] == $_SESSION["user_id"]) {
            $filePath = __DIR__ . "/../public/images/uploads/" . $matchedPhoto["file_name"];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Clean up the original backup too, if a filter was ever applied.
            $backupPath = $this->getOriginalBackupPath($filePath);
            if (file_exists($backupPath)) {
                unlink($backupPath);
            }

            $photoModel->deleteIfOwner($photoId, $_SESSION["user_id"]);
        }

        $this->redirectTo("/alzikrayat/public/photos");
    }

    /**
     * Applies grayscale or sepia filter to the photo. Before filtering for the
     * first time, a backup of the untouched original is saved so it can be
     * restored later via restoreOriginal().
     */
    public function applyFilter($photoId, $filterType)
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
        }

        $photoModel = new Photo();
        $matchedPhoto = $photoModel->findById($photoId);

        if (!$matchedPhoto || $matchedPhoto["user_id"] != $_SESSION["user_id"]) {
            $this->redirectTo("/alzikrayat/public/photos");
        }

        $filePath = __DIR__ . "/../public/images/uploads/" . $matchedPhoto["file_name"];
        $backupPath = $this->getOriginalBackupPath($filePath);

        // Save an untouched backup the first time a filter is ever applied.
        if (!file_exists($backupPath) && file_exists($filePath)) {
            copy($filePath, $backupPath);
        }

        $this->applyImageFilter($filePath, $filterType);

        $this->redirectTo("/alzikrayat/public/photo/" . $photoId);
    }

    /**
     * Restores the original, unfiltered image from its backup copy,
     * overwriting the current (filtered) file. Only the owner may restore.
     */
    public function restoreOriginal($photoId)
    {
        if (!isset($_SESSION["user_id"])) {
            $this->redirectTo("/alzikrayat/public/login");
        }

        $photoModel = new Photo();
        $matchedPhoto = $photoModel->findById($photoId);

        if (!$matchedPhoto || $matchedPhoto["user_id"] != $_SESSION["user_id"]) {
            $this->redirectTo("/alzikrayat/public/photos");
        }

        $filePath = __DIR__ . "/../public/images/uploads/" . $matchedPhoto["file_name"];
        $backupPath = $this->getOriginalBackupPath($filePath);

        if (file_exists($backupPath)) {
            copy($backupPath, $filePath);
        }

        $this->redirectTo("/alzikrayat/public/photo/" . $photoId);
    }

    /**
     * Builds the path used to store/read the untouched original backup for a
     * given uploaded file, e.g. "photo_123.jpg" -> "photo_123_original.jpg".
     * @param string $filePath Absolute path to the main (working) image file
     * @return string
     */
    private function getOriginalBackupPath($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $nameWithoutExtension = pathinfo($filePath, PATHINFO_FILENAME);
        $directory = pathinfo($filePath, PATHINFO_DIRNAME);
        return $directory . "/" . $nameWithoutExtension . "_original." . $extension;
    }

    /**
     * Custom GD image filter processor.
     */
    private function applyImageFilter($filePath, $filterType)
    {
        if (!extension_loaded("gd") || !file_exists($filePath)) {
            return;
        }

        // Convert file extension to lowercase to handle uppercase extensions (.JPG, .PNG)
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        switch ($extension) {
            case "jpg":
            case "jpeg":
                $imageResource = @imagecreatefromjpeg($filePath);
                break;
            case "png":
                $imageResource = @imagecreatefrompng($filePath);
                break;
            case "gif":
                $imageResource = @imagecreatefromgif($filePath);
                break;
            default:
                return;
        }

        if (!$imageResource) {
            return;
        }

        // Apply grayscale transformation
        imagefilter($imageResource, IMG_FILTER_GRAYSCALE);

        // Apply sepia color tint if selected
        if ($filterType === "sepia") {
            imagefilter($imageResource, IMG_FILTER_COLORIZE, 90, 60, 30);
        }

        // Save the filtered image back to storage
        switch ($extension) {
            case "jpg":
            case "jpeg":
                imagejpeg($imageResource, $filePath, 90);
                break;
            case "png":
                imagepng($imageResource, $filePath);
                break;
            case "gif":
                imagegif($imageResource, $filePath);
                break;
        }

        // Free system memory resources
        imagedestroy($imageResource);
    }
}