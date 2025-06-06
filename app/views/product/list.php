<?php include 'app/views/shares/header.php'; ?>
<?php require_once 'app/helpers/SessionHelper.php'; ?>

<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    h1 {
        font-size: 2.2rem;
        font-weight: bold;
        color: red;
        margin-bottom: 20px;
        text-align: center;
    }

    h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .carousel-container {
        margin-top: 20px;
    }

    .carousel-item img {
        height: 500px;
        object-fit: cover;
    }    .product-container {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    
    .product-row {
        position: relative;
        display: flex;
        overflow-x: hidden;
        gap: 20px;
        padding: 10px 0;
        scroll-behavior: smooth;
        width: 100%;
    }    .card {
        width: calc((100% - 80px) / 5); /* 5 cards with 4 gaps (20px each) */
        flex: 0 0 auto;
        height: auto; /* Cho phép chiều cao tự điều chỉnh */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden; /* Đảm bảo nội dung không tràn ra khỏi card */
    }.scroll-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        z-index: 10;
        transition: all 0.3s ease;
    }
    
    .scroll-btn i {
        font-size: 1.25rem; /* Kích thước icon Bootstrap */
    }
    
    .scroll-btn:hover {
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    }
    
    .scroll-btn.left {
        left: 5px;
    }
    
    .scroll-btn.right {
        right: 5px;
    }

    .card:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 200px;
        object-fit: contain;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        background: #fff;
    }    .card-body {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px; /* Chiều cao tối thiểu thay vì cố định */
    }.product-title a {
        font-weight: normal;
        font-size: 1rem;
        color: #000000;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.5rem; /* Thay max-height bằng height cố định */
        min-height: 2.5rem; /* Đảm bảo chiều cao tối thiểu */
        line-height: 1.25rem;
    }    .product-title a:hover {
        text-decoration: underline;
    }
    
    .product-info {
        flex-grow: 1;
        margin-bottom: 5px;
    }

    .card-text {
        margin-top: 10px;
        font-weight: bold;
        color: #000000;
    }

    .btn {
        border-radius: 30px;
        padding: 6px 16px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn:hover {
        opacity: 0.9;
    }    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        width: 100%;
        flex-shrink: 0;
        gap: 5px;
    }
    
    .action-buttons .btn {
        flex: 1;
        padding: 5px 10px;
        font-size: 0.85rem;
        text-align: center;
    }

    footer {
        background-color: #343a40;
        color: white;
        padding: 30px 0;
        margin-top: 40px;
        text-align: center;
    }

    footer a {
        color: white !important;
        text-decoration: none;
    }

    .category-section {
        margin-bottom: 30px;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 10px;
    }    @media screen and (max-width: 1200px) {
        .card {
            width: calc((100% - 60px) / 4); /* 4 cards with 3 gaps (20px each) */
        }
    }

    @media screen and (max-width: 992px) {
        .card {
            width: calc((100% - 40px) / 3); /* 3 cards with 2 gaps (20px each) */
        }
    }

    @media screen and (max-width: 768px) {
        .card {
            width: calc((100% - 20px) / 2); /* 2 cards with 1 gap (20px each) */
        }
    }

    @media screen and (max-width: 576px) {
        .card {
            width: 100%; /* 1 card, no gaps needed */
        }
    }
</style>

<div class="container carousel-container">
    <div id="productSlider" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/webbanhang/<?php echo IMAGE_PATH_Banner . 'QC1.png'; ?>" class="d-block w-100"
                    alt="First slide">
            </div>
            <div class="carousel-item">
                <img src="/webbanhang/<?php echo IMAGE_PATH_Banner . 'QC2.png'; ?>" class="d-block w-100"
                    alt="Second slide">
            </div>
            <div class="carousel-item">
                <img src="/webbanhang/<?php echo IMAGE_PATH_Banner . 'bannerfootball.png'; ?>" class="d-block w-100"
                    alt="Third slide">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<div class="container mt-4">
    <h1><i class="fas fa-boxes"></i> Danh sách sản phẩm</h1>

    <?php if (SessionHelper::isAdmin()): ?>
        <div class="mb-3 text-end">
            <a href="/webbanhang/Product/add" class="btn btn-success"><i class="fas fa-plus"></i> Thêm sản phẩm</a>
        </div>
    <?php endif; ?>

    <?php
    // Tổ chức sản phẩm theo danh mục
    $productsByCategory = [];
    foreach ($products as $product) {
        $categoryName = !empty($product->category_name) ? $product->category_name : 'Chưa phân loại';
        if (!isset($productsByCategory[$categoryName])) {
            $productsByCategory[$categoryName] = [];
        }
        $productsByCategory[$categoryName][] = $product;
    }
    
    // Hiển thị sản phẩm theo từng danh mục
    foreach ($productsByCategory as $categoryName => $categoryProducts):
    ?>        <div class="category-section mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h2><?php echo htmlspecialchars($categoryName); ?></h2>
                <?php
                // Tìm category_id từ product đầu tiên trong danh mục
                $categoryId = !empty($categoryProducts[0]->category_id) ? $categoryProducts[0]->category_id : null;
                if ($categoryId): ?>
                    <a href="/webbanhang/Product/category/<?php echo $categoryId; ?>" class="btn btn-outline-primary btn-sm">
                        Xem thêm <i class="bi bi-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <div class="product-container position-relative"><button class="scroll-btn left"><i class="bi bi-arrow-left"></i></button>
                <div class="product-row">
                    <?php foreach ($categoryProducts as $product): ?>
                        <div class="card">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                <?php if (!empty($product->image)): ?>
                                    <img src="/webbanhang/<?php echo IMAGE_PATH_Product . $product->image; ?>" class="card-img-top"
                                        alt="<?php echo htmlspecialchars($product->name); ?>">
                                <?php else: ?>
                                    <div class="d-flex justify-content-center align-items-center bg-light card-img-top">
                                        <i class="fas fa-image fa-3x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                            </a>                            <div class="card-body">
                                <div class="product-info">
                                    <h5 class="product-title mb-2">
                                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                            <?php echo htmlspecialchars($product->name); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text mb-0">
                                        <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                                    </p>
                                </div>                                <?php if (SessionHelper::isAdmin()): ?>
                                    <div class="action-buttons">
                                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </a>
                                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>                </div>
                <button class="scroll-btn right"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <div class="container">
        <p class="mb-0">© 2025 Quản lý sản phẩm. All rights reserved.</p>
        <p>
            <a href="/Product/">Danh sách sản phẩm</a> |
            <a href="/Contact/">Liên hệ</a> |
            <a href="/PrivacyPolicy/">Chính sách bảo mật</a>
        </p>    </div>
</footer>

<script>    document.addEventListener('DOMContentLoaded', function() {
        // Lấy tất cả các nút scroll
        const leftButtons = document.querySelectorAll('.scroll-btn.left');
        const rightButtons = document.querySelectorAll('.scroll-btn.right');
        
        // Tính toán chiều rộng của 5 sản phẩm (bao gồm cả khoảng cách)
        const calculateScrollAmount = () => {
            const container = document.querySelector('.product-container');
            if (container) {
                return container.offsetWidth; // Scroll đúng chiều rộng hiển thị (5 sản phẩm)
            }
            return 1000; // Giá trị mặc định nếu không tìm thấy container
        };
        
        // Ẩn nút scroll nếu không cần thiết
        const checkScrollButtons = () => {
            const productContainers = document.querySelectorAll('.product-container');
            
            productContainers.forEach(container => {
                const productRow = container.querySelector('.product-row');
                const leftBtn = container.querySelector('.scroll-btn.left');
                const rightBtn = container.querySelector('.scroll-btn.right');
                
                // Số sản phẩm trong danh mục
                const productCount = productRow.children.length;
                
                // Nếu số sản phẩm ít hơn hoặc bằng 5, ẩn cả hai nút
                if (productCount <= 5) {
                    leftBtn.style.display = 'none';
                    rightBtn.style.display = 'none';
                } else {
                    // Xử lý hiển thị ban đầu
                    leftBtn.style.display = 'none'; // Ban đầu ở vị trí bắt đầu, ẩn nút trái
                    rightBtn.style.display = 'flex';
                    
                    // Xử lý hiển thị khi scroll
                    productRow.addEventListener('scroll', function() {
                        // Kiểm tra vị trí scroll
                        if (productRow.scrollLeft <= 0) {
                            leftBtn.style.display = 'none';
                        } else {
                            leftBtn.style.display = 'flex';
                        }
                        
                        if (productRow.scrollLeft + productRow.offsetWidth >= productRow.scrollWidth - 5) {
                            rightBtn.style.display = 'none';
                        } else {
                            rightBtn.style.display = 'flex';
                        }
                    });
                }
            });
        };
        
        // Thêm sự kiện cho nút scroll sang trái
        leftButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productRow = this.nextElementSibling;
                productRow.scrollBy({
                    left: -calculateScrollAmount(), // Cuộn sang trái đúng 5 sản phẩm
                    behavior: 'smooth'
                });
            });
        });
        
        // Thêm sự kiện cho nút scroll sang phải
        rightButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productRow = this.previousElementSibling;
                productRow.scrollBy({
                    left: calculateScrollAmount(), // Cuộn sang phải đúng 5 sản phẩm
                    behavior: 'smooth'
                });
            });
        });
        
        // Kiểm tra và thiết lập trạng thái nút ban đầu
        checkScrollButtons();
        
        // Cập nhật khi thay đổi kích thước màn hình
        window.addEventListener('resize', checkScrollButtons);
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>