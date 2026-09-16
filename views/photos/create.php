<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4">Add New Photo</h3>

                <?php if (!empty($failureNotice)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($failureNotice) ?></div>
                <?php endif; ?>

                <form method="POST" action="/alzikrayat/public/photo/store" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required maxlength="200">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo File</label>
                        <input type="file" name="photo_file" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . "/../layout/footer.php"; ?>