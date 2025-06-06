<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/OrderModel.php');
require_once('app/models/SizeModel.php');
require_once('app/models/AccountModel.php');
require_once('app/models/ReviewModel.php');
require_once 'app/helpers/SessionHelper.php';

class AdminController
{

    private $productModel;
    private $orderModel;
    private $accountModel;
    private $reviewModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->orderModel = new OrderModel();
        $this->accountModel = new AccountModel($this->db);
        $this->reviewModel = new ReviewModel();

    }

    public function index()
    {

        include __DIR__ . '/../views/admin/index.html';

    }


    // Trang quản lý sản phẩm
    public function products()
    {
        $search = isset($_GET['search']) ? $_GET['search'] : '';  // Lấy từ khóa tìm kiếm từ GET

        // Lấy danh sách sản phẩm từ ProductModel, truyền tham số $search vào để lọc
        $products = $this->productModel->getProducts($search);

        // Bao gồm view và truyền dữ liệu sản phẩm vào
        include __DIR__ . '/../views/admin/products.php';
    }


    public function orders()
    {
        $orders = $this->orderModel->getAllOrders();

        include __DIR__ . '/../views/admin/orders.php';
    }

    public function accounts()
    {
        // Khởi tạo model AccountModel
        $accountModel = new AccountModel($this->db);

        // Lấy tham số GET từ URL
        $roleFilter = isset($_GET['role']) ? $_GET['role'] : '';
        $emailFilter = isset($_GET['email']) ? $_GET['email'] : '';
        $nameFilter = isset($_GET['name']) ? $_GET['name'] : '';

        // Lấy danh sách tài khoản với các bộ lọc
        $accounts = $accountModel->getAllAccounts($roleFilter, $emailFilter, $nameFilter);

        // Truyền dữ liệu vào view
        include 'app/views/admin/accounts.php';
    }




    // Hiển thị form thêm sản phẩm
    public function addProduct()
    {

        $categories = (new CategoryModel($this->db))->getCategories();
        $sizes = (new SizeModel($this->db))->getSizes(); // Lấy danh sách kích thước
        $productSizes = []; // Khởi tạo biến $productSizes nếu chưa có dữ liệu

        include_once 'app/views/admin/addProduct.php';
    }

    public function save()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $sizes = $_POST['sizes'] ?? []; // Lấy các kích thước đã chọn
            $image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
                ? $this->uploadImage($_FILES['image'])
                : "";

            // Thêm sản phẩm vào cơ sở dữ liệu
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                // Nếu có lỗi, trả về thông báo lỗi và danh sách category
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                $sizes = (new SizeModel($this->db))->getSizes(); // Lấy danh sách kích thước
                include 'app/views/admin/addProduct.php';
            } else {
                // Lưu các kích thước cho sản phẩm
                $product_id = $this->db->lastInsertId(); // Lấy ID của sản phẩm vừa thêm
                foreach ($sizes as $size_id) {
                    $this->productModel->addProductSize($product_id, $size_id); // Thêm các kích thước vào bảng product_sizes
                }
                header('Location: /webbanhang/admin/addProduct');
            }
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "public/images/product/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $imageName = basename($file["name"]);
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $imageName;
    }
    public function editProduct($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        $sizes = (new SizeModel($this->db))->getSizes(); // Lấy danh sách kích thước
        $productSizes = $this->productModel->getProductSizes($id); // Lấy danh sách kích thước hiện tại của sản phẩm
        if ($product) {
            include 'app/views/admin/editProduct.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }
    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $sizes = $_POST['sizes'] ?? []; // Lấy các kích thước đã chọn
            $image = (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
                ? $this->uploadImage($_FILES['image'])
                : $_POST['existing_image'];

            // Cập nhật thông tin sản phẩm
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

            if ($edit) {
                // Cập nhật các kích thước cho sản phẩm
                $this->productModel->deleteProductSizes($id); // Xóa các kích thước cũ
                foreach ($sizes as $size_id) {
                    $this->productModel->addProductSize($id, $size_id); // Thêm các kích thước mới vào bảng product_sizes
                }
                header('Location: /webbanhang/admin/products');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }
    public function deleteProduct($id)
    {

        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/admin/products');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;

            $order = $this->orderModel->getOrderById($order_id);

            if (!$order) {
                echo "Không tìm thấy đơn hàng!";
                return;
            }


            // Cập nhật trạng thái đơn hàng thành cancelled
            $this->orderModel->updateOrderStatus($order_id, 'confirmed');

            // Quay lại trang danh sách đơn hàng
            header("Location: /webbanhang/admin/orders");
            exit();
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }

    public function shipping()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;

            $order = $this->orderModel->getOrderById($order_id);

            if (!$order) {
                echo "Không tìm thấy đơn hàng!";
                return;
            }


            // Cập nhật trạng thái đơn hàng thành cancelled
            $this->orderModel->updateOrderStatus($order_id, 'shipping');

            // Quay lại trang danh sách đơn hàng
            header("Location: /webbanhang/admin/orders");
            exit();
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }

    public function delivered()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;

            $order = $this->orderModel->getOrderById($order_id);

            if (!$order) {
                echo "Không tìm thấy đơn hàng!";
                return;
            }


            // Cập nhật trạng thái đơn hàng thành cancelled
            $this->orderModel->updateOrderStatus($order_id, 'delivered');

            // Quay lại trang danh sách đơn hàng
            header("Location: /webbanhang/admin/orders");
            exit();
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }

    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;

            $order = $this->orderModel->getOrderById($order_id);

            if (!$order) {
                echo "Không tìm thấy đơn hàng!";
                return;
            }


            // Cập nhật trạng thái đơn hàng thành cancelled
            $this->orderModel->updateOrderStatus($order_id, 'cancelled');

            // Quay lại trang danh sách đơn hàng
            header("Location: /webbanhang/admin/orders");
            exit();
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }

    public function order_detail($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);


        if (!$order) {
            echo "Đơn hàng không tồn tại!";
            return;
        }

        $orderDetails = $this->orderModel->getOrderDetails($order_id);

        include 'app/views/admin/order_detail.php';
    }

    public function editAccount($id)
    {
        // Lấy thông tin tài khoản từ model
        $account = $this->accountModel->getAccountById($id);

        // Kiểm tra xem tài khoản có tồn tại hay không
        if (!$account) {
            // Nếu không tìm thấy tài khoản, chuyển hướng về trang danh sách tài khoản
            header('Location: /webbanhang/admin/accounts');
            exit;
        }

        // Hiển thị form chỉnh sửa
        require_once(__DIR__ . '/../views/admin/editAccount.php');

    }

    public function createAccount()
    {
        include 'app/views/admin/createAccount.php';
    }


    public function saveAccount()
    {
        // Kiểm tra nếu có dữ liệu từ form (email, username, password, role)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user'; // Mặc định là 'user'

            // Gọi phương thức createAccount từ AccountModel
            $result = $this->accountModel->save($email, $username, $password, $role);

            if ($result) {
                // Thành công, chuyển hướng tới trang quản lý tài khoản hoặc thông báo thành công
                echo "Tạo tài khoản thành công!";
            } else {
                // Lỗi nếu tài khoản đã tồn tại
                echo "Email đã tồn tại, vui lòng chọn email khác.";
            }
        }
    }

    public function updateAccount($id)
    {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $role = $_POST['role'];

        $result = $this->accountModel->updateAccount($id, $email, $username, $role);

        if ($result) {
            // Chuyển hướng về trang danh sách tài khoản nếu thành công
            header('Location: /webbanhang/admin/accounts');
            exit;
        } else {
            echo "Lỗi cập nhật tài khoản.";
        }
    }


    public function deleteAccount($id)
    {
        $result = $this->accountModel->deleteAccount($id);

        if ($result) {
            // Chuyển hướng về trang danh sách tài khoản sau khi xóa thành công
            header('Location: /webbanhang/admin/accounts');
            exit;
        } else {
            echo "Lỗi xóa tài khoản.";
        }
    }

    public function accountOrders($id)
    {
        $accountOrders = $this->orderModel->getOrdersByAccountId($id);
        $account = $this->accountModel->getAccountById($id);

        include 'app/views/admin/accountOrders.php';

    }
    // Trang quản lý danh mục
    public function categories()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include __DIR__ . '/../views/admin/categories.php';
    }

    // Thêm danh mục
    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $model = new CategoryModel($this->db);
            $model->addCategory($name, $description);
            header('Location: /webbanhang/admin/categories');
            exit();
        }
    }

    // Sửa danh mục
    public function editCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $model = new CategoryModel($this->db);
            $model->updateCategory($id, $name, $description);
            header('Location: /webbanhang/admin/categories');
            exit();
        }
    }

    // Xóa danh mục
    public function deleteCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $model = new CategoryModel($this->db);
            $model->deleteCategory($id);
            header('Location: /webbanhang/admin/categories');
            exit();
        }
    }

    // Trang quản lý Sizes
    public function sizes()
    {
        $sizes = (new SizeModel($this->db))->getSizes();
        include __DIR__ . '/../views/admin/sizes.php';
    }
    // Thêm size
    public function addSize()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $size = $_POST['size'] ?? '';
            $model = new SizeModel($this->db);
            $model->addSize($size);
            header('Location: /webbanhang/admin/sizes');
            exit();
        }
    }

    // Sửa size
    public function editSize()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $size = $_POST['size'] ?? '';
            $model = new SizeModel($this->db);
            $model->updateSize($id, $size);
            header('Location: /webbanhang/admin/sizes');
            exit();
        }
    }

    // Xóa size
    public function deleteSize()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $model = new SizeModel($this->db);
            $model->deleteSize($id);
            header('Location: /webbanhang/admin/sizes');
            exit();
        }
    }

    public function reviews()
    {
        // Lấy các tham số lọc từ URL
        $product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';
        $rating_value = isset($_GET['rating_value']) ? $_GET['rating_value'] : '';

        // Gọi hàm getAllRatings() từ model với các tham số lọc
        $ratings = $this->reviewModel->getAllRatings($product_name, $rating_value);

        // Chuyển tới view để hiển thị danh sách đánh giá
        include 'app/views/admin/ratings.php';
    }


}
?>