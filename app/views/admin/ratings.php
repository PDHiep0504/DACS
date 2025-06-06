<?php
// Bao gồm các file header, footer, kết nối DB nếu cần thiết.
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đánh giá</title>
    <!-- Liên kết tới Bootstrap và các icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #a3d9ff; /* Màu xanh nhạt */
            color: #000; /* Màu chữ đen */
        }

        .table td {
            background-color: #ffffff; /* Màu nền trắng cho các ô dữ liệu */
        }

        .table img {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .offcanvas-body {
            background-color: #f8f9fa; /* Màu nền sáng */
        }

        .list-group-item {
            color: #333; /* Màu chữ tối */
            background-color: #f8f9fa; /* Màu nền sáng */
        }

        .list-group-item-action:hover {
            background-color: #007bff; /* Màu nền khi hover */
            color: #fff; /* Màu chữ khi hover */
        }
    </style>
</head>

<body>

    <!-- Nút menu, đặt trên quản lý đánh giá -->
    <div class="container mt-4">
        <button class="btn btn-outline-primary mb-3" type="button" id="menuButton" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

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
                <<div class="collapse submenu" id="submenuAdmin">
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

    <!-- Quản lý Đánh giá -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Quản lý Đánh giá</h2>

        <!-- Form lọc -->
        <form method="GET" action="/webbanhang/admin/reviews" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="product_name" class="form-control" placeholder="Tên sản phẩm"
                        value="<?php echo isset($_GET['product_name']) ? $_GET['product_name'] : ''; ?>">
                </div>
                <div class="col-md-4">
                    <select name="rating_value" class="form-control">
                        <option value="">Chọn số sao</option>
                        <option value="1" <?php echo isset($_GET['rating_value']) && $_GET['rating_value'] == 1 ? 'selected' : ''; ?>>1 sao</option>
                        <option value="2" <?php echo isset($_GET['rating_value']) && $_GET['rating_value'] == 2 ? 'selected' : ''; ?>>2 sao</option>
                        <option value="3" <?php echo isset($_GET['rating_value']) && $_GET['rating_value'] == 3 ? 'selected' : ''; ?>>3 sao</option>
                        <option value="4" <?php echo isset($_GET['rating_value']) && $_GET['rating_value'] == 4 ? 'selected' : ''; ?>>4 sao</option>
                        <option value="5" <?php echo isset($_GET['rating_value']) && $_GET['rating_value'] == 5 ? 'selected' : ''; ?>>5 sao</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
            </div>
        </form>

        <!-- Bảng hiển thị đánh giá -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Size</th>
                    <th scope="col">Đánh giá</th>
                    <th scope="col">Bình luận</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col">Tên tài khoản</th> <!-- Thêm cột tên tài khoản -->
                    <th scope="col">Email</th> <!-- Thêm cột email -->
                    <th scope="col">Tùy chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating): ?>
                    <tr>
                        <th scope="row"><?php echo $rating->id; ?></th>
                        <td><?php echo $rating->product_name; ?></td>
                        <td><img src="/webbanhang/<?php echo IMAGE_PATH_Product . $rating->product_image; ?>"
                                alt="Product Image" width="70" height="70"></td>
                        <td><?php echo $rating->product_size; ?></td>
                        <td><?php echo $rating->rating_value; ?> sao</td>
                        <td><?php echo htmlspecialchars($rating->comment); ?></td>
                        <td><?php echo $rating->created_at; ?></td>
                        <td><?php echo $rating->account_name; ?></td> <!-- Hiển thị tên tài khoản -->
                        <td><?php echo $rating->account_email; ?></td> <!-- Hiển thị email -->
                        <td>
                            <a href="/webbanhang/admin/reviews/delete/<?php echo $rating->id; ?>"
                                class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS và dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
