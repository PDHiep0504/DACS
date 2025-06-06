<?php
$currentPage = 'address';
include 'app/views/shares/header.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Địa chỉ của tôi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        .account-page {
            max-width: 1200px;
            margin: 40px auto;
        }

        .sidebar {
            border-right: 1px solid #eee;
            padding-right: 30px;
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

        .btn-address {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .address-card {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
        }

        .address-actions {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .address-actions button {
            margin-left: 5px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="container account-page">
        <div class="row mb-3">
            <div class="col-12">
                <div class="breadcrumb">
                    <a href="/webbanhang/product">Trang chủ</a>&nbsp;|&nbsp;<span class="fw-bold">Địa chỉ của tôi</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h5 class="mb-3">TÀI KHOẢN</h5>
                <p>Xin chào, <strong><?= htmlspecialchars($account->username ?? '') ?></strong></p>
                <a href="/webbanhang/account/profile" class="<?= $currentPage == 'account' ? 'active' : '' ?>">Thông tin
                    tài khoản</a>
                <!-- <a href="/webbanhang/voucher" class="<?= $currentPage == 'voucher' ? 'active' : '' ?>">Mã giảm giá của
                    tôi</a> -->
                <a href="/webbanhang/address" class="<?= $currentPage == 'address' ? 'active' : '' ?>">Địa chỉ</a>
                <a href="/webbanhang/order/" class="<?= $currentPage == 'orders' ? 'active' : '' ?>">Quản lý đơn
                    hàng</a>
                <!-- <a href="/webbanhang/wishlist" class="<?= $currentPage == 'wishlist' ? 'active' : '' ?>">Danh sách yêu
                    thích</a> -->
                <a href="/webbanhang/review/reviews" class="<?= $currentPage == 'member' ? 'active' : '' ?>">Đánh giá
                    sản phẩm</a>
                <!-- <a href="/webbanhang/points" class="<?= $currentPage == 'points' ? 'active' : '' ?>">Lịch sử tích
                    điểm</a> -->
            </div>

            <!-- Main content -->
            <div class="col-md-9">
                <!-- Nút mở modal -->
                <button class="btn btn-address" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Thêm địa chỉ
                    mới</button>

                <?php if (!empty($addresses)): ?>
                    <?php foreach ($addresses as $address): ?>
                        <?php
                        $fullAddressParts = array_filter([
                            $address->address_detail ?? '',
                            $address->wardName ?? '',
                            $address->districtName ?? '',
                            $address->provinceName ?? ''
                        ]);
                        $fullAddress = implode(', ', $fullAddressParts);
                        ?>
                        <div class="address-card">
                            <h6 class="fw-bold">Tên người nhận: <?= htmlspecialchars($address->name) ?></h6>
                            <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($address->phone) ?></p>
                            <p class="mb-1"><strong>Địa chỉ:</strong> <?= htmlspecialchars($fullAddress) ?></p>

                            <div class="address-actions">
                                <button class="btn btn-sm btn-outline-dark me-2 btn-edit-address" data-id="<?= $address->id ?>"
                                    data-name="<?= htmlspecialchars($address->name) ?>"
                                    data-phone="<?= htmlspecialchars($address->phone) ?>"
                                    data-address="<?= htmlspecialchars($fullAddress) ?>"
                                    data-address_detail="<?= htmlspecialchars($address->address_detail) ?>"
                                    data-province="<?= htmlspecialchars($address->provinceName) ?>"
                                    data-district="<?= htmlspecialchars($address->districtName) ?>"
                                    data-ward="<?= htmlspecialchars($address->wardName) ?>">
                                    Sửa
                                </button>
                                <a href="/webbanhang/address/delete/<?= $address->id ?>" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">Xóa</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Bạn chưa có địa chỉ nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Thêm địa chỉ -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/webbanhang/account/addAddress" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select id="province" name="province" class="form-control" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quận/Huyện</label>
                            <select id="district" name="district" class="form-control" required disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phường/Xã</label>
                            <select id="ward" name="ward" class="form-control" required disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ cụ thể (số nhà, tên đường...)</label>
                            <textarea name="address_detail" class="form-control" rows="2" required></textarea>
                        </div>
                        <!-- Ẩn các input lưu tên tỉnh, huyện, xã -->
                        <input type="hidden" name="provinceName" id="provinceName">
                        <input type="hidden" name="districtName" id="districtName">
                        <input type="hidden" name="wardName" id="wardName">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-dark">Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Sửa địa chỉ -->
    <!-- Modal Sửa địa chỉ -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAddressForm" action="/webbanhang/address/update" method="POST">
                    <input type="hidden" name="id" id="editAddressId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAddressModalLabel">Sửa địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="editPhone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select id="province" name="province" class="form-control" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quận/Huyện</label>
                            <select id="district" name="district" class="form-control" required disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phường/Xã</label>
                            <select id="ward" name="ward" class="form-control" required disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <textarea name="address_detail" id="editAddressDetail" class="form-control" rows="2"
                                required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-dark">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý sự kiện cho nút "Sửa"
            const buttons = document.querySelectorAll('.btn-edit-address');
            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const phone = this.dataset.phone;
                    const address_detail = this.dataset.address_detail;
                    const province = this.dataset.province;
                    const district = this.dataset.district;
                    const ward = this.dataset.ward;

                    // Đổ vào form sửa
                    document.getElementById('editAddressId').value = id;
                    document.getElementById('editName').value = name;
                    document.getElementById('editPhone').value = phone;
                    document.getElementById('editAddressDetail').value = address_detail;

                    // Lấy dữ liệu tỉnh
                    fetch('https://provinces.open-api.vn/api/p/')
                        .then(res => res.json())
                        .then(provinces => {
                            const provinceSelect = document.getElementById('province');
                            provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                            provinces.forEach(p => {
                                const opt = document.createElement('option');
                                opt.value = p.code;
                                opt.text = p.name;
                                provinceSelect.appendChild(opt);
                            });

                            // Set giá trị tỉnh đã chọn
                            provinceSelect.value = province;
                            updateDistricts(province);

                            // Cập nhật khi chọn tỉnh
                            provinceSelect.addEventListener('change', function () {
                                const provinceCode = this.value;
                                document.getElementById('provinceName').value = this.options[this.selectedIndex].text;
                                updateDistricts(provinceCode);
                            });

                            function updateDistricts(provinceCode) {
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

                                        // Set giá trị quận/huyện đã chọn
                                        districtSelect.value = district;
                                        updateWards(district);

                                        districtSelect.addEventListener('change', function () {
                                            const districtCode = this.value;
                                            document.getElementById('districtName').value = this.options[this.selectedIndex].text;
                                            updateWards(districtCode);
                                        });

                                        function updateWards(districtCode) {
                                            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                                                .then(res => res.json())
                                                .then(data => {
                                                    const wardSelect = document.getElementById('ward');
                                                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                                                    wardSelect.disabled = false;

                                                    data.wards.forEach(w => {
                                                        const opt = document.createElement('option');
                                                        opt.value = w.code;
                                                        opt.text = w.name;
                                                        wardSelect.appendChild(opt);
                                                    });

                                                    // Set giá trị phường/xã đã chọn
                                                    wardSelect.value = ward;
                                                    document.getElementById('wardName').value = wardSelect.options[wardSelect.selectedIndex]?.text || '';
                                                });
                                        }
                                    });
                            }
                        });

                    // Mở modal sửa
                    const editModal = new bootstrap.Modal(document.getElementById('editAddressModal'));
                    editModal.show();
                });
            });


            // Xử lý dữ liệu tỉnh, huyện, phường
            fetch('https://provinces.open-api.vn/api/p/')
                .then(res => res.json())
                .then(provinces => {
                    const provinceSelect = document.getElementById('province');
                    provinces.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.code;
                        opt.text = p.name;
                        provinceSelect.appendChild(opt);
                    });

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
        });

    </script>

</body>

</html>
<?php include 'app/views/shares/footer.php'; ?>