<div class="review-section mt-5">
    <h4 class="mb-4 text-center font-weight-bold text-uppercase" style="color: #333;">Đánh giá & Nhận xét sản phẩm</h4>

    <?php if (!empty($productReviews)): ?>
        <?php
        $totalReviews = count($productReviews);
        $totalRating = 0;
        $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        foreach ($productReviews as $review) {
            $rating = isset($review->rating_value) ? (int) $review->rating_value : 0;
            if ($rating >= 1 && $rating <= 5) {
                $totalRating += $rating;
                $ratingCounts[$rating]++;
            }
        }

        $averageRating = $totalReviews ? round($totalRating / $totalReviews, 1) : 0;
        ?>

        <!-- Trung bình đánh giá + Biểu đồ sao -->
        <div class="d-flex justify-content-between align-items-start mb-4 border rounded p-4 bg-white shadow-sm">
            <!-- Trung bình đánh giá (1/3) -->
            <div class="text-center" style="width: 33.33%;">
                <div class="display-4 fw-bold text-primary"><?= $averageRating ?>/5</div>
                <div style="font-size: 28px; color: #f39c12;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?= $i <= round($averageRating) ? '★' : '☆'; ?>
                    <?php endfor; ?>
                </div>
                <small class="text-muted"><?= $totalReviews ?> đánh giá</small>
            </div>

            <!-- Đường kẻ dọc -->
            <div class="mx-3" style="width: 1px; background-color: #dee2e6;"></div>

            <!-- Biểu đồ số lượng sao (2/3) -->
            <div class="d-flex flex-column align-items-start justify-content-center" style="width: 66.66%;">
                <?php foreach (array_reverse([5, 4, 3, 2, 1]) as $star): ?>
                    <div class="d-flex align-items-center mb-2" style="font-size: 14px; width: 100%;">
                        <div style="width: 40px;">
                            <span><?= $star ?></span>
                            <span style="color: #f39c12;">★</span>
                        </div>

                        <div class="progress flex-grow-1 mx-2"
                            style="height: 12px; background-color: #e9ecef; border-radius: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: <?= ($ratingCounts[$star] / $totalReviews) * 100 ?>%"
                                aria-valuenow="<?= $ratingCounts[$star] ?>" aria-valuemin="0"
                                aria-valuemax="<?= $totalReviews ?>">
                            </div>
                        </div>
                        <div class="text-muted" style="width: 30px;"><?= $ratingCounts[$star] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bộ lọc số sao -->
        <div class="text-center mb-4">
            <strong>Lọc theo số sao:</strong>
            <button class="btn btn-outline-secondary btn-sm mx-1 filter-btn" data-rating="all">Tất cả</button>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <button class="btn btn-outline-warning btn-sm mx-1 filter-btn" data-rating="<?= $i ?>"><?= $i ?>★</button>
            <?php endfor; ?>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="mt-4" id="review-list">
            <?php foreach ($productReviews as $review):
                $username = isset($review->account_name) ? htmlspecialchars($review->account_name, ENT_QUOTES, 'UTF-8') : 'Ẩn danh';
                $comment = isset($review->comment) ? nl2br(htmlspecialchars($review->comment, ENT_QUOTES, 'UTF-8')) : 'Không có nhận xét.';
                $rating = isset($review->rating_value) ? (int) $review->rating_value : 0;
                $date = isset($review->created_at) ? date('d/m/Y', strtotime($review->created_at)) : '';
                ?>
                <div class="border rounded p-4 mb-3 bg-light shadow-sm hover-shadow-lg transition-all review-item"
                    data-rating="<?= $rating ?>">
                    <div class="d-flex align-items-start mb-2">
                        <div class="me-3" style="font-size: 32px; color: #6c757d;">
                            <i class="fa fa-user-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-person text-secondary me-1"></i>
                                <?= $username ?>
                                <?php if ($date): ?>
                                    <span class="text-muted" style="font-size: 14px;"> · <?= $date ?></span>
                                <?php endif; ?>
                            </div>
                            <div style="color: #f39c12; font-size: 20px;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?= $i <= $rating ? '★' : '☆'; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="mt-2 mb-0" style="font-size: 16px;"><?= $comment ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p class="text-center text-muted">Chưa có đánh giá cho sản phẩm này.</p>
    <?php endif; ?>
</div>

<!-- CSS -->
<style>
    .hover-shadow-lg:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .review-section .d-flex {
            flex-direction: column !important;
        }

        .review-section .text-center,
        .review-section .d-flex.flex-column {
            width: 100% !important;
        }

        .review-section .mx-3 {
            display: none;
        }
    }
    .filter-btn.active {
        background-color: #f39c12;
        color: white;
        border-color: #f39c12;
    }
</style>

<!-- JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const reviewItems = document.querySelectorAll('.review-item');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const selectedRating = btn.getAttribute('data-rating');

                // Cập nhật class active cho nút
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Lọc đánh giá
                reviewItems.forEach(item => {
                    const itemRating = item.getAttribute('data-rating');
                    if (selectedRating === 'all' || itemRating === selectedRating) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
