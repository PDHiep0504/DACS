<?php
class CartController {
    public function index() {
        $file = __DIR__ . '/../views/product/cart.php'; // Đảm bảo đường dẫn tuyệt đối
        if (file_exists($file)) {
            include $file;
        } else {
            die("Lỗi: Không tìm thấy file cart.php trong thư mục views.");
        }
    }
}