<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #667eea, #764ba2);">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center fw-bold mb-3 text-primary">Register</h2>
                        <p class="text-center text-muted">Create your account</p>

                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (isset($errors) && !empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Hiển thị thông báo thành công nếu có -->
                        <?php if (!empty($successMessage)): ?>
                            <div class="alert alert-success">
                                <?= htmlspecialchars($successMessage) ?>
                            </div>
                        <?php endif; ?>

                        <form action="/webbanhang/account/save" method="post">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" required>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="Enter your username" required>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter your password" required>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirmpassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirmpassword"
                                        name="confirmpassword" placeholder="Confirm your password" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Already have an account?
                                <a href="/webbanhang/account/login" class="text-primary fw-bold">Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>
