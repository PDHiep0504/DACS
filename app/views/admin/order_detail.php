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
                    <a href="/webbanhang/admin/">Trang Quản Lý</a>&nbsp;|&nbsp;
                    <a href="/webbanhang/admin/orders">QL Đơn hàng</a>&nbsp;|&nbsp;
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
            </p>

            <div class="mt-2">
                <?php if ($status === 'pending'): ?>
                    <form method="post" action="/webbanhang/admin/confirm" onsubmit="return confirm('Xác nhận đơn hàng này?');" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Xác nhận đơn</button>
                    </form>
                <?php elseif ($status === 'confirmed'): ?>
                    <form method="post" action="/webbanhang/admin/shipping" onsubmit="return confirm('Bắt đầu giao đơn hàng này?');" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-primary">Bắt đầu giao hàng</button>
                    </form>
                <?php elseif ($status === 'shipping'): ?>
                    <form method="post" action="/webbanhang/admin/delivered" onsubmit="return confirm('Xác nhận đã giao hàng?');" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Đã giao hàng</button>
                    </form>
                <?php endif; ?>

                <?php if (!in_array($status, ['received', 'cancelled', 'delivered'])): ?>
                    <form method="post" action="/webbanhang/admin/cancel" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này?');" style="display:inline-block;">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hủy đơn</button>
                    </form>
                <?php endif; ?>
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
                        <td><img src="/webbanhang/<?= IMAGE_PATH_Product . $item['image'] ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" width="60"></td>
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

        <a href="/webbanhang/admin/orders" class="btn btn-secondary">← Quay về quản lý đơn hàng</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'app/views/shares/footer.php'; ?>
