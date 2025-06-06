<?php
$currentPage = 'orders'; // Để sidebar biết đang ở mục đơn hàng
include 'app/views/shares/header.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container account-page my-5">
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumb">
                    <a href="/webbanhang">Trang chủ</a>&nbsp;|&nbsp;
                    <a href="/webbanhang/order">Đơn hàng</a>&nbsp;|&nbsp;
                    <span class="fw-bold">Chi tiết đơn hàng</span>
                </div>
            </div>
        </div>

        <h4 class="mb-4">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h4>

        <div class="mb-4">
            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['name']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
            <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($order['address']) ?></p>

            <?php
            $status = $order['status'];
            $statusColors = [
                'pending' => 'bg-secondary',
                'confirmed' => 'bg-primary',
                'shipping' => 'bg-info',
                'delivered' => 'bg-success',
                'cancelled' => 'bg-danger'
            ];
            $badgeClass = $statusColors[$status] ?? 'bg-dark';
            
            ?>
            <p>
                <strong>Trạng thái:</strong>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>

                <?php if ($status === 'pending'): ?>
                <form method="post" action="/webbanhang/order/cancel" class="d-inline-block ms-2"
                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                </form>
            <?php endif; ?>
            </p>
            <div><strong>Phương thức thanh toán:</strong>
                                <?php
                                if ($order['payment_method'] == 'cod') {
                                    echo 'Thanh toán khi nhận hàng';
                                } elseif ($order['payment_method'] == 'bank') {
                                    echo 'Thanh toán qua thẻ ngân hàng';
                                } else {
                                    echo 'Chưa có thông tin phương thức thanh toán';
                                }
                                ?>
                            </div>

            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Sản phẩm</th>
                    <th>Size</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($orderDetails as $item): ?>
                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="/webbanhang/<?= IMAGE_PATH_Product . $item['image'] ?>"
                                alt="<?= htmlspecialchars($item['product_name']) ?>" width="60"></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['size']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                        <td><?= number_format($subtotal, 0, ',', '.') ?>₫</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                    <td><strong><?= number_format($total, 0, ',', '.') ?>₫</strong></td>
                </tr>
            </tbody>
        </table>

        <a href="/webbanhang/order" class="btn btn-secondary">← Quay lại danh sách đơn hàng</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'app/views/shares/footer.php'; ?>