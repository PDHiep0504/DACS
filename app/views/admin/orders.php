<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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

        .order-card {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border: 1px solid #ddd;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .order-details {
            flex: 1;
        }

        .order-status-container {
            text-align: right;
            align-self: center;
        }

        .order-status {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .mb-3 {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
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
        <!-- Căn giữa tiêu đề -->
        <h2 class="mb-4 text-center">Quản lý đơn hàng</h2>

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div class="d-flex">
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

            <!-- Lọc theo ngày đặt -->
            <form method="GET" action="" class="d-flex">
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                    <span class="input-group-text">Đến</span>
                    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary ms-2">Lọc</button>
                </div>
            </form>
        </div>

        <?php
        // Filter orders by date if dates are provided
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';

        // Filter orders by date range
        if ($startDate && $endDate) {
            $orders = array_filter($orders, function($order) use ($startDate, $endDate) {
                $orderDate = strtotime($order->created_at);
                return ($orderDate >= strtotime($startDate) && $orderDate <= strtotime($endDate));
            });
        } elseif ($startDate) {
            $orders = array_filter($orders, function($order) use ($startDate) {
                $orderDate = strtotime($order->created_at);
                return $orderDate >= strtotime($startDate);
            });
        } elseif ($endDate) {
            $orders = array_filter($orders, function($order) use ($endDate) {
                $orderDate = strtotime($order->created_at);
                return $orderDate <= strtotime($endDate);
            });
        }
        ?>

        <?php foreach ($orders as $order): ?>
            <?php if ($currentStatus && $order->status !== $currentStatus)
                continue; ?>
            <div class="order-card">
                <div class="order-details">
                    <div><strong>Mã đơn:</strong> #<?= $order->id ?></div>
                    <div><strong>Người nhận:</strong> <?= htmlspecialchars($order->name ?? 'Không có') ?></div>
                    <div><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($order->created_at)) ?></div>
                    <div><strong>Địa chỉ nhận hàng:</strong> <?= htmlspecialchars($order->address ?? 'Không có') ?></div>
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
                        <a href="/webbanhang/admin/order_detail/<?= $order->id ?>" class="btn btn-sm btn-outline-dark">Xem chi
                            tiết</a>
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
    </div>

    <!-- Thêm JS của Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
