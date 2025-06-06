<?php

require_once 'app/config/database.php';

class ReviewModel
{
    private $conn;
    private $table_name = "rating";

    // Khởi tạo kết nối đến cơ sở dữ liệu
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả đánh giá từ cơ sở dữ liệu
    public function getAllRatings($product_name = '', $rating_value = '')
    {
        $query = "SELECT r.id, r.rating_value, r.comment, r.created_at, p.name AS product_name, p.image AS product_image, sz.size AS product_size, a.username AS account_name, a.email AS account_email
              FROM " . $this->table_name . " r
              JOIN order_details od ON r.order_detail_id = od.id
              JOIN product_sizes ps ON od.product_sizes_id = ps.id
              JOIN sizes sz ON sz.id = ps.size_id
              JOIN product p ON ps.product_id = p.id
              JOIN orders o ON od.order_id = o.id  -- Liên kết với bảng orders để lấy account_id
              JOIN account a ON o.account_id = a.id  -- Liên kết với bảng account qua account_id trong bảng orders
              WHERE 1";

        if ($product_name) {
            $query .= " AND p.name LIKE :product_name";
        }

        if ($rating_value) {
            $query .= " AND r.rating_value = :rating_value";
        }

        $stmt = $this->conn->prepare($query);

        if ($product_name) {
            $stmt->bindParam(':product_name', $product_name);
        }

        if ($rating_value) {
            $stmt->bindParam(':rating_value', $rating_value);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }




    public function getReceivedItems($account_id, $reviewed = false)
    {
        $reviewCondition = $reviewed ? "r.rating_value AS rating, r.comment" : "NULL AS rating, NULL AS comment";
        $joinCondition = $reviewed ? "INNER JOIN rating r ON r.order_detail_id = od.id" : "LEFT JOIN rating r ON r.order_detail_id = od.id";
        $ratingCondition = $reviewed ? "" : "AND r.id IS NULL";

        $query = "SELECT 
                od.id AS order_detail_id, 
                p.id AS product_id, 
                p.name AS product_name, 
                p.image, 
                s.size,
                od.price, 
                od.quantity, 
                o.created_at,
                $reviewCondition
            FROM orders o
            INNER JOIN order_details od ON o.id = od.order_id
            INNER JOIN product_sizes ps ON od.product_sizes_id = ps.id
            INNER JOIN product p ON ps.product_id = p.id
            INNER JOIN sizes s ON ps.size_id = s.id
            $joinCondition
            WHERE o.status = 'received' 
              AND o.account_id = :account_id
              $ratingCondition";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Error fetching received items: " . implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreviewedReceivedItems($account_id)
    {
        return $this->getReceivedItems($account_id, false);
    }

    public function getReviewedReceivedItems($account_id)
    {
        return $this->getReceivedItems($account_id, true);
    }



    public function saveReview($order_detail_id, $rating_value, $comment)
    {
        // Kiểm tra rating_value có hợp lệ không
        if ($rating_value < 1 || $rating_value > 5) {
            throw new Exception("Giá trị đánh giá phải từ 1 đến 5.");
        }

        // Kiểm tra comment không rỗng (loại bỏ khoảng trắng đầu/cuối)
        if (trim($comment) === '') {
            throw new Exception("Vui lòng nhập nội dung đánh giá.");
        }

        // Kiểm tra đã có đánh giá cho order_detail_id chưa
        $query = "SELECT id FROM rating WHERE order_detail_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt->execute([$order_detail_id])) {
            throw new Exception("Lỗi kiểm tra đánh giá tồn tại: " . implode(", ", $stmt->errorInfo()));
        }

        $existingReview = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingReview) {
            // Đã tồn tại -> cập nhật đánh giá
            $updateQuery = "UPDATE rating SET rating_value = ?, comment = ?, created_at = NOW() WHERE order_detail_id = ?";
            $updateStmt = $this->conn->prepare($updateQuery);
            if (!$updateStmt->execute([$rating_value, $comment, $order_detail_id])) {
                throw new Exception("Cập nhật đánh giá thất bại: " . implode(", ", $updateStmt->errorInfo()));
            }
            return "Cập nhật đánh giá thành công.";
        } else {
            // Chưa tồn tại -> thêm mới
            $insertQuery = "INSERT INTO rating (rating_value, comment, order_detail_id) VALUES (?, ?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);
            if (!$insertStmt->execute([$rating_value, $comment, $order_detail_id])) {
                throw new Exception("Lưu đánh giá thất bại: " . implode(", ", $insertStmt->errorInfo()));
            }
            return "Gửi đánh giá thành công.";
        }
    }

    public function getReviewsByProductId($product_id)
    {
        $query = "SELECT 
                r.id, 
                r.rating_value, 
                r.comment, 
                r.created_at, 
                p.name AS product_name, 
                p.image AS product_image, 
                sz.size AS product_size, 
                a.username AS account_name, 
                a.email AS account_email
              FROM " . $this->table_name . " r
              JOIN order_details od ON r.order_detail_id = od.id
              JOIN product_sizes ps ON od.product_sizes_id = ps.id
              JOIN sizes sz ON sz.id = ps.size_id
              JOIN product p ON ps.product_id = p.id
              JOIN orders o ON od.order_id = o.id
              JOIN account a ON o.account_id = a.id
              WHERE p.id = :product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



}
?>