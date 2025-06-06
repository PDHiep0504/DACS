<?php include 'app/views/shares/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
        }

        .card {
            border-radius: 15px;
        }

        .list-group-item {
            border: none;
            font-size: 14px;
        }

        .list-group-item i {
            margin-right: 8px;
        }

        .list-group-item-action.active {
            background-color: #ff2c2c;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i class="bi bi-house-door"></i> Trang chủ
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-clock-history"></i> Lịch sử mua hàng
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-person"></i> Tài khoản của bạn
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-telephone"></i> Hỗ trợ
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-chat-dots"></i> Góp ý - Phản hồi
                    </a>
                    <a href="#" class="list-group-item list-group-item-action text-danger">
                        <i class="bi bi-box-arrow-right"></i> Thoát tài khoản
                    </a>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9">
                <!-- Thông tin người dùng -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Avatar -->
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-white border border-2 border-secondary d-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 100px;">
                                    <img src="https://via.placeholder.com/70" class="rounded-circle" alt="avatar">
                                </div>
                            </div>


                            <!-- Tên và sđt -->
                            <div class="col-md-7">
                                <h5 class="mb-1">HIỆP</h5>
                                <p class="text-muted mb-0">09******42</p>
                            </div>

                            <!-- Đơn hàng -->
                            <div class="col-md-3 text-center">
                                <h6 class="mb-0">0</h6>
                                <p class="text-muted mb-0">Đơn hàng</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Các nút chức năng -->
                <div class="card mb-4">
                    <div class="card-body d-flex justify-content-around text-center">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-percent fs-3"></i>
                            <p class="mt-1 mb-0">Mã giảm giá</p>
                        </a>
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-clock-history fs-3"></i>
                            <p class="mt-1 mb-0">Lịch sử mua</p>
                        </a>
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-geo-alt fs-3"></i>
                            <p class="mt-1 mb-0">Sổ địa chỉ</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php include 'app/views/shares/footer.php'; ?>