<?php

require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';

class OrderController
{
    private $conn;
    private $orderModel;
    private $accountModel;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        $this->orderModel = new OrderModel($this->conn);
        $this->accountModel = new AccountModel($this->conn);
    }

    // Hiển thị danh sách đơn hàng của người dùng
    public function index()
    {
        $account_id = SessionHelper::getAccountId();
        $account = $this->accountModel->getAccountById(id: $account_id);

        $status = $_GET['status'] ?? '';

        if ($status && in_array($status, ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'])) {
            $orders = $this->orderModel->getOrdersByAccountIdAndStatus($account_id, $status);
        } else {
            $orders = $this->orderModel->getOrdersByAccountId($account_id);
        }

        $account = $this->accountModel->getAccountById($account_id);

        include 'app/views/order/orders.php';
    }

    // Hiển thị chi tiết đơn hàng
    public function detail($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        

        if (!$order) {
            echo "Đơn hàng không tồn tại!";
            return;
        }

        $orderDetails = $this->orderModel->getOrderDetails($order_id);

        include 'app/views/order/detail.php';
    }

    // Thêm đơn hàng mới
    public function create($name, $phone, $address)
    {
        $account_id = SessionHelper::getAccountId();

        if (!SessionHelper::isLoggedIn() || !$account_id) {
            header("Location: /login");
            exit();
        }

        $order_id = $this->orderModel->createOrder($name, $phone, $address, $account_id);

        if ($order_id) {
            header("Location: /orders/index");
            exit();
        } else {
            echo "Không thể tạo đơn hàng!";
        }
    }

    // Cập nhật trạng thái đơn hàng (chỉ cho admin)
    public function updateStatus($order_id, $status)
    {
        if (!SessionHelper::isAdmin()) {
            echo "Không có quyền thay đổi trạng thái đơn hàng!";
            return;
        }

        $this->orderModel->updateOrderStatus($order_id, $status);

        header("Location: /orders/view/{$order_id}");
        exit();
    }

    // Xóa đơn hàng (chỉ chủ đơn hàng mới được xóa)
    public function delete($order_id)
    {
        $account_id = SessionHelper::getAccountId();
        $order = $this->orderModel->getOrderById($order_id);

        if (!$order || $order['account_id'] != $account_id) {
            echo "Không có quyền xóa đơn hàng này!";
            return;
        }

        $this->orderModel->deleteOrder($order_id);

        header("Location: /orders/index");
        exit();
    }
    // Hủy đơn hàng (chỉ khi đơn đang chờ xác nhận)
    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;
            $account_id = SessionHelper::getAccountId();

            if (!$order_id || !$account_id) {
                echo "Yêu cầu không hợp lệ!";
                return;
            }

            $order = $this->orderModel->getOrderById($order_id);

            if (!$order) {
                echo "Không tìm thấy đơn hàng!";
                return;
            }

            // Kiểm tra đơn có thuộc người dùng và còn ở trạng thái chờ xác nhận không
            if ($order['account_id'] != $account_id) {
                echo "Bạn không có quyền hủy đơn hàng này!";
                return;
            }

            if ($order['status'] !== 'pending') {
                echo "Chỉ có thể hủy các đơn hàng đang chờ xác nhận!";
                return;
            }

            // Cập nhật trạng thái đơn hàng thành cancelled
            $this->orderModel->updateOrderStatus($order_id, 'cancelled');

            // Quay lại trang danh sách đơn hàng
            header("Location: /webbanhang/order");
            exit();
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }
    // OrderController.php

    public function confirm_received()
    {
        if (!isset($_POST['order_id'])) {
            $_SESSION['error'] = 'Không có ID đơn hàng. Vui lòng thử lại.';
            header('Location: /webbanhang/order');
            exit;
        }

        $orderId = $_POST['order_id'];

        try {
            // Khởi tạo mô hình OrderModel
            $orderModel = new OrderModel();

            // Lấy đơn hàng từ cơ sở dữ liệu
            $order = $orderModel->getOrderById($orderId);

            if (!$order) {
                $_SESSION['error'] = 'Đơn hàng không tồn tại hoặc đã bị xóa.';
                header('Location: /webbanhang/order');
                exit;
            }

            // Nếu $order là đối tượng, sử dụng cú pháp đối tượng
            if ($order['status'] !== 'delivered') {
                $_SESSION['error'] = 'Chỉ có đơn hàng đã giao mới có thể được xác nhận là đã nhận.';
                header('Location: /webbanhang/order');
                exit;
            }

            // Cập nhật trạng thái đơn hàng
            $orderModel->updateOrderStatus($orderId, 'received');

            $_SESSION['success'] = 'Đơn hàng đã được xác nhận là đã nhận.';
            header('Location: /webbanhang/order?status=received');
            exit;

        } catch (Exception $e) {
            // Ghi lỗi vào log nếu cần
            error_log('Error in confirm_received: ' . $e->getMessage());

            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            header('Location: /webbanhang/order');
            exit;
        }
    }


    

}
