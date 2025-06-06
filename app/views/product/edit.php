<?php include 'app/views/shares/header.php'; ?>

<style>
    .size-checkboxes {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .size-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .size-item input {
        margin-right: 5px;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 p-4"
                style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                <h2 class="text-center text-primary fw-bold mb-3">
                    <i class="fas fa-edit"></i> Chỉnh sửa sản phẩm
                </h2>

                <!-- Hiển thị lỗi -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>⚠ Lỗi:</strong>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $product->id; ?>">

                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">📌 Tên sản phẩm</label>
                        <input type="text" id="name" name="name" class="form-control rounded-3 shadow-sm"
                            value="<?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">📖 Mô tả</label>
                        <textarea id="description" name="description" class="form-control rounded-3 shadow-sm" rows="3"
                            required><?= htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="row">
                        <!-- Giá -->
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label fw-semibold">💰 Giá (VND)</label>
                            <input type="number" id="price" name="price" class="form-control rounded-3 shadow-sm"
                                step="0.01" value="<?= htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>"
                                required>
                        </div>

                        <!-- Danh mục -->
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label fw-semibold">📂 Danh mục</label>
                            <select id="category_id" name="category_id" class="form-select rounded-3 shadow-sm"
                                required>
                                <option value="" disabled>-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->id; ?>" <?= $category->id == $product->category_id ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Kích thước -->
                    <div class="mb-3">
                        <label for="sizes" class="form-label fw-semibold">📏 Chọn kích thước</label>
                        <div class="size-checkboxes">
                            <?php
                            $selectedSizeIds = array_map(function ($s) {
                                return $s->id ?? $s->size_id;
                            }, $productSizes);
                            ?>

                            <?php foreach ($sizes as $size): ?>
                                <div class="size-item">
                                    <input type="checkbox" id="size_<?= $size->id ?>" name="sizes[]"
                                        value="<?= $size->id ?>" <?= in_array($size->id, $selectedSizeIds) ? 'checked' : ''; ?>>
                                    <label
                                        for="size_<?= $size->id ?>"><?= htmlspecialchars($size->size, ENT_QUOTES, 'UTF-8') ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">🖼 Hình ảnh</label>
                        <input type="file" id="image" name="image" class="form-control rounded-3 shadow-sm"
                            accept="image/*" onchange="previewImage(event)">
                        <input type="hidden" name="existing_image" value="<?= $product->image; ?>">

                        <div class="text-center mt-3">
                            <?php if ($product->image): ?>
                                <p class="fw-bold">Ảnh hiện tại:</p>
                                <img id="currentImage" src="/webbanhang/<?= IMAGE_PATH_Product . $product->image; ?>"
                                    alt="Hình ảnh sản phẩm" class="img-fluid rounded-3 shadow-sm"
                                    style="max-height: 200px; transition: opacity 0.3s;">
                            <?php endif; ?>
                            <img id="imagePreview" class="img-fluid rounded-3 shadow-sm d-none mt-2"
                                style="max-height: 200px; border: 2px dashed #007bff; padding: 5px;">
                        </div>
                    </div>

                    <!-- Nút lưu -->
                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold rounded-3 shadow-sm">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </form>

                <!-- Quay lại -->
                <div class="text-center mt-3">
                    <a href="/webbanhang/Product/"
                        class="btn btn-outline-primary rounded-3 fw-bold shadow-sm px-4 py-2">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script xem trước ảnh -->
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('imagePreview');
            const currentImage = document.getElementById('currentImage');
            output.src = reader.result;
            output.classList.remove('d-none');
            if (currentImage) {
                currentImage.style.opacity = "0.3";
            }
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>