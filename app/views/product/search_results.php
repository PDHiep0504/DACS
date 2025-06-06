<?php include 'app/views/shares/header.php'; ?>
<?php require_once 'app/helpers/SessionHelper.php'; ?>

<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .search-header {
        margin-bottom: 30px;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 15px;
        position: relative;
    }
    
    .search-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #d63031;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .search-title i {
        margin-right: 10px;
    }
    
    .search-query {
        font-weight: 500;
        color: #343a40;
    }
    
    .search-meta {
        font-size: 0.9rem;
        color: #6c757d;
        font-style: italic;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        transform: translateY(-7px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .card-img-container {
        height: 220px;
        overflow: hidden;
        background: #fff;
        position: relative;
    }
    
    .card-img-top {
        height: 100%;
        width: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
        padding: 15px;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .card-body {
        padding: 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
        border-top: 2px solid #e9ecef;
    }
    
    .card-title {
        margin-bottom: 10px;
        min-height: 48px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-title a {
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        color: #212529;
        transition: color 0.2s ease;
    }

    .card-title a:hover {
        color: #d63031;
    }

    .card-text {
        font-size: 1.1rem;
        font-weight: 700;
        color: #d63031;
        margin-top: auto;
    }
    
    .no-results {
        background: #fff;
        padding: 40px 20px;
        text-align: center;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .no-results i {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
        display: block;
    }
    
    .btn-back {
        display: inline-block;
        padding: 10px 25px;
        margin: 30px auto;
        background-color: #d63031;
        color: white;
        font-weight: 600;
        border-radius: 30px;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(214, 48, 49, 0.3);
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #c0392b;
        box-shadow: 0 6px 12px rgba(214, 48, 49, 0.4);
        transform: translateY(-2px);
        color: white;
    }
    
    .footer-container {
        margin-top: 60px;
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }
        
        .card-img-container {
            height: 180px;
        }
        
        .search-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container mt-5">
    <div class="search-header">
        <div class="search-title">
            <i class="fas fa-search"></i> Kết quả tìm kiếm
        </div>
        <div class="search-query">Từ khóa: "<?php echo htmlspecialchars($_GET['query']); ?>"</div>
        <?php if (!empty($results)): ?>
            <div class="search-meta">Tìm thấy <?php echo count($results); ?> sản phẩm</div>
        <?php endif; ?>
    </div>

    <?php if (empty($results)): ?>
        <div class="no-results">
            <i class="fas fa-search-minus"></i>
            <h4>Không tìm thấy sản phẩm nào phù hợp</h4>
            <p>Vui lòng thử tìm kiếm với từ khóa khác</p>
            <a href="/webbanhang/product/" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại trang chủ
            </a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($results as $product): ?>
                <div class="card">
                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="card-img-container">
                        <?php if (!empty($product->image)): ?>
                            <img src="/webbanhang/<?php echo IMAGE_PATH_Product . htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                class="card-img-top" 
                                alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center bg-light h-100">
                                <i class="fas fa-tshirt fa-3x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/webbanhang/product/" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại trang chủ
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="footer-container">
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5 class="mb-3">Thông tin liên hệ</h5>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> Địa chỉ: 123 Đường ABC, Thành phố XYZ</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> Điện thoại: (012) 345-6789</p>
                    <p><i class="fas fa-envelope me-2"></i> Email: contact@webbanhang.com</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <h5 class="mb-3">Liên kết</h5>
                    <p class="mb-1">
                        <a href="/webbanhang/Product/" class="text-white text-decoration-none"><i class="fas fa-home me-1"></i> Trang chủ</a> |
                        <a href="/webbanhang/Contact/" class="text-white text-decoration-none"><i class="fas fa-envelope me-1"></i> Liên hệ</a>
                    </p>
                    <p>
                        <a href="/webbanhang/PrivacyPolicy/" class="text-white text-decoration-none"><i class="fas fa-shield-alt me-1"></i> Chính sách bảo mật</a>
                    </p>
                    <div class="mt-3">
                        <p class="mb-0">© 2025 Quản lý sản phẩm. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php include 'app/views/shares/footer.php'; ?>
