<?php
// Kiểm tra nếu $productSizes không rỗng hoặc null
$productSizes = isset($productSizes) ? $productSizes : [];
$selectedSizes = array_column($productSizes, 'size_id'); // Lấy mảng các size_id đã chọn
?>


<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>

    <!-- Thêm Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    /* Style cho phần chọn kích thước */
    .size-checkboxes {
        display: grid;
        grid-template-columns: repeat(5, 1fr); /* Chia làm 5 cột */
        gap: 10px; /* Khoảng cách giữa các ô */
        margin-top: 10px;
    }

    /* Tùy chỉnh giao diện của từng ô size */
    .size-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .size-item input {
        margin-right: 5px;
    }

    </style>
    
    <!-- Script xem trước ảnh -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.classList.remove('d-none');
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4 p-4">
                    <h2 class="text-center text-primary fw-bold mb-3">
                        <i class="fas fa-plus-circle"></i> Thêm sản phẩm mới
                    </h2>

                    <!-- Hiển thị lỗi -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/webbanhang/admin/save" enctype="multipart/form-data"
                        onsubmit="return validateForm();">

                        <!-- Tên sản phẩm -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">📌 Tên sản phẩm</label>
                            <input type="text" id="name" name="name" class="form-control rounded-3 shadow-sm" required
                                placeholder="Nhập tên sản phẩm...">
                        </div>

                        <!-- Mô tả -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">📖 Mô tả</label>
                            <textarea id="description" name="description" class="form-control rounded-3 shadow-sm" rows="3"
                                required placeholder="Nhập mô tả sản phẩm..."></textarea>
                        </div>

                        <!-- Giá -->
                        <div class="mb-3">
                            <label for="price" class="form-label fw-semibold">💰 Giá (VND)</label>
                            <input type="number" id="price" name="price" class="form-control rounded-3 shadow-sm"
                                step="0.01" required placeholder="Nhập giá sản phẩm...">
                        </div>

                        <!-- Danh mục -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">📂 Danh mục</label>
                            <select id="category_id" name="category_id" class="form-select rounded-3 shadow-sm" required>
                                <option value="" disabled selected>-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>">
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Kích thước (bấm vào mới hiện size) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📏 
                                <a data-bs-toggle="collapse" href="#sizeCollapse" role="button" aria-expanded="false" aria-controls="sizeCollapse">
                                    Kích thước <i class="fas fa-chevron-down"></i>
                                </a>
                            </label>

                            <div class="collapse mt-2" id="sizeCollapse">
                                <div class="size-checkboxes">
                                    <?php foreach ($sizes as $size): ?>
                                        <div class="form-check size-item">
                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="<?= $size->id ?>" id="size_<?= $size->id ?>"
                                                <?= in_array($size->id, $selectedSizes) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="size_<?= $size->id ?>">
                                                <?= $size->size ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hình ảnh -->
                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">🖼 Hình ảnh</label>
                            <input type="file" id="image" name="image" class="form-control rounded-3 shadow-sm"
                                accept="image/*" onchange="previewImage(event)">
                            <div class="mt-3 text-center">
                                <img id="imagePreview" src="" class="img-fluid rounded-3 d-none"
                                    style="max-height: 200px; object-fit: cover; border: 1px solid #ddd; padding: 5px;">
                            </div>
                        </div>

                        <!-- Nút thêm sản phẩm -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow-sm">
                            <i class="fas fa-check-circle"></i> Thêm sản phẩm
                        </button>
                    </form>

                    <!-- Quay lại -->
                    <div class="text-center mt-3">
                        <a href="/webbanhang/admin/products" class="btn btn-secondary rounded-3 fw-bold shadow-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại quản lý sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
