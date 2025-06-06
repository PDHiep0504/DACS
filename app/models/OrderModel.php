<?php

require_once 'app/config/database.php';

class OrderModel
{
    private $conn;

    // Khởi tạo kết nối đến cơ sở dữ liệu
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy danh sách đơn hàng của một người dùng
    public function getOrdersByAccountId($account_id)
    {
        $query = "SELECT * FROM orders WHERE account_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute([$account_id])) {
            throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ); // ✅ Trả về danh sách object
    }


    // Lấy chi tiết đơn hàng theo ID
    public function getOrderById($id)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Kiểm tra thực thi câu lệnh
        if (!$stmt->execute([$id])) {
            throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
        }

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ? $order : null;
    }

    // Thêm đơn hàng mới
    public function createOrder($name, $phone, $address, $account_id)
    {
        $query = "INSERT INTO orders (name, phone, address, status, account_id) VALUES (?, ?, ?, 'pending', ?)";
        $stmt = $this->conn->prepare($query);

        // Kiểm tra thực thi câu lệnh
        if (!$stmt->execute([$name, $phone, $address, $account_id])) {
            throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
        }

        return $this->conn->lastInsertId();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($order_id, $status)
    {
        // SQL query
        $query = "UPDATE orders SET status = :status WHERE id = :order_id";

        // Chuẩn bị câu lệnh
        $stmt = $this->conn->prepare($query);

        // Liên kết tham số với câu lệnh SQL
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        // Ghi lại câu truy vấn vào log (để kiểm tra)
        error_log("Executing SQL: " . $query . " with status: " . $status . " and order_id: " . $order_id);

        // Thực thi câu lệnh và kiểm tra kết quả
        if (!$stmt->execute()) {
            // Trường hợp có lỗi, ném ngoại lệ với thông báo chi tiết
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . $errorInfo[2]);  // Ghi thông tin lỗi vào log
            throw new Exception("Error executing query: " . $errorInfo[2]);
        }

        // Kiểm tra xem có bản ghi nào bị thay đổi không
        if ($stmt->rowCount() == 0) {
            throw new Exception("No rows were updated. The order might not exist or its status is already set to: " . $status);
        }
    }
    // Xóa đơn hàng
    public function deleteOrder($order_id)
    {
        $query = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Kiểm tra thực thi câu lệnh
        if (!$stmt->execute([$order_id])) {
            throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
        }
    }

    public function getOrdersByAccountIdAndStatus($account_id, $status)
    {
        $query = "SELECT * FROM orders WHERE account_id = ? AND status = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute([$account_id, $status])) {
            throw new Exception("Error executing query: " . implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ); // ✅
    }

    // Lấy danh sách sản phẩm trong đơn hàng (order details)
    public function getOrderDetails($order_id)
    {
        $query = "SELECT od.*, p.name as product_name, p.image, s.size
              FROM order_details od
              JOIN product_sizes ps ON od.product_sizes_id = ps.id
              JOIN product p ON ps.product_id = p.id
              JOIN sizes s ON ps.size_id = s.id
              WHERE od.order_id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute([$order_id])) {
            throw new Exception("Lỗi truy vấn chi tiết đơn hàng: " . implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả đơn hàng
    public function getAllOrders()
    {
        $query = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);

        if (!$stmt->execute()) {
            throw new Exception("Lỗi truy vấn: " . implode(", ", $stmt->errorInfo()));
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



}
?>