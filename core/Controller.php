<?php
/**
 * Controller
 * Abstract base class for all Controller classes (AuthController, PhotoController, CommentController).
 * Provides shared helper methods for rendering views and redirecting.
 */
abstract class Controller
{
    /**
     * Loads a view file and passes data to it as variables.
     * @param string $templateName Relative path inside the views/ folder, e.g. "auth/login"
     * @param array $templateVars Data to extract into variables available inside the view
     */
    protected function render($templateName, $templateVars = [])
    {
        extract($templateVars);
        $resolvedPath = __DIR__ . "/../views/" . $templateName . ".php";
        require $resolvedPath;
    }

    /**
     * Redirects the browser to another URL and stops script execution.
     * @param string $destination
     */
    protected function redirectTo($destination)
    {
        header("Location: " . $destination);
        exit;
    }
}