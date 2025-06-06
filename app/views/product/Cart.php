<?php
include 'app/views/shares/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="container mt-5">
    <div class="text-center bg-primary text-white py-4 rounded shadow-sm">
        <h1 class="font-weight-bold"><i class="fas fa-shopping-cart"></i> Giỏ hàng</h1>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['product_id'], $_POST['size'])) {
        $productId = (int) $_POST['product_id'];
        $size = $_POST['size'];

        if (isset($_SESSION['cart'][$productId]['sizes'][$size])) {
            switch ($_POST['action']) {
                case 'increase':
                    $_SESSION['cart'][$productId]['sizes'][$size]['quantity']++;
                    break;
                case 'decrease':
                    $_SESSION['cart'][$productId]['sizes'][$size]['quantity']--;
                    if ($_SESSION['cart'][$productId]['sizes'][$size]['quantity'] <= 0) {
                        unset($_SESSION['cart'][$productId]['sizes'][$size]);
                        if (empty($_SESSION['cart'][$productId]['sizes'])) {
                            unset($_SESSION['cart'][$productId]);
                        }
                    }
                    break;
                case 'remove':
                    unset($_SESSION['cart'][$productId]['sizes'][$size]);
                    if (empty($_SESSION['cart'][$productId]['sizes'])) {
                        unset($_SESSION['cart'][$productId]);
                    }
                    break;
            }
        }
    }

    $cart = $_SESSION['cart'] ?? [];
    ?>

    <?php if (!empty($cart)): ?>
        <div class="row mt-5">
            <div class="col-12 col-md-8">
                <ul class="list-group">
                    <?php foreach ($cart as $id => $item): ?>
                        <?php foreach ($item['sizes'] as $size => $details): ?>
                            <?php $subtotal = $item['price'] * $details['quantity']; ?>
                            <li class="list-group-item d-flex flex-column mb-4 shadow-sm rounded bg-light">
                                <div class="d-flex align-items-start gap-3">
                                    <input type="checkbox" form="checkout-form" name="selected_products[]" value="<?= $id ?>|<?= $size ?>"
                                        class="form-check-input mt-2 product-checkbox" data-subtotal="<?= $subtotal ?>">

                                        <a href="/webbanhang/product/show/<?= $id ?>">
                                        <img src="/webbanhang/<?= IMAGE_PATH_Product . htmlspecialchars($item['image'] ?? '') ?>"
                                            class="cart-img img-thumbnail" alt="Product Image">
                                        </a>

                                    <div class="flex-grow-1">
                                        <a href="/webbanhang/product/show/<?= $id ?>" class="text-decoration-none text-dark">
                                            <h5 class="text-primary mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                        </a>
                                        <p class="mb-1"><strong>Size:</strong> <?= htmlspecialchars($details['size_name'] ?? $size) ?></p>
                                        <p class="mb-1"><strong>Đơn giá:</strong> <?= number_format($item['price']) ?> VND</p>
                                        <p class="mb-1 text-danger"><strong>Tổng:</strong> <?= number_format($subtotal) ?> VND</p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 px-2">
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-ajax" data-action="decrease"
                                            data-product-id="<?= $id ?>" data-size="<?= $size ?>"><i class="fas fa-minus"></i></button>
                                        <span class="mx-3 fw-bold"><?= $details['quantity'] ?></span>
                                        <button type="button" class="btn btn-outline-success btn-sm btn-ajax" data-action="increase"
                                            data-product-id="<?= $id ?>" data-size="<?= $size ?>"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm btn-ajax"
                                        data-action="remove" data-product-id="<?= $id ?>" data-size="<?= $size ?>">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-4 d-flex justify-content-between">
                    <a href="/webbanhang/Product" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Tiếp tục mua sắm</a>
                </div>
            </div>
            <div class="col-12 col-md-4 mt-3 mt-md-0">
                <div class="card bg-light p-4 shadow-sm rounded">
                    <h4 class="text-success"><i class="fas fa-coins"></i> Tổng tiền:</h4>
                    <h2 class="text-danger fw-bold" id="selected-total">0 VND</h2>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <form id="checkout-form" action="/webbanhang/Product/checkout" method="POST"
                        onsubmit="return validateForm()">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Thanh Toán sản phẩm đã chọn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-3">
            <i class="fas fa-shopping-basket"></i> Giỏ hàng của bạn đang trống.
        </div>
    <?php endif; ?>
</div>

<div id="error-message" class="alert alert-danger text-center mt-4" style="display: none;">
    <i class="fas fa-exclamation-circle"></i> Bạn chưa chọn sản phẩm nào. Vui lòng chọn sản phẩm ⚠️
</div>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f8f9fa;
    }

    .cart-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1.2rem;
    }

    .list-group-item {
        padding: 15px;
        font-size: 1rem;
        border: none;
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f1f1f1;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .alert {
        font-size: 1.1rem;
    }

    @media (max-width: 576px) {
        .cart-img {
            width: 60px;
            height: 60px;
        }

        .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }

        h5 {
            font-size: 1rem;
        }

        .card h4,
        .card h2 {
            font-size: 1.1rem;
        }

        .text-end {
            flex-shrink: 0;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const totalDisplay = document.getElementById('selected-total');

        function formatCurrency(number) {
            return new Intl.NumberFormat('vi-VN').format(number) + ' VND';
        }

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.subtotal);
                }
            });
            totalDisplay.textContent = formatCurrency(total);
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        updateTotal();

        const buttons = document.querySelectorAll('.btn-ajax');
        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const action = this.dataset.action;
                const productId = this.dataset.productId;
                const size = this.dataset.size;

                if (action === 'remove') {
                    const confirmDelete = confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?");
                    if (!confirmDelete) return;
                }

                $.ajax({
                    url: '/webbanhang/Product/cart',
                    type: 'POST',
                    data: {
                        action: action,
                        product_id: productId,
                        size: size
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function (error) {
                        console.error('Có lỗi xảy ra:', error);
                    }
                });
            });
        });
    });

    function validateForm() {
        const checkboxes = document.querySelectorAll('.product-checkbox:checked');
        const isLoggedIn = <?php echo SessionHelper::isLoggedIn() ? 'true' : 'false'; ?>;

        if (checkboxes.length === 0) {
            document.getElementById('error-message').style.display = 'block';
            return false;
        }

        if (!isLoggedIn) {
            alert("Bạn cần đăng nhập trước khi thanh toán.");
            window.location.href = '/webbanhang/account/login';
            return false;
        }

        return true;
    }
</script>

<?php
include 'app/views/shares/footer.php';
?>
