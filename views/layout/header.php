<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alzikrayat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="/alzikrayat/public/js/main.js" defer></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/alzikrayat/public/">Alzikrayat</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/alzikrayat/public/photos">Gallery</a>
                </li>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/alzikrayat/public/photo/upload">Add Photo</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Hi <?= htmlspecialchars($_SESSION["first_name"]) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/alzikrayat/public/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Please Login</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/alzikrayat/public/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">