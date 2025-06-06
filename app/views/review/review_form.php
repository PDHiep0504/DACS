<?php
$currentPage = 'review';
include 'app/views/shares/header.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đánh giá sản phẩm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .account-page {
            max-width: 1200px;
            margin: 40px auto;
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

        .product-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fdfdfd;
        }

        .product-card img {
            max-width: 80px;
        }

        .review-btn {
            background-color: black;
            color: white;
            padding: 8px 16px;
            border: none;
        }

        .sidebar {
            border-right: 1px solid #eee;
            padding-right: 30px;
            height: 100%;
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

        .content {
            width: 75%;
        }

        .review-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .review-box h4 {
            margin-bottom: 20px;
        }

        .rating-stars {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            margin-left: 5px;
        }

        .rating-stars input {
            display: none;
            /* Ẩn các input radio */
        }

        .rating-stars label {
            font-size: 24px;
            color: gray;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-stars input:checked~label,
        /* Các sao phía bên phải sẽ đổi màu khi sao hiện tại được chọn */
        .rating-stars label:hover,
        .rating-stars label:hover~label {
            color: gold;
        }

        .star {
            font-size: 20px;
            color: gold;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .content {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container account-page">
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumb">
                    <a href="/webbanhang/product">Trang chủ</a>&nbsp;|&nbsp;<span class="fw-bold">Đánh giá sản
                        phẩm</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-12 sidebar">
                <h5 class="mb-3">TÀI KHOẢN</h5>
                <p>Xin chào,
                    <strong><?= isset($account) && $account ? htmlspecialchars($account->username) : 'Khách hàng' ?></strong>
                </p>
                <a href="/webbanhang/account/profile" class="<?= $currentPage == 'account' ? 'active' : '' ?>">Thông tin
                    tài khoản</a>
                <a href="/webbanhang/voucher" class="<?= $currentPage == 'voucher' ? 'active' : '' ?>">Mã giảm giá</a>
                <a href="/webbanhang/address" class="<?= $currentPage == 'address' ? 'active' : '' ?>">Địa chỉ</a>
                <a href="/webbanhang/order/" class="<?= $currentPage == 'orders' ? 'active' : '' ?>">Quản lý đơn
                    hàng</a>
                <a href="/webbanhang/wishlist" class="<?= $currentPage == 'wishlist' ? 'active' : '' ?>">Yêu thích</a>
                <a href="/webbanhang/review/reviews" class="<?= $currentPage == 'review' ? 'active' : '' ?>">Đánh giá
                    sản
                    phẩm</a>
                <a href="/webbanhang/points" class="<?= $currentPage == 'points' ? 'active' : '' ?>">Tích điểm</a>
            </div>

            <!-- Content -->
            <div class="col-md-9 col-12 content">
                <!-- Unreviewed Products -->
                <div class="review-box">
                    <h4>Chưa đánh giá</h4>
                    <?php if (!empty($unreviewed)): ?>
                        <?php foreach ($unreviewed as $item): ?>
                            <div class="product-card d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="/webbanhang/<?= IMAGE_PATH_Product . $item['image'] ?>"
                                        alt="<?= $item['product_name'] ?>" class="me-3">
                                    <div>
                                        <h6><?= htmlspecialchars($item['product_name']) ?></h6>
                                        <p>Size: <?= $item['size'] ?> | Số lượng: <?= $item['quantity'] ?></p>
                                        <p><strong>Giá: <?= number_format($item['price'], 0, ',', '.') ?> VNĐ</strong></p>
                                    </div>
                                </div>
                                <button class="review-btn" data-bs-toggle="modal"
                                    data-bs-target="#reviewModal<?= $item['order_detail_id'] ?>">Đánh giá</button>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="reviewModal<?= $item['order_detail_id'] ?>" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="post" action="/webbanhang/review/save">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Đánh giá sản phẩm</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Thông tin sản phẩm -->
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="/webbanhang/<?= IMAGE_PATH_Product . $item['image'] ?>"
                                                        alt="<?= $item['product_name'] ?>"
                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;"
                                                        class="me-3">
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($item['product_name']) ?></h6>
                                                        <small>Size: <?= $item['size'] ?> | Số lượng:
                                                            <?= $item['quantity'] ?></small>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <input type="hidden" name="order_detail_id"
                                                    value="<?= $item['order_detail_id'] ?>">

                                                <!-- Rating từ trái sang phải -->
                                                <label class="form-label">Số sao</label>
                                                <div class="rating-stars mb-3">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <input type="radio" id="star<?= $i ?>-<?= $item['order_detail_id'] ?>"
                                                            name="rating" value="<?= $i ?>" <?php if (isset($item['rating']) && $item['rating'] == $i)
                                                                  echo 'checked'; ?> required>
                                                        <label for="star<?= $i ?>-<?= $item['order_detail_id'] ?>">★</label>
                                                    <?php endfor; ?>
                                                </div>

                                                <!-- Nội dung -->
                                                <label class="form-label">Nội dung đánh giá</label>
                                                <textarea name="content" class="form-control" rows="4" required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-dark">Gửi đánh giá</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm nào đang chờ đánh giá.</p>
                    <?php endif; ?>
                </div>

                <!-- Reviewed Products -->
                <div class="review-box">
                    <h4>Đã đánh giá</h4>
                    <?php if (!empty($reviewed)): ?>
                        <?php foreach ($reviewed as $review): ?>
                            <div class="product-card">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="/webbanhang/<?= IMAGE_PATH_Product . $review['image'] ?>"
                                        alt="<?= $review['product_name'] ?>" class="me-3">
                                    <div>
                                        <h6><?= htmlspecialchars($review['product_name']) ?></h6>
                                        <p>Size: <?= $review['size'] ?> | Số lượng: <?= $review['quantity'] ?></p>
                                        <p><strong>Giá: <?= number_format($review['price'], 0, ',', '.') ?> VNĐ</strong></p>
                                    </div>
                                </div>
                                <div class="rating-stars mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star"><?= $i <= $review['rating'] ? '★' : '☆' ?></span>
                                    <?php endfor; ?>
                                </div>
                                <p><?= htmlspecialchars($review['comment']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Bạn chưa đánh giá sản phẩm nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'app/views/shares/footer.php'; ?>