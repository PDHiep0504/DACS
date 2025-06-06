<?php
$currentPage = 'orders'; // Để active sidebar mục "Quản lý đơn hàng"
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        .account-page {
            max-width: 1200px;
            margin: 40px auto;
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

        .order-card {
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .order-details {
            width: 70%;
        }

        .order-status-container {
            width: 25%;
            border-left: 2px solid #eee;
            padding-left: 20px;
        }

        .order-status {
            font-weight: bold;
        }

        .order-status a {
            margin-top: 10px;
        }

        .order-status button {
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .order-card {
                flex-direction: column;
            }

            .order-details,
            .order-status-container {
                width: 100%;
            }

            .order-status-container {
                border-left: none;
                padding-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container account-page">
        <!-- Nút menu và menu bên trái đồng nhất -->
        <button class="btn btn-outline-primary mb-3" type="button" id="menuButton" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
            <i class="bi bi-list"></i> Menu
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="list-group">
                    <a class="list-group-item list-group-item-action active bg-primary text-white" data-bs-toggle="collapse" href="#submenuAdmin" role="button" aria-expanded="false" aria-controls="submenuAdmin">
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
    </div>

        <div class="row">
            <div class="col-12">
                <h4 class="mb-4">Danh sách đơn hàng</h4>

                <!-- Thông tin tài khoản người dùng -->
                <?php if ($account): ?>
                    <div class="mb-4">
                        <h5>Thông tin tài khoản</h5>
                        <ul>
                            <li><strong>Tên người dùng:</strong>
                                <?= htmlspecialchars($account->username ?? 'Chưa có thông tin') ?></li>
                            <li><strong>Email:</strong> <?= htmlspecialchars($account->email ?? 'Chưa có thông tin') ?></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <p>Thông tin tài khoản không tồn tại.</p>
                <?php endif; ?>
                <!-- Filter Buttons -->
                <div class="mb-3">
                    <?php
                    $statuses = [
                        '' => 'Tất cả',
                        'pending' => 'Chờ xác nhận',
                        'confirmed' => 'Đã xác nhận',
                        'shipping' => 'Đang giao hàng',
                        'delivered' => 'Đã giao hàng',
                        'received' => 'Đã nhận',
                        'cancelled' => 'Đã hủy'
                    ];
                    $currentStatus = $_GET['status'] ?? '';
                    ?>
                    <?php foreach ($statuses as $key => $label): ?>
                        <a href="?status=<?= $key ?>"
                            class="btn btn-sm <?= $currentStatus === $key ? 'btn-warning' : 'btn-outline-secondary' ?> me-1 mb-1">
                            <?= $label ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Hiển thị đơn hàng -->
                <?php if (!empty($accountOrders)): ?>
                    <?php foreach ($accountOrders as $order): ?>
                        <?php if ($currentStatus && $order->status !== $currentStatus)
                            continue; ?>
                        <div class="order-card">
                            <div class="order-details">
                                <div><strong>Mã đơn:</strong> #<?= $order->id ?></div>
                                <div><strong>Người nhận:</strong> <?= htmlspecialchars($order->name ?? 'Không có') ?></div>
                                <div><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($order->created_at)) ?></div>
                                <div><strong>Địa chỉ nhận hàng:</strong> <?= htmlspecialchars($order->address ?? 'Không có') ?>
                                </div>
                                <div><strong>Tổng tiền:</strong> <?= number_format($order->total_amount, 0, ',', '.') ?>₫</div>
                            </div>

                            <div class="order-status-container">
                                <?php
                                $statusInfo = [
                                    'pending' => ['color' => 'text-warning', 'icon' => '🕑', 'label' => 'Chờ xử lý'],
                                    'confirmed' => ['color' => 'text-info', 'icon' => '✔️', 'label' => 'Đã duyệt đơn hàng'],
                                    'shipping' => ['color' => 'text-primary', 'icon' => '🚚', 'label' => 'Đang giao hàng'],
                                    'delivered' => ['color' => 'text-success', 'icon' => '📦', 'label' => 'Đã giao hàng'],
                                    'cancelled' => ['color' => 'text-danger', 'icon' => '❌', 'label' => 'Đã hủy'],
                                    'received' => ['color' => 'text-success', 'icon' => '✅', 'label' => 'Đã nhận hàng'],
                                ];
                                $status = $statusInfo[$order->status] ?? ['color' => 'text-dark', 'icon' => '⚪', 'label' => 'Không xác định'];
                                ?>
                                <div class="order-status <?= $status['color'] ?>">
                                    <span class="me-2"><?= $status['icon'] ?></span><?= $status['label'] ?>
                                </div>

                                <div class="mt-2">
                                    <a href="/webbanhang/order/detail/<?= $order->id ?>" class="btn btn-sm btn-outline-dark">Xem
                                        chi tiết</a>
                                </div>

                                <?php if ($order->status === 'pending'): ?>

                                    <div class="mt-2">
                                        <!-- Form Xác nhận đơn hàng -->
                                        <form method="post" action="/webbanhang/admin/confirm" style="display:inline-block;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?');">
                                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Xác nhận đơn</button>
                                        </form>
                                    </div>


                                <?php endif; ?>

                                <?php if ($order->status === 'confirmed'): ?>
                                    <div class="mt-2">
                                        <form method="post" action="/webbanhang/admin/shipping" style="display:inline-block;"
                                            onsubmit="return confirm('Bắt đầu giao đơn hàng này?');">
                                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Bắt đầu giao hàng</button>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <?php if ($order->status === 'shipping'): ?>
                                    <div class="mt-2">
                                        <form method="post" action="/webbanhang/admin/delivered" style="display:inline-block;"
                                            onsubmit="return confirm('Xác nhận đơn hàng đã giao thành công?');">
                                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Đã giao hàng</button>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <?php if ($order->status !== 'received' && $order->status !== 'cancelled' && $order->status !== 'delivered'): ?>
                                    <div class="mt-2">
                                        <!-- Form Hủy đơn -->
                                        <form method="post" action="/webbanhang/admin/cancel" style="display:inline-block;"
                                            onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
                                            <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tài khoản này chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>