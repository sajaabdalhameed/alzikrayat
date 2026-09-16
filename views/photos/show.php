<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="row">
    <div class="col-md-7">
        <img src="/alzikrayat/public/images/uploads/<?= htmlspecialchars($matchedPhoto["file_name"]) ?>?v=<?= time() ?>"
             class="img-fluid rounded shadow-sm" alt="photo">
    </div>
    <div class="col-md-5">
        <h2><?= htmlspecialchars($matchedPhoto["title"]) ?></h2>
        <p class="text-muted">
            By <?= htmlspecialchars($matchedPhoto["first_name"] . " " . $matchedPhoto["last_name"]) ?>
            on <?= htmlspecialchars($matchedPhoto["date_time"]) ?>
        </p>
        <p><?= nl2br(htmlspecialchars($matchedPhoto["description"])) ?></p>

        <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $matchedPhoto["user_id"]): ?>
            <div class="mb-3">
                <a href="/alzikrayat/public/photo/<?= $matchedPhoto["id"] ?>/filter/grayscale"
                   class="btn btn-outline-secondary btn-sm"
                   onclick="return confirm('Apply Black &amp; White filter? This modifies the photo file.');">
                    Black &amp; White
                </a>
                <a href="/alzikrayat/public/photo/<?= $matchedPhoto["id"] ?>/filter/sepia"
                   class="btn btn-outline-secondary btn-sm"
                   onclick="return confirm('Apply Sepia filter? This modifies the photo file.');">
                    Sepia
                </a>
                <?php if ($hasOriginalBackup): ?>
                    <a href="/alzikrayat/public/photo/<?= $matchedPhoto["id"] ?>/restore"
                       class="btn btn-outline-success btn-sm"
                       onclick="return confirm('Restore the original, unfiltered photo?');">
                        Restore Original
                    </a>
                <?php endif; ?>
                <a href="/alzikrayat/public/photo/<?= $matchedPhoto["id"] ?>/delete"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this photo?');">
                    Delete Photo
                </a>
            </div>
        <?php endif; ?>

        <hr>
        <h5>Comments</h5>

        <div class="mb-3" id="commentsList" style="max-height: 250px; overflow-y: auto;">
            <?php if (empty($attachedComments)): ?>
                <p class="text-muted">No comments yet.</p>
            <?php else: ?>
                <?php foreach ($attachedComments as $commentRow): ?>
                    <div class="border-bottom py-2">
                        <strong><?= htmlspecialchars($commentRow["first_name"]) ?>:</strong>
                        <?= htmlspecialchars($commentRow["comment"]) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION["user_id"])): ?>
            <form method="POST" action="/alzikrayat/public/comment/store" id="commentForm">
                <input type="hidden" name="photo_id" value="<?= $matchedPhoto["id"] ?>">
                <div class="mb-2">
                    <textarea name="comment" class="form-control" rows="2" required placeholder="Add a comment..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Post Comment</button>
            </form>
        <?php else: ?>
            <p><a href="/alzikrayat/public/login">Login</a> to add a comment.</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . "/../layout/footer.php"; ?>