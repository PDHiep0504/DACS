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
        color: #d63031;
        margin-bottom: 20px;
        text-align: center;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 200px;
        object-fit: contain;
        background: #fff;
    }

    .card-body {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-title a {
        font-weight: normal;
        font-size: 1rem;
        color: #000000;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.5rem;
        line-height: 1.25rem;
    }

    .product-title a:hover {
        text-decoration: underline;
    }

    .card-text {
        margin-top: 10px;
        font-weight: bold;
        color: #000000;
    }

    .back-link {
        margin-bottom: 20px;
        display: inline-block;
    }

    .no-products {
        text-align: center;
        padding: 50px 0;
        color: #777;
    }

    .action-buttons {
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

    .btn {
        border-radius: 30px;
        padding: 6px 16px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<div class="container mt-5">
    <a href="/webbanhang/Product" class="back-link btn btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách sản phẩm
    </a>

    <h1>
        <i class="fas fa-th-list"></i> 
        <?php echo htmlspecialchars($category->name); ?>
    </h1>

    <div class="row mb-3">
        <div class="col-md-12">
            <?php if (!empty($category->description)): ?>
                <p class="category-description">
                    <?php echo htmlspecialchars($category->description); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (SessionHelper::isAdmin()): ?>
        <div class="mb-3 text-end">
            <a href="/webbanhang/Product/add" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="no-products">
            <i class="fas fa-box-open fa-3x mb-3"></i>
            <h3>Không có sản phẩm nào trong danh mục này.</h3>
            <p>Vui lòng quay lại sau hoặc khám phá các danh mục khác.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
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
                    </a>
                    <div class="card-body">
                        <div class="product-info">
                            <h5 class="product-title mb-2">
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                    <?php echo htmlspecialchars($product->name); ?>
                                </a>
                            </h5>
                            <p class="card-text mb-0">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                            </p>
                        </div>
                        
                        <?php if (SessionHelper::isAdmin()): ?>
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
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
