<?php require __DIR__ . "/../layout/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Register</h3>

                <?php if (!empty($failureNotice)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($failureNotice) ?></div>
                <?php endif; ?>

                <form method="POST" action="/alzikrayat/public/register" novalidate>
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required pattern="[A-Za-z]+">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required pattern="[A-Za-z]+">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Already have an account? <a href="/alzikrayat/public/login">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . "/../layout/footer.php"; ?>