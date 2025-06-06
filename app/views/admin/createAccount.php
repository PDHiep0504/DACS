<?php
// Nếu form được gửi đi, thực hiện việc thêm tài khoản mới
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Lưu tài khoản mới vào cơ sở dữ liệu
    $addSuccess = $this->accountModel->save($email, $username, $password, $role);

    if ($addSuccess) {
        // Chuyển hướng về trang danh sách tài khoản sau khi thêm thành công
        header('Location: /webbanhang/admin/accounts');
        exit;
    } else {
        $errorMessage = "Email đã tồn tại hoặc có lỗi xảy ra khi thêm tài khoản.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Tài Khoản Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Thêm Tài Khoản Mới</h2>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Name</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select class="form-control" id="role" name="role" required>
                <option value="user">Người dùng</option>
                <option value="admin">Quản trị viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
        <a href="/webbanhang/admin/accounts" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
