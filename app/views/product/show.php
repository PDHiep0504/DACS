<?php include 'app/views/shares/header.php'; ?>
<style>
    .alert-custom {
        font-size: 24px;
        text-align: center;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #d4edda;
        color: #155724;
    }

    .btn-size {
        padding: 5px 10px;
        font-size: 14px;
        border-radius: 15px;
        border: 2px solid #000;
        background-color: transparent;
        color: #000;
        transition: all 0.3s ease;
        cursor: pointer;
        text-transform: uppercase;
        margin: 1px;
    }


    .btn-size:hover,
    .btn-size.active {
        background-color: #000;
        color: white;
    }

    .btn-size:disabled {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        cursor: not-allowed;
    }

    .product-image {
        height: 400px;
        object-fit: cover;
    }

    .product-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
    }

    .product-image-container {
        display: flex;
        align-items: center;
        justify-content: center; /* Center the image horizontally */
    }

    .review-section {
        margin-top: 30px;
        border-top: 2px solid #ddd;
        padding-top: 20px;
    }

    .review-section h4 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .review {
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .stars {
        color: #f39c12;
    }

    .description-box {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        background-color: #f9f9f9;
        margin-top: 20px;
    }

    .description-box h4 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    .description-box p {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
    }

    .description-box img {
        max-width: 200px;
        border-radius: 10px;
        object-fit: cover;
    }
</style>

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Chi tiết sản phẩm</h2>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message_addToCart']) && isset($_GET['added'])): ?>
                <div id="message-box" class="alert-custom">
                    <?= $_SESSION['message_addToCart']; ?>
                </div>
                <?php unset($_SESSION['message_addToCart']); ?>
            <?php endif; ?>

            <?php if (isset($product) && is_object($product)): ?>
                <div class="row">
                    <div class="col-md-6 product-image-container">
                        <?php if (!empty($product->image)): ?>
                            <img src="/webbanhang/<?php echo IMAGE_PATH_Product . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                                class="img-fluid rounded product-image"
                                alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <img src="/images/no-image.png" class="img-fluid rounded product-image" alt="Không có ảnh">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 product-details">
                        <h3 class="card-title text-dark font-weight-bold">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p class="text-danger font-weight-bold h4">
                            💰 <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </p>
                        <p><strong>Danh mục:</strong>
                            <span class="badge bg-info text-white">
                                <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa có danh mục'; ?>
                            </span>
                        </p>

                        <div class="mb-3">
                            <p><strong>Size:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                <?php
                                if (!empty($productSizes)) {
                                    foreach ($productSizes as $size) {
                                        ?>
                                        <button type="button" class="btn-size" id="size-<?php echo $size->id; ?>"
                                            data-size-id="<?php echo $size->id; ?>"
                                            data-size-name="<?php echo htmlspecialchars($size->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($size->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </button>
                                        <?php
                                    }
                                } else {
                                    echo "Không có size";
                                }
                                ?>
                            </div>
                        </div>

                        <form id="add-to-cart-form" action="/webbanhang/Product/addToCart/<?php echo $product->id; ?>"
                            method="POST">
                            <input type="hidden" id="selected-size-id" name="size_id" value="">
                            <input type="hidden" id="selected-size-name" name="size_name" value="">
                            <button type="submit" class="btn btn-success px-4" id="add-to-cart-btn">➕ Thêm vào giỏ
                                hàng</button>
                            <a href="/webbanhang/Product/" class="btn btn-secondary px-4 ml-2">Quay lại danh sách</a>
                        </form>
                    </div>
                </div>

                <!-- Mô tả sản phẩm -->
                <div class="description-box">
                    <div>
                        <h4>Mô tả sản phẩm</h4>
                        <p>
                            <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                    </div>
                    <img src="/webbanhang/<?php echo IMAGE_PATH_Product . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <!-- Phần đánh giá -->
                <?php include 'app/views/product/review_section.php'; ?>

            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>Không tìm thấy sản phẩm!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
    window.onload = function () {
        var messageBox = document.getElementById("message-box");
        if (messageBox) {
            setTimeout(function () {
                messageBox.style.display = "none";
            }, 3000);
        }

        var addToCartBtn = document.getElementById("add-to-cart-btn");
        addToCartBtn.addEventListener("click", function (event) {
            var selectedSize = document.querySelector('.btn-size.active');
            if (!selectedSize) {
                event.preventDefault();
                alert("Vui lòng chọn size sản phẩm!");
            } else {
                document.getElementById('selected-size-id').value = selectedSize.getAttribute('data-size-id');
                document.getElementById('selected-size-name').value = selectedSize.getAttribute('data-size-name');
            }
        });

        var sizeButtons = document.querySelectorAll('.btn-size');
        sizeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                sizeButtons.forEach(function (btn) { btn.classList.remove('active'); });
                this.classList.add('active');
            });
        });
    };
</script>