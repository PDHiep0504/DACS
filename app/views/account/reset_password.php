<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #667eea, #764ba2);">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center fw-bold mb-3">Reset Password</h2>
                        <p class="text-center text-muted">Please enter your new password</p>

                        <form action="/webbanhang/account/resetPassword" method="POST">
                            <!-- Token ẩn -->
                            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                            <!-- Mật khẩu mới -->
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Enter new password" required>
                            </div>

                            <!-- Nhập lại mật khẩu -->
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Confirm new password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Update Password</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="/webbanhang/account/login" class="text-decoration-none">Back to Login</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>
