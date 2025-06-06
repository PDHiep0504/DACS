<?php include 'app/views/shares/header.php'; ?>

<?php
$checkoutItems = $_SESSION['checkout_items'] ?? [];
$total = 0;

foreach ($checkoutItems as $item) {
    foreach ($item['sizes'] as $size => $details) {
        $total += $details['quantity'] * $item['price'];
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <!-- Form thanh toán -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-lg p-4 border-0 rounded-4"
                style="background: linear-gradient(135deg, #dfe9f3, #ffffff);">
                <h2 class="text-center mb-4 fw-bold text-success">Thanh toán</h2>

                <?php if (empty($checkoutItems)): ?>
                    <div class="alert alert-warning text-center">Không có sản phẩm nào được chọn để thanh toán.</div>
                    <a href="/webbanhang/Product/cart" class="btn btn-primary w-100 mt-3">Quay lại giỏ hàng</a>
                <?php else: ?>
                    <!-- Nút chọn form thanh toán -->
                    <div class="btn-group w-100 mb-3">
                        <button type="button" class="btn btn-outline-primary w-50" id="btn-new-address">Thanh toán địa chỉ
                            mới</button>
                        <button type="button" class="btn btn-outline-secondary w-50" id="btn-saved-address">Thanh toán địa
                            chỉ đã lưu</button>
                    </div>

                    <!-- Form Địa chỉ mới -->
                    <form method="POST" action="/webbanhang/Product/processCheckout" id="newAddressForm"
                        style="display: block;">

                        <div class="form-group mb-3">
                            <label for="name">Họ tên:</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>

                        <div class="form-group mb-3" id="province-container">
                            <label for="province">Tỉnh/Thành phố:</label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="district">Quận/Huyện:</label>
                            <select id="district" name="district" class="form-control" disabled required>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="ward">Phường/Xã:</label>
                            <select id="ward" name="ward" class="form-control" disabled required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">Địa chỉ cụ thể:</label>
                            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_method">Phương thức thanh toán:</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Chọn phương thức</option>
                                <option value="cod">Thanh toán khi nhận hàng</option>
                                <option value="bank">Thanh toán qua thẻ ngân hàng</option>
                            </select>
                        </div>


                        <input type="hidden" name="provinceName" id="provinceName">
                        <input type="hidden" name="districtName" id="districtName">
                        <input type="hidden" name="wardName" id="wardName">
                        <input type="hidden" name="total_amount" value="<?= $total ?>">

                        <button type="submit" class="btn btn-success w-100 mt-3">Thanh toán</button>
                    </form>

                    <!-- Form Địa chỉ đã lưu -->
                    <form method="POST" action="/webbanhang/Product/processCheckout" id="savedAddressForm"
                        style="display: none;">
                        <div class="form-group mb-3">
                            <label for="addressSelect">Chọn địa chỉ của bạn:</label>
                            <div class="address-list">
                                <?php foreach ($addresses as $addr): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_id"
                                                    id="address_<?= $addr->id ?>" value="<?= $addr->id ?>" required>
                                                <label class="form-check-label" for="address_<?= $addr->id ?>">
                                                    <h5 class="card-title"><?= htmlspecialchars($addr->name) ?></h5>
                                                    <p class="card-text">
                                                        <strong>Điện thoại:</strong> <?= htmlspecialchars($addr->phone) ?><br>
                                                        <strong>Địa chỉ:</strong>
                                                        <?= htmlspecialchars($addr->address_detail . ', ' . $addr->wardName . ', ' . $addr->districtName . ', ' . $addr->provinceName) ?>
                                                    </p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_method">Phương thức thanh toán:</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">Chọn phương thức</option>
                                <option value="cod">Thanh toán khi nhận hàng</option>
                                <option value="bank">Thanh toán qua thẻ ngân hàng</option>
                            </select>
                        </div>

                        <input type="hidden" name="total_amount" value="<?= $total ?>">

                        <button type="submit" class="btn btn-success w-100 mt-3">Thanh toán</button>
                    </form>

                <?php endif; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-6">
            <?php if (!empty($checkoutItems)): ?>
                <div class="card shadow-lg p-4 border-0 rounded-4">
                    <h4 class="fw-bold text-primary mb-3">Sản phẩm đã chọn: </h4>
                    <?php foreach ($checkoutItems as $item):
                        $imageName = basename($item['image']);
                        $imageRelativePath = '/webbanhang/' . IMAGE_PATH_Product . $imageName;
                        $imageAbsolutePath = $_SERVER['DOCUMENT_ROOT'] . $imageRelativePath;
                        $imageUrl = file_exists($imageAbsolutePath) ? $imageRelativePath : '/public/images/default.png';

                        foreach ($item['sizes'] as $size => $details):
                            ?>
                            <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="text-muted">Size: <?= htmlspecialchars($details['size_name']) ?> , Số lượng:
                                        <?= $details['quantity'] ?>
                                    </div>
                                    <div class="text-danger">Giá: <?= number_format($item['price']) ?>đ</div>
                                </div>
                            </div>
                        <?php endforeach; endforeach; ?>

                    <div class="text-end mt-4">
                        <h5 class="fw-bold text-success">Tổng tiền: <?= number_format($total) ?>đ</h5>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript: xử lý chọn form thanh toán -->
<script>
    fetch('https://provinces.open-api.vn/api/p/')
        .then(res => res.json())
        .then(provinces => {
            const provinceSelect = document.createElement('select');
            provinceSelect.name = 'province';
            provinceSelect.className = 'form-control';
            provinceSelect.required = true;
            provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
            provinces.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.code;
                opt.text = p.name;
                provinceSelect.appendChild(opt);
            });

            document.getElementById('province-container').appendChild(provinceSelect);

            provinceSelect.addEventListener('change', function () {
                const provinceCode = this.value;
                document.getElementById('provinceName').value = this.options[this.selectedIndex].text;

                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        const districtSelect = document.getElementById('district');
                        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                        districtSelect.disabled = false;

                        const wardSelect = document.getElementById('ward');
                        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                        wardSelect.disabled = true;
                        document.getElementById('wardName').value = '';

                        data.districts.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.code;
                            opt.text = d.name;
                            districtSelect.appendChild(opt);
                        });

                        districtSelect.addEventListener('change', function () {
                            const districtCode = this.value;
                            document.getElementById('districtName').value = this.options[this.selectedIndex].text;

                            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                                .then(res => res.json())
                                .then(data => {
                                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                                    wardSelect.disabled = false;

                                    data.wards.forEach(w => {
                                        const opt = document.createElement('option');
                                        opt.value = w.code;
                                        opt.text = w.name;
                                        wardSelect.appendChild(opt);
                                    });

                                    wardSelect.addEventListener('change', function () {
                                        document.getElementById('wardName').value = this.options[this.selectedIndex].text;
                                    });
                                });
                        });
                    });
            });
        });
    document.getElementById('btn-new-address').addEventListener('click', function () {
        document.getElementById('newAddressForm').style.display = 'block';
        document.getElementById('savedAddressForm').style.display = 'none';
        this.classList.add('btn-primary');
        document.getElementById('btn-saved-address').classList.remove('btn-primary');
        document.getElementById('btn-saved-address').classList.add('btn-outline-secondary');
    });

    document.getElementById('btn-saved-address').addEventListener('click', function () {
        document.getElementById('savedAddressForm').style.display = 'block';
        document.getElementById('newAddressForm').style.display = 'none';
        this.classList.add('btn-primary');
        document.getElementById('btn-new-address').classList.remove('btn-primary');
        document.getElementById('btn-new-address').classList.add('btn-outline-primary');
    });
</script>

<style>
    .btn-primary {
        background-color: #28a745 !important;
        color: white;
    }

    .btn-outline-primary:hover {
        background-color: #28a745 !important;
        color: white;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d !important;
        color: white;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>