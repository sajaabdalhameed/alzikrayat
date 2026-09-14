<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Photo Gallery</h2>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-secondary" onclick="toggleView('grid')">Grid View</button>
        <button type="button" class="btn btn-outline-secondary" onclick="toggleView('list')">List View</button>
    </div>
</div>

<div class="row" id="galleryContainer">
    <?php if (empty($fullCollection)): ?>
        <p class="text-muted">No photos uploaded yet.</p>
    <?php else: ?>
        <?php foreach ($fullCollection as $photoRow): ?>
            <div class="gallery-item col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="row g-0 h-100 align-items-center">
                        <div class="card-img-wrapper col-12">
                            <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($photoRow["file_name"]) ?>"
                                 class="card-img-top w-100" style="height: 220px; object-fit: cover;" alt="photo">
                        </div>
                        <div class="card-body-wrapper col-12">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($photoRow["title"]) ?></h5>
                                <p class="card-text text-muted mb-2">
                                    By <?= htmlspecialchars($photoRow["first_name"] . " " . $photoRow["last_name"]) ?>
                                </p>
                                <a href="/alzikrayat/public/photo/<?= $photoRow["id"] ?>" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function toggleView(style) {
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        const imgWrapper = item.querySelector('.card-img-wrapper');
        const bodyWrapper = item.querySelector('.card-body-wrapper');
        const img = item.querySelector('img');

        if (style === 'list') {
            item.className = 'gallery-item col-12 mb-3';
            imgWrapper.className = 'card-img-wrapper col-md-3';
            bodyWrapper.className = 'card-body-wrapper col-md-9';
            img.style.height = '140px';
        } else {
            item.className = 'gallery-item col-md-4 mb-4';
            imgWrapper.className = 'card-img-wrapper col-12';
            bodyWrapper.className = 'card-body-wrapper col-12';
            img.style.height = '220px';
        }
    });
}
</script>

<?php require __DIR__ . "/../layout/footer.php"; ?>