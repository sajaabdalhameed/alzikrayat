<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="p-5 mb-4 bg-dark text-white rounded-3 text-center">
    <div class="container-fluid py-4">
        <h1 class="display-5 fw-bold">Welcome to Alzikrayat</h1>
        <p class="col-md-8 mx-auto fs-5">
            "Alzikrayat" (ذكريات) means memories. This is a simple place to upload your photos,
            share the story behind each one, and read what other members have to say about them.
        </p>
        <a href="/alzikrayat/public/photos" class="btn btn-primary btn-lg me-2">Browse the Gallery</a>
        <?php if (!isset($_SESSION["user_id"])): ?>
            <a href="/alzikrayat/public/register" class="btn btn-outline-light btn-lg">Join Now</a>
        <?php else: ?>
            <a href="/alzikrayat/public/photo/upload" class="btn btn-outline-light btn-lg">Upload a Photo</a>
        <?php endif; ?>
    </div>
</div>

<div class="row text-center mb-5">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="display-6 fw-bold text-primary"><?= (int) $siteStats["userCount"] ?></h2>
                <p class="text-muted mb-0">Registered Members</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h2 class="display-6 fw-bold text-primary"><?= (int) $siteStats["photoCount"] ?></h2>
                <p class="text-muted mb-0">Photos Shared</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($recentPhotos)): ?>
    <h3 class="mb-3">Recently Shared</h3>
    <div class="row mb-5">
        <?php foreach ($recentPhotos as $photoRow): ?>
            <div class="col-6 col-md-4 col-lg-2 mb-4">
                <a href="/alzikrayat/public/photo/<?= $photoRow["id"] ?>" class="text-decoration-none">
                    <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($photoRow["file_name"]) ?>"
                         class="img-fluid rounded shadow-sm" style="height: 140px; width: 100%; object-fit: cover;"
                         alt="<?= htmlspecialchars($photoRow["title"]) ?>">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section id="about-us" class="mb-5">
    <h3 class="mb-3">About Us</h3>
    <p>
        Alzikrayat is a course project built for the Advanced Web Technologies course at
        Sudan University of Science and Technology (SUST), College of Computer Science and
        Information Technology. It was built from scratch using a hand-written
        Model-View-Controller (MVC) architecture and a manual, Regular-Expression-based router,
        with no external web frameworks or ORMs, to demonstrate a solid understanding of how
        dynamic web applications work under the hood.
    </p>
    <p class="mb-0">
        Members can register for a free account, upload photos with a title and description,
        browse everyone's shared memories in the gallery, and leave comments on any photo.
    </p>
</section>

<?php require __DIR__ . "/../layout/footer.php"; ?>
