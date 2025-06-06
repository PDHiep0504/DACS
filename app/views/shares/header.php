<?php
// Kiểm tra nếu giỏ hàng tồn tại trong session
$totalQuantity = 0;
if (isset($_SESSION['cart'])) {
    // Duyệt qua các sản phẩm trong giỏ hàng và tính tổng số lượng
    foreach ($_SESSION['cart'] as $productId => $productDetails) {
        foreach ($productDetails['sizes'] as $sizeId => $sizeDetails) {
            $totalQuantity += $sizeDetails['quantity'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimodo Sport - Thể thao & Quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Các kiểu CSS bạn đã dùng */
        .header-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 40px;
            background-color: #000;
            color: white;
            height: 100px;
        }

        .logo img {
            width: 300px;
            height: auto;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
            flex-grow: 1;
            justify-content: flex-end;
            margin-right: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .nav-links a:hover {
            color: #f39c12;
        }

        .cart i {
            font-size: 1.8rem;
        }

        .cart {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -20px;
            /* Điều chỉnh giá trị này để ô lên cao hơn */
            right: -10px;
            /* Điều chỉnh giá trị này để ô ra ngoài giỏ hàng */
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            /* Giảm kích thước padding để ô nhỏ lại */
            font-size: 0.9rem;
            /* Giảm kích thước chữ để ô nhỏ lại */
        }



        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
        }

        .search-box {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .search-box input {
            border: none;
            padding: 10px;
            border-radius: 4px 0 0 4px;
            outline: none;
            width: 250px;
        }

        .search-box button {
            background: #2a66b8;
            border: none;
            color: white;
            padding: 10px 12px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        .search-box button:hover {
            background: #1f4a8a;
        }

        .account-dropdown .dropdown-toggle {
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .account-dropdown .dropdown-toggle i {
            font-size: 1.8rem;
        }

        .dropdown-menu {
            background-color: whitesmoke;
            border: 1px solid black;
        }

        .dropdown-item:hover {
            background-color: cyan;
            color: black;
        }
    </style>
</head>

<body>
    <header class="header-container">
        <div class="logo">
            <img src="/webbanhang/public/images/logo/dimodo-sport.png" alt="Logo">
        </div>
        <div class="nav-links">
            <a href="/webbanhang/product/">Trang chủ</a>
            <a href="/webbanhang/shares/about">Giới thiệu</a>
            <!-- <a href="#">Áo & Quần bóng đá</a>
            <a href="#">Giày</a>
            <a href="#">Phụ kiện</a> -->
            <div class="search-wrapper">
                <button class="search-icon" onclick="toggleSearch()">
                    <i class="bi bi-search"></i>
                </button>
                <div class="search-box" id="searchBox">
                    <input type="text" placeholder="Tìm kiếm..." id="searchInput">
                    <button type="button"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="cart">
                <a href="/webbanhang/product/cart" class="position-relative">
                    <i class="bi bi-cart2"></i>
                    <span class="cart-count">
                        <?php echo $totalQuantity > 0 ? $totalQuantity : '0'; ?>
                    </span>
                </a>
            </div>
        </div>
        <div class="account-dropdown">
            <a class="dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Tài khoản"; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="accountDropdown">
                <?php if (isset($_SESSION['username'])) { ?>
                    <li><a class="dropdown-item" href="/webbanhang/account/profile">Thông tin tài khoản</a></li>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <li><a class="dropdown-item" href="/webbanhang/admin">Quản Lý (Admin)</a></li>
                    <?php } ?>
                    <li><a class="dropdown-item" href="/webbanhang/account/logout">Đăng xuất</a></li>
                <?php } else { ?>
                    <li><a class="dropdown-item" href="/webbanhang/account/login">Đăng nhập</a></li>
                    <li><a class="dropdown-item" href="/webbanhang/account/register">Đăng ký</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSearch() {
            const searchBox = document.getElementById('searchBox');
            searchBox.style.display = (searchBox.style.display === 'flex') ? 'none' : 'flex';
            if (searchBox.style.display === 'flex') {
                document.getElementById('searchInput').focus();
            }
        }

        document.addEventListener('click', (event) => {
            if (!document.querySelector('.search-wrapper').contains(event.target)) {
                document.getElementById('searchBox').style.display = 'none';
            }
        });

        document.getElementById('searchInput').addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                search();
            }
        });

        document.querySelector('.search-box button').addEventListener('click', search);

        function search() {
            const query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.location.href = `/webbanhang/search?query=${encodeURIComponent(query)}`;
            }
        }
    </script>
</body>

</html>