<?php
$currentPage = 'account'; // Gán tên trang hiện tại để active sidebar
include 'app/views/shares/header.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        .account-page {
            max-width: 1200px;
            margin: 40px auto;
        }

        .sidebar {
            border-right: 1px solid #eee;
            padding-right: 30px;
        }

        .sidebar h5 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sidebar a {
            display: block;
            padding: 10px 0 10px 15px;
            color: #333;
            text-decoration: none;
            font-size: 15px;
            position: relative;
            transition: color 0.2s;
        }

        .sidebar a.active {
            color: #d6a200;
            font-weight: bold;
        }

        .sidebar a.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 10px;
            width: 5px;
            height: 20px;
            background-color: #d6a200;
            border-radius: 2px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control:disabled {
            background-color: #f8f9fa;
        }

        .update-btn {
            background-color: black;
            color: white;
            border: none;
            padding: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .change-password {
            color: #d6a200;
            font-size: 14px;
            cursor: pointer;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .required {
            color: red;
        }

        .breadcrumb {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        .breadcrumb a {
            color: #000;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            margin-right: 5px;
        }

        .password-fields {
            display: none;
            margin-top: 15px;
        }

        .password-fields input {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                border: none;
                padding-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container account-page">
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumb">
                    <a href="/webbanhang/product">Trang chủ</a>&nbsp;|&nbsp;<span class="fw-bold">Tài khoản</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-3">TÀI KHOẢN</h5>
                <p>Xin chào, <strong><?= htmlspecialchars($account->username) ?></strong></p>
                <a href="/webbanhang/account/profile" class="<?= $currentPage == 'account' ? 'active' : '' ?>">Thông tin tài khoản</a>
                <!-- <a href="/webbanhang/voucher" class="<?= $currentPage == 'voucher' ? 'active' : '' ?>">Mã giảm giá của tôi</a> -->
                <a href="/webbanhang/account/address" class="<?= $currentPage == 'address' ? 'active' : '' ?>">Địa chỉ</a>
                <a href="/webbanhang/order/" class="<?= $currentPage == 'orders' ? 'active' : '' ?>">Quản lý đơn hàng</a>
                <!-- <a href="/webbanhang/wishlist" class="<?= $currentPage == 'wishlist' ? 'active' : '' ?>">Danh sách yêu thích</a> -->
                <a href="/webbanhang/review/reviews" class="<?= $currentPage == 'member' ? 'active' : '' ?>">Đánh giá sản phẩm</a>
                <!-- <a href="/webbanhang/points" class="<?= $currentPage == 'points' ? 'active' : '' ?>">Lịch sử tích điểm</a> -->
            </div>

            <!-- Form content -->
            <div class="col-md-9">
                <form>
                    <div class="form-section">
                        <label class="form-label">Tên</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($account->username) ?>">
                    </div>

                    <div class="form-section">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($account->email) ?>" disabled>
                    </div>

                    <div class="form-section">
                        <label class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                        <div class="d-flex align-items-center">
                            <input type="password" class="form-control" placeholder="********">
                            <!-- <span class="ms-2 change-password" onclick="togglePasswordFields()">Đổi mật khẩu</span> -->
                        </div>
                    </div>

                    <!-- Các trường nhập mật khẩu mới -->
                    <div class="password-fields">
                        <div class="form-section">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" placeholder="Mật khẩu mới">
                        </div>
                        <div class="form-section">
                            <label class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" placeholder="Xác nhận mật khẩu mới">
                        </div>
                    </div>

                    <!-- <button type="submit" class="w-100 mt-4 update-btn">CẬP NHẬT</button> -->
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordFields() {
            var passwordFields = document.querySelector('.password-fields');
            passwordFields.style.display = (passwordFields.style.display === 'none' || passwordFields.style.display === '') ? 'block' : 'none';
        }
    </script>
</body>

</html>
<?php include 'app/views/shares/footer.php'; ?>
