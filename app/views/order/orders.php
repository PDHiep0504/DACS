<?php
$currentPage = 'orders'; // để active sidebar mục "Quản lý đơn hàng"
include 'app/views/shares/header.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
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
            .sidebar {
                border: none;
                padding-right: 0;
            }

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
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumb">
                    <a href="/webbanhang/product">Trang chủ</a>&nbsp;|&nbsp;<span class="fw-bold">Đơn hàng</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-3">TÀI KHOẢN</h5>
                <p>Xin chào, <strong><?= htmlspecialchars($account->username) ?></strong></p>
                <a href="/webbanhang/account/profile/" class="<?= $currentPage == 'account' ? 'active' : '' ?>">Thông
                    tin tài
                    khoản</a>
                <!-- <a href="/webbanhang/voucher" class="<?= $currentPage == 'voucher' ? 'active' : '' ?>">Mã giảm giá của
                    tôi</a> -->
                <a href="/webbanhang/account/address" class="<?= $currentPage == 'address' ? 'active' : '' ?>">Địa
                    chỉ</a>
                <a href="/webbanhang/order" class="<?= $currentPage == 'orders' ? 'active' : '' ?>">Quản lý đơn
                    hàng</a>
                <!-- <a href="/webbanhang/wishlist" class="<?= $currentPage == 'wishlist' ? 'active' : '' ?>">Danh sách yêu
                    thích</a> -->
                <a href="/webbanhang/review/reviews" class="<?= $currentPage == 'member' ? 'active' : '' ?>">Đánh giá
                    sản phẩm</a>
                <!-- <a href="/webbanhang/points" class="<?= $currentPage == 'points' ? 'active' : '' ?>">Lịch sử tích
                    điểm</a> -->
            </div>

            <!-- Order content -->
            <div class="col-md-9">
                <h4 class="mb-4">Danh sách đơn hàng</h4>
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

                <?php foreach ($orders as $order): ?>
                    <?php if ($currentStatus && $order->status !== $currentStatus)
                        continue; // Lọc theo trạng thái ?>
                    <div class="order-card">
                        <div class="order-details">
                            <div><strong>Mã đơn:</strong> #<?= $order->id ?></div>
                            <div><strong>Người nhận:</strong> <?= htmlspecialchars($order->name ?? 'Không có') ?></div>
                            <div><strong>Ngày đặt:</strong> <?= date('d/m/Y', strtotime($order->created_at)) ?></div>
                            <div><strong>Địa chỉ nhận hàng:</strong>
                                <?= htmlspecialchars($order->address ?? 'Không có') ?></div>
                            <div><strong>Tổng tiền:</strong> <?= number_format($order->total_amount, 0, ',', '.') ?>₫
                            </div>
                            <div><strong>Phương thức thanh toán:</strong>
                                <?php
                                if ($order->payment_method == 'cod') {
                                    echo 'Thanh toán khi nhận hàng';
                                } elseif ($order->payment_method == 'bank') {
                                    echo 'Thanh toán qua thẻ ngân hàng';
                                } else {
                                    echo 'Chưa có thông tin phương thức thanh toán';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="order-status-container">
                            <?php
                            // Gán màu và icon tương ứng với từng trạng thái
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

                            <!-- Hủy đơn chỉ hiển thị khi trạng thái là "pending" -->
                            <?php if ($order->status === 'pending'): ?>
                                <div class="mt-2">
                                    <form method="post" action="/webbanhang/order/cancel" style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
                                        <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <!-- Thêm nút "Đã nhận" chỉ hiển thị khi trạng thái là "Đã giao hàng" -->
                            <?php if ($order->status === 'delivered'): ?>
                                <div class="mt-2">
                                    <form method="post" action="/webbanhang/order/confirm_received"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc chắn đã nhận hàng không?');">
                                        <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Đã nhận</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($orders)): ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php include 'app/views/shares/footer.php'; ?>