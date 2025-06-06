<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        #menuButton {
            position: absolute;
            top: 45px;
            left: 60px;
            z-index: 1020;
        }

        .offcanvas {
            z-index: 1045;
        }

        body.offcanvas-backdrop::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1040;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
        }

        .btn {
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #f39c12;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .d-flex .btn {
            margin-left: 10px;
        }

        .search-group {
            display: flex;
            align-items: center;
        }

        .search-group .form-select {
            width: 200px;
        }

        .search-group .input-group {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <!-- Nút menu -->
    <button class="btn btn-outline-primary" type="button" id="menuButton" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
        <i class="bi bi-list"></i> Menu
    </button>

    <!-- Menu bên trái -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="list-group">
                <a class="list-group-item list-group-item-action active bg-primary text-white" data-bs-toggle="collapse"
                    href="#submenuAdmin" role="button" aria-expanded="false" aria-controls="submenuAdmin">
                    Trang quản lý <i class="bi bi-caret-down-fill float-end"></i>
                </a>
                <div class="collapse submenu" id="submenuAdmin">
                    <a href="/webbanhang/admin/" class="list-group-item list-group-item-action">Bảng Điều Khiển Quản Trị</a>
                    <a href="/webbanhang/admin/products" class="list-group-item list-group-item-action">QL Sản phẩm</a>
                    <a href="/webbanhang/admin/categories" class="list-group-item list-group-item-action">QL Danh mục</a>
                    <a href="/webbanhang/admin/sizes" class="list-group-item list-group-item-action">QL Sizes</a>
                    <a href="/webbanhang/admin/orders" class="list-group-item list-group-item-action">QL Đơn hàng</a>
                    <a href="/webbanhang/admin/accounts" class="list-group-item list-group-item-action">QL Người dùng</a>
                    <a href="/webbanhang/admin/reviews" class="list-group-item list-group-item-action">QL Đánh giá</a>
                </div>
                <a href="/webbanhang" class="list-group-item list-group-item-action bg-primary text-white">Trang chủ</a>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary">Danh Sách Tài Khoản</h2>

        <div class="d-flex justify-content-between mb-3">
            <!-- Vị trí lọc tài khoản -->
            <div class="search-group">
                <!-- Lọc theo vai trò -->
                <select name="role_filter" class="form-select" onchange="window.location.href = this.value">
                    <option value="/webbanhang/admin/accounts" <?= !isset($_GET['role']) ? 'selected' : '' ?>>Tất cả
                    </option>
                    <option value="/webbanhang/admin/accounts?role=user" <?= isset($_GET['role']) && $_GET['role'] == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="/webbanhang/admin/accounts?role=admin" <?= isset($_GET['role']) && $_GET['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <!-- Tìm kiếm -->
                <form method="GET" action="/webbanhang/admin/accounts" class="input-group ms-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                        value="<?= htmlspecialchars($emailFilter) ?>">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên"
                        value="<?= htmlspecialchars($nameFilter) ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>

                    <!-- Nút Đặt lại -->
                    <a href="/webbanhang/admin/accounts" class="btn btn-secondary ms-2">Đặt lại</a>
                </form>
            </div>
            <div>
                <a href="/webbanhang/admin/createAccount" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Thêm tài khoản mới
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Đơn hàng</th> <!-- Cột mới -->
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)) { ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có tài khoản nào</td>
                    </tr>
                <?php } else {
                    foreach ($accounts as $account) { ?>
                        <tr>
                            <td><?= htmlspecialchars($account['id']) ?></td>
                            <td><?= htmlspecialchars($account['email']) ?></td>
                            <td><?= htmlspecialchars($account['username']) ?></td>
                            <td><?= htmlspecialchars($account['role']) ?></td>
                            <td>
                                <a href="/webbanhang/admin/accountOrders/<?= $account['id'] ?>" class="btn btn-primary btn-sm">
                                    Xem các đơn hàng
                                </a>
                            </td>
                            <td>
                                <a href="/webbanhang/admin/editAccount/<?= $account['id'] ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <a href="/webbanhang/admin/deleteAccount/<?= $account['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>