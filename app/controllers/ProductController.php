<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/SizeModel.php');
require_once('app/models/AddressModel.php');
require_once('app/models/ReviewModel.php');
require_once 'app/helpers/SessionHelper.php';

class ProductController
{
    private $productModel;
    private $reviewModel;
    private $addressModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->reviewModel = new ReviewModel();
        $this->addressModel = new AddressModel($this->db);
    }

    private function isAdmin()
    {
        return SessionHelper::isAdmin();
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {

        $productSizes = $this->productModel->getProductSizes($id);
        $productReviews = $this->reviewModel->getReviewsByProductId($id);


        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        $categories = (new CategoryModel($this->db))->getCategories();
        $sizes = (new SizeModel($this->db))->getSizes(); // Lấy danh sách kích thước
        $productSizes = []; // Khởi tạo biến $productSizes nếu chưa có dữ liệu

        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
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
                include 'app/views/product/add.php';
            } else {
                // Lưu các kích thước cho sản phẩm
                $product_id = $this->db->lastInsertId(); // Lấy ID của sản phẩm vừa thêm
                foreach ($sizes as $size_id) {
                    $this->productModel->addProductSize($product_id, $size_id); // Thêm các kích thước vào bảng product_sizes
                }
                header('Location: /webbanhang/Product');
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

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        $sizes = (new SizeModel($this->db))->getSizes(); // Lấy danh sách kích thước
        $productSizes = $this->productModel->getProductSizes($id); // Lấy danh sách kích thước hiện tại của sản phẩm
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
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
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }



    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        $sizeId = isset($_POST['size_id']) ? $_POST['size_id'] : null;  // Lấy size_id từ form
        $sizeName = isset($_POST['size_name']) ? $_POST['size_name'] : 'Chưa chọn size';  // Lấy size_name từ form

        if (!$sizeId) {
            echo "Vui lòng chọn size.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            // Kiểm tra nếu size đã có trong giỏ hàng rồi, tăng số lượng
            if (isset($_SESSION['cart'][$id]['sizes'][$sizeId])) {
                $_SESSION['cart'][$id]['sizes'][$sizeId]['quantity']++;
            } else {
                // Thêm size mới vào giỏ hàng
                $_SESSION['cart'][$id]['sizes'][$sizeId] = [
                    'size_name' => $sizeName,
                    'quantity' => 1
                ];
            }
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'sizes' => [
                    $sizeId => [
                        'size_name' => $sizeName,
                        'quantity' => 1
                    ]
                ]
            ];
        }

        $_SESSION['message_addToCart'] = 'Thêm vào giỏ hàng thành công !!!';
        header('Location: /webbanhang/Product/show/' . $id . '?added=1');
    }



    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
            $selectedItems = [];
            $cart = $_SESSION['cart'] ?? [];

            foreach ($_POST['selected_products'] as $entry) {
                list($productId, $size) = explode('|', $entry);

                if (isset($cart[$productId]['sizes'][$size])) {
                    $item = $cart[$productId];

                    $selectedItems[$productId]['name'] = $item['name'];
                    $selectedItems[$productId]['price'] = $item['price'];
                    $selectedItems[$productId]['image'] = $item['image'];
                    $selectedItems[$productId]['sizes'][$size] = $item['sizes'][$size];
                }
            }

            if (empty($selectedItems)) {
                echo "Không có sản phẩm nào được chọn để thanh toán.";
                return;
            }

            $_SESSION['checkout_items'] = $selectedItems;

            $accountId = SessionHelper::getAccountId();
            $addresses = $this->addressModel->getAddressesByAccountId($accountId);

            include 'app/views/product/checkout.php';
        } else {
            echo "Không có sản phẩm nào được chọn để thanh toán.";
        }
    }



    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address_id = $_POST['address_id'];
            $payment_method = $_POST['payment_method']; // Get payment method
            // Kiểm tra xem người dùng có chọn địa chỉ đã lưu không
            if (!empty($address_id)) {
                // Lấy địa chỉ đã lưu từ database dựa trên address_id
                $addressId = (int) $address_id;
                $query = "SELECT * FROM address WHERE id = :address_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':address_id', $addressId);
                $stmt->execute();
                $addr = $stmt->fetch();

                if ($addr) {
                    // Sử dụng địa chỉ đã lưu
                    $name = $addr['name'];
                    $phone = $addr['phone'];
                    $province = $addr['provinceName'] ?? '';
                    $district = $addr['districtName'] ?? '';
                    $ward = $addr['wardName'] ?? '';
                    $addressDetail = $addr['address_detail'] ?? '';
                    $address = "$province -- $district -- $ward -- $addressDetail";
                } else {
                    echo "Địa chỉ không hợp lệ.";
                    return;
                }
            } else {
                // Nếu không có địa chỉ đã lưu, lấy thông tin từ form nhập
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $province = $_POST['provinceName'] ?? '';
                $district = $_POST['districtName'] ?? '';
                $ward = $_POST['wardName'] ?? '';
                $addressDetail = $_POST['address'] ?? '';
                $address = "$province -- $district -- $ward -- $addressDetail";
            }

            // Kiểm tra nếu người dùng đã đăng nhập và có account_id
            $account_id_raw = SessionHelper::getAccountId();
            if ($account_id_raw === null) {
                echo "Bạn cần đăng nhập để thực hiện thanh toán.";
                return;
            }
            $account_id = (int) $account_id_raw;

            if (!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])) {
                echo "Không có sản phẩm nào để thanh toán.";
                return;
            }

            $totalAmount = (float) $_POST['total_amount'] ?? 0;
            $this->db->beginTransaction();
            try {
                $query = "INSERT INTO orders (name, phone, address, account_id, total_amount, payment_method) 
                      VALUES (:name, :phone, :address, :account_id, :total_amount, :payment_method)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':account_id', $account_id);
                $stmt->bindParam(':total_amount', $totalAmount);
                $stmt->bindParam(':payment_method', $payment_method); // Bind payment method
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // Lưu sản phẩm vào order_details với product_sizes_id
                $items = $_SESSION['checkout_items'];
                foreach ($items as $productId => $item) {
                    foreach ($item['sizes'] as $sizeId => $size) {
                        // Truy xuất product_sizes_id từ bảng product_sizes
                        $query = "SELECT id FROM product_sizes WHERE product_id = :product_id AND size_id = :size_id";
                        $stmt = $this->db->prepare($query);
                        $stmt->bindParam(':product_id', $productId);
                        $stmt->bindParam(':size_id', $sizeId);
                        $stmt->execute();
                        $productSize = $stmt->fetch();

                        if ($productSize) {
                            $product_sizes_id = $productSize['id'];

                            // Chèn thông tin vào order_details
                            $query = "INSERT INTO order_details (order_id, product_sizes_id, quantity, price) 
                                  VALUES (:order_id, :product_sizes_id, :quantity, :price)";
                            $stmt = $this->db->prepare($query);
                            $stmt->bindParam(':order_id', $order_id);
                            $stmt->bindParam(':product_sizes_id', $product_sizes_id);
                            $stmt->bindParam(':quantity', $size['quantity']);
                            $stmt->bindParam(':price', $item['price']);
                            $stmt->execute();
                        }
                    }

                    // Gỡ sản phẩm khỏi giỏ hàng
                    unset($_SESSION['cart'][$productId]);
                }

                unset($_SESSION['checkout_items']);

                switch ($payment_method) {
                    case 'cod':
                        // Lưu thông tin đơn hàng vào session
                        $_SESSION['checkout_name'] = $name;
                        $_SESSION['checkout_phone'] = $phone;
                        $_SESSION['checkout_address'] = $address;
                        $_SESSION['checkout_total_amount'] = $totalAmount;
                        $_SESSION['checkout_payment_method'] = $payment_method;

                        $this->db->commit();
                        header('Location: /webbanhang/Product/orderConfirmation?order_id=' . $order_id);
                        exit;

                    case 'bank':
                        $this->db->commit(); // Vẫn commit đơn hàng để lấy order_id
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        // CHUẨN BỊ GỬI TỚI VNPAY
                        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                        $vnp_Returnurl = "http://localhost/webbanhang/Product/vnpay_return"; // Cập nhật đúng URL return
                        $vnp_TmnCode = "A363RUJR";
                        $vnp_HashSecret = "SSF8TV6GMOD2CZ6ZU0KQYVFZMLFEHWMG";

                        $vnp_TxnRef = $order_id; // Mã đơn hàng trong DB
                        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order_id;
                        $vnp_OrderType = 'billpayment';
                        $vnp_Amount = $totalAmount * 100;
                        $vnp_Locale = 'vn';
                        $vnp_BankCode = ''; // Cho phép chọn ngân hàng sau
                        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
                        $vnp_CreateDate = date('YmdHis');

                        $inputData = array(
                            "vnp_Version" => "2.1.0",
                            "vnp_TmnCode" => $vnp_TmnCode,
                            "vnp_Amount" => $vnp_Amount,
                            "vnp_Command" => "pay",
                            "vnp_CreateDate" => $vnp_CreateDate,
                            "vnp_CurrCode" => "VND",
                            "vnp_IpAddr" => $vnp_IpAddr,
                            "vnp_Locale" => $vnp_Locale,
                            "vnp_OrderInfo" => $vnp_OrderInfo,
                            "vnp_OrderType" => $vnp_OrderType,
                            "vnp_ReturnUrl" => $vnp_Returnurl,
                            "vnp_TxnRef" => $vnp_TxnRef
                        );

                        // Build query and hash
                        ksort($inputData);
                        $query = "";
                        $hashdata = "";
                        $i = 0;
                        foreach ($inputData as $key => $value) {
                            $hashdata .= ($i ? '&' : '') . urlencode($key) . "=" . urlencode($value);
                            $query .= urlencode($key) . "=" . urlencode($value) . '&';
                            $i++;
                        }

                        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                        $vnp_Url = $vnp_Url . "?" . $query . 'vnp_SecureHash=' . $vnp_SecureHash;

                        // Xóa checkout_items (giữ lại cart nếu cần xác nhận thanh toán)
                        unset($_SESSION['checkout_items']);

                        header('Location: ' . $vnp_Url);
                        exit;

                    default:
                        echo "Phương thức thanh toán không hợp lệ.";
                        return;
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }



        }
    }
    public function vnpay_return()
    {
        include 'app/views/vnpay/vnpay_return.php';
    }


    public function orderConfirmation()
    {
        $_SESSION['message'] = 'Đặt hàng thành công! Chúng tôi sẽ liên hệ bạn sớm.';
        include 'app/views/product/orderConfirmation.php';
    }

    public function category($categoryId)
    {
        // Get the category name
        $categoryModel = new CategoryModel($this->db);
        $category = $categoryModel->getCategoryById($categoryId);
        
        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }
        
        // Get products in this category
        $products = $this->productModel->getProductsByCategory($categoryId);
        
        // Load the category view
        include 'app/views/product/category.php';
    }
}
?>