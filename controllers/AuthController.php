<?php
/**
 * AuthController
 * Handles user registration, login, logout, and the "Last Login" cookie.
 */
class AuthController extends Controller
{
    /**
     * Displays the registration form.
     */
    public function showRegister()
    {
        $this->render("auth/register");
    }

    /**
     * Validates and processes a new user registration.
     */
    public function register()
    {
        $submittedFields = $_POST;

        if (
            empty($submittedFields["first_name"]) ||
            empty($submittedFields["last_name"]) ||
            empty($submittedFields["email"]) ||
            empty($submittedFields["password"])
        ) {
            $this->render("auth/register", ["failureNotice" => "All fields are required."]);
            return;
        }

        // Names must be letters only, max 50 characters (matches the Users table constraint).
        $namePattern = '/^[A-Za-z]{1,50}$/';
        if (
            !preg_match($namePattern, $submittedFields["first_name"]) ||
            !preg_match($namePattern, $submittedFields["last_name"])
        ) {
            $this->render("auth/register", ["failureNotice" => "Names must contain letters only (max 50 characters)."]);
            return;
        }

        if (!filter_var($submittedFields["email"], FILTER_VALIDATE_EMAIL)) {
            $this->render("auth/register", ["failureNotice" => "Invalid email format."]);
            return;
        }

        // Server-side password strength check (mirrors the client-side minlength=6).
        if (strlen($submittedFields["password"]) < 6) {
            $this->render("auth/register", ["failureNotice" => "Password must be at least 6 characters long."]);
            return;
        }

        $accountModel = new User();

        $duplicateAccount = $accountModel->findByEmail($submittedFields["email"]);
        if ($duplicateAccount) {
            $this->render("auth/register", ["failureNotice" => "Email already registered."]);
            return;
        }

        $accountModel->create($submittedFields);
        $this->redirectTo("/alzikrayat/public/login");
    }

    /**
     * Displays the login form, including the "Last Login" cookie message if present.
     */
    public function showLogin()
    {
        $lastVisitTimestamp = isset($_COOKIE["last_login"]) ? $_COOKIE["last_login"] : null;
        $this->render("auth/login", ["lastVisitTimestamp" => $lastVisitTimestamp]);
    }

    /**
     * Validates credentials, starts the session, and sets the "Last Login" cookie.
     */
    public function login()
    {
        $inputEmail = $_POST["email"] ?? "";
        $inputPassword = $_POST["password"] ?? "";

        $accountModel = new User();
        $foundAccount = $accountModel->findByEmail($inputEmail);

        if (!$foundAccount || !password_verify($inputPassword, $foundAccount["password"])) {
            $this->render("auth/login", ["failureNotice" => "Incorrect email or password."]);
            return;
        }

        $_SESSION["user_id"] = $foundAccount["id"];
        $_SESSION["first_name"] = $foundAccount["first_name"];

        setcookie("last_login", date("Y-m-d H:i:s"), time() + (7 * 24 * 60 * 60), "/");

        $this->redirectTo("/alzikrayat/public/photos");
    }

    /**
     * Destroys the current session and logs the user out.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirectTo("/alzikrayat/public/login");
    }
}