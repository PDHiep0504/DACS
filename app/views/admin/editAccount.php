<?php

// Nếu form được gửi đi, thực hiện cập nhật tài khoản
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Cập nhật tài khoản
    $updateSuccess = $this->accountModel->updateAccount($id, $email, $username, $role);

    if ($updateSuccess) {
        // Chuyển hướng về trang danh sách tài khoản sau khi cập nhật thành công
        header('Location: /webbanhang/admin/accounts');
        exit;
    } else {
        $errorMessage = "Có lỗi xảy ra khi cập nhật tài khoản.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tài Khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Sửa Tài Khoản</h2>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($account->email) ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Name</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($account->username) ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select class="form-control" id="role" name="role" required>
                <option value="user" <?= $account->role == 'user' ? 'selected' : '' ?>>Người dùng</option>
                <option value="admin" <?= $account->role == 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
        <a href="/webbanhang/admin/accounts" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
