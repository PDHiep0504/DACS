<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center">Xác nhận đơn hàng</h2>
        </div>
        <div class="alert alert-success text-center mt-3">
            Bạn đã tạo đơn hàng thành công!
        </div>
        <div class="card-body">
            <?php
            // Lấy thông tin từ session
            $orderId = $_GET['order_id'] ?? 'Không xác định';
            $name = $_SESSION['checkout_name'] ?? 'Không xác định';
            $phone = $_SESSION['checkout_phone'] ?? 'Không xác định';
            $address = $_SESSION['checkout_address'] ?? 'Không xác định';
            $totalPrice = $_SESSION['checkout_total_amount'] ?? 0;
            $paymentMethod = $_SESSION['checkout_payment_method'] ?? 'Không xác định';
            ?>

            <div class="order-details">
                <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($orderId) ?></p>
                <p><strong>Người nhận:</strong> <?= htmlspecialchars($name) ?></p>
                <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($phone) ?></p>
                <p><strong>Địa chỉ nhận:</strong> <?= htmlspecialchars($address) ?></p>
                <p><strong>Tổng tiền:</strong> <?= htmlspecialchars($totalPrice) ?> VND</p>
                <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($paymentMethod) ?></p>
            </div>

            <div class="text-center mt-4">
                <a href="/webbanhang/Product/" class="btn btn-success">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>