<?php

class SearchController
{
    private $conn;

    // Khởi tạo controller với kết nối cơ sở dữ liệu
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function index()
    {
        // Kiểm tra nếu có từ khóa tìm kiếm
        $query = isset($_GET['query']) ? $_GET['query'] : '';

        // Nếu không có từ khóa, yêu cầu người dùng nhập từ khóa
        if (empty($query)) {
            echo "Vui lòng nhập từ khóa tìm kiếm.";
            return;
        }

        // Lấy dữ liệu từ model
        $productModel = new ProductModel($this->conn);
        $results = $productModel->searchProducts($query);

        // Nếu có kết quả, trả về view, nếu không, hiển thị thông báo
        if (empty($results)) {
            echo "Không tìm thấy sản phẩm nào.";
        } else {
            // Truyền kết quả sang view
            include 'app/views/product/search_results.php';
        }
    }
}
?>

