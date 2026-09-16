
 <?php require __DIR__ . "/../layout/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Login</h3>

                <?php if (!empty($failureNotice)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($failureNotice) ?></div>
                <?php endif; ?>

                <?php if (!empty($lastVisitTimestamp)): ?>
                    <div class="alert alert-info">
                        Last login from this computer was <?= htmlspecialchars($lastVisitTimestamp) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/alzikrayat/public/login">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Don't have an account? <a href="/alzikrayat/public/register">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . "/../layout/footer.php"; ?>