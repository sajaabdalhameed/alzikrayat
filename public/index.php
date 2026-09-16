<?php
session_start();
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../core/Model.php";
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../core/Router.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Photo.php";
require_once __DIR__ . "/../models/Comment.php";

$mainDispatcher = new Router();

// Home route
$mainDispatcher->add("GET", "/", ["HomeController", "index"]);

// Auth routes
$mainDispatcher->add("GET", "/register", ["AuthController", "showRegister"]);
$mainDispatcher->add("POST", "/register", ["AuthController", "register"]);
$mainDispatcher->add("GET", "/login", ["AuthController", "showLogin"]);
$mainDispatcher->add("POST", "/login", ["AuthController", "login"]);
$mainDispatcher->add("GET", "/logout", ["AuthController", "logout"]);

// Photo routes
$mainDispatcher->add("GET", "/photos", ["PhotoController", "index"]);
$mainDispatcher->add("GET", "/photo/upload", ["PhotoController", "showUploadForm"]);
$mainDispatcher->add("GET", "/photo/{id}", ["PhotoController", "show"]);
$mainDispatcher->add("POST", "/photo/store", ["PhotoController", "store"]);
$mainDispatcher->add("GET", "/photo/{id}/delete", ["PhotoController", "delete"]);
$mainDispatcher->add("GET", "/photo/{id}/filter/{type}", ["PhotoController", "applyFilter"]);
$mainDispatcher->add("GET", "/photo/{id}/restore", ["PhotoController", "restoreOriginal"]);

// Comment routes
$mainDispatcher->add("POST", "/comment/store", ["CommentController", "store"]);

$currentUrlPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$basePrefix = "/alzikrayat/public";

if (strpos($currentUrlPath, $basePrefix) === 0) {
    $currentUrlPath = substr($currentUrlPath, strlen($basePrefix));
}

if ($currentUrlPath === "") {
    $currentUrlPath = "/";
}

$mainDispatcher->dispatch($_SERVER["REQUEST_METHOD"], $currentUrlPath);