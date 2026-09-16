<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Photo Gallery</h2>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-secondary" onclick="toggleView('grid')">Grid View</button>
        <button type="button" class="btn btn-outline-secondary" onclick="toggleView('list')">List View</button>
    </div>
</div>

<div class="row" id="galleryContainer">
    <?php if (empty($photosList)): ?>
        <p class="text-muted">No photos uploaded yet.</p>
    <?php else: ?>
        <?php foreach ($photosList as $photoRow): ?>
            <div class="gallery-item col-md-4 mb-4">
                <div class="card h-100 gallery-card">
                    <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($photoRow["file_name"]) ?>"
                         class="gallery-thumb" alt="photo">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($photoRow["title"]) ?></h5>
                        <p class="card-text text-muted mb-1">
                            By <?= htmlspecialchars($photoRow["first_name"] . " " . $photoRow["last_name"]) ?>
                        </p>
                        <a href="/alzikrayat/public/photo/<?= $photoRow["id"] ?>" class="btn btn-outline-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* Grid mode (default): a normal card thumbnail on top of the text. */
    .gallery-thumb {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    /* List mode: a small fixed-size thumbnail sitting beside the text. */
    #galleryContainer.list-mode .gallery-item {
        width: 100%;
    }

    #galleryContainer.list-mode .gallery-card {
        flex-direction: row;
        align-items: stretch;
    }

    #galleryContainer.list-mode .gallery-thumb {
        width: 160px;
        min-width: 160px;
        height: 120px;
    }
</style>

<script>
function toggleView(style) {
    const container = document.getElementById('galleryContainer');
    const items = document.querySelectorAll('.gallery-item');

    if (style === 'list') {
        container.classList.add('list-mode');
        items.forEach(item => { item.className = 'gallery-item col-12 mb-3'; });
    } else {
        container.classList.remove('list-mode');
        items.forEach(item => { item.className = 'gallery-item col-md-4 mb-4'; });
    }
}
</script>

<?php require __DIR__ . "/../layout/footer.php"; ?>
