<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
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

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .search-container form {
            display: flex;
            width: 60%;
        }

        .search-container input {
            flex: 1;
        }

        .add-product-btn {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .add-product-btn i {
            font-size: 18px;
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
        <h2 class="text-center mb-4">Quản Lý Sản Phẩm</h2>

        <!-- Cải tiến Form tìm kiếm và nút thêm sản phẩm -->
        <div class="search-container mb-3">
            <!-- Form tìm kiếm -->
            <form method="GET" action="/webbanhang/admin/products" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm"
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary ms-2">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
            </form>

            <!-- Nút thêm sản phẩm mới -->
            <a href="/webbanhang/admin/addProduct" class="btn btn-success add-product-btn">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
            </a>
        </div>

        <!-- Bảng sản phẩm -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Size</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)) { ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có sản phẩm nào</td>
                    </tr>
                <?php } else {
                    foreach ($products as $product) { ?>
                        <tr>
                            <td>
                                <img src="/webbanhang/<?php echo IMAGE_PATH_Product . $product->image; ?>" alt="Image"
                                    width="100">
                            </td>
                            <td><?php echo $product->name; ?></td>
                            <td><?php echo $product->category_name; ?></td>
                            <td><?php echo number_format($product->price, 0, ',', '.'); ?> VND</td>
                            <td>
                                <?php
                                $sizes = $this->productModel->getProductSizes($product->id);
                                if (empty($sizes)) {
                                    echo 'Chưa có size';
                                } else {
                                    foreach ($sizes as $size) {
                                        echo $size->name . '<br>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="/webbanhang/admin/editProduct/<?php echo $product->id; ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <a href="/webbanhang/admin/deleteProduct/<?php echo $product->id; ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
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
