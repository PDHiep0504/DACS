<?php include 'app/views/shares/header.php'; ?>
<?php
if (isset($_SESSION['email_not_found'])) {
    echo '<div class="alert alert-success" id="successMessage">';
    echo $_SESSION['email_not_found'];
    echo '</div>';
    unset($_SESSION['email_not_found']); // Xóa thông báo sau khi đã hiển thị
}
?>
<?php
if (isset($_SESSION['token_Time'])) {
    echo '<div class="alert alert-success" id="successMessage">';
    echo $_SESSION['token_Time'];
    echo '</div>';
    unset($_SESSION['token_Time']); // Xóa thông báo sau khi đã hiển thị
}
?>
<?php
if (isset($_SESSION['diffirent_password'])) {
    echo '<div class="alert alert-success" id="successMessage">';
    echo $_SESSION['diffirent_password'];
    echo '</div>';
    unset($_SESSION['diffirent_password']); // Xóa thông báo sau khi đã hiển thị
}
?>
<section class="vh-100 d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #667eea, #764ba2);">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center fw-bold mb-3">Forgot Password</h2>
                        <p class="text-center text-muted">Enter your email to reset your password</p>

                        <form action="/webbanhang/account/sendResetEmail" method="POST">
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="Enter your email" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Send Reset Link</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="/webbanhang/account/login" class="text-decoration-none">Back to Login</a>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">Don't have an account?
                                <a href="/webbanhang/account/register" class="text-primary fw-bold">Sign Up</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // Kiểm tra xem thông báo có tồn tại không
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        // Đặt thời gian ẩn thông báo (5 giây)
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 5000); // 5000ms = 5 giây
    }
</script>
<style>
    /* CSS cho thông báo */
    #successMessage {
        position: fixed; /* Đặt vị trí cố định */
        top: 20px; /* Cách từ trên xuống */
        left: 50%; /* Căn giữa theo chiều ngang */
        transform: translateX(-50%); /* Đảm bảo căn giữa chính xác */
        z-index: 9999; /* Đảm bảo thông báo hiển thị trên các phần tử khác */
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: block; /* Hiển thị khi có thông báo */
        opacity: 1;
        transition: opacity 0.5s ease-in-out; /* Hiệu ứng mờ dần khi ẩn thông báo */
    }

    /* Khi thông báo biến mất */
    #successMessage.hidden {
        opacity: 0;
        pointer-events: none; /* Không cho phép tương tác với thông báo khi nó đã ẩn */
    }
</style>
<?php include 'app/views/shares/footer.php'; ?>
