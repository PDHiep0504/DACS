<?php
session_start();

// Đảm bảo rằng bạn đã bao gồm lớp kết nối cơ sở dữ liệu
require_once 'app/config/Database.php';

// Bao gồm các model và helpers cần thiết
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';



// Định nghĩa đường dẫn hình ảnh
define('IMAGE_PATH', 'public/images/');
define('IMAGE_PATH_Banner', IMAGE_PATH . 'banner/');
define('IMAGE_PATH_Product', IMAGE_PATH . 'product/');


// Tạo đối tượng Database và kết nối
$db = new Database();
$conn = $db->getConnection();

// Lấy URL từ tham số query string
$url = $_GET['url'] ?? '';  // Nếu không có URL thì mặc định là rỗng
$url = rtrim($url, '/');  // Xóa dấu '/' thừa ở cuối URL
$url = filter_var($url, FILTER_SANITIZE_URL);  // Làm sạch URL
$urlParts = explode('/', $url);  // Phân tách URL thành các phần

// Xác định controller từ phần đầu tiên của URL
$controllerName = isset($urlParts[0]) && $urlParts[0] != '' ? ucfirst($urlParts[0]) . 'Controller' : 'ProductController';

// Xác định action từ phần thứ hai của URL
$action = isset($urlParts[1]) && $urlParts[1] != '' ? $urlParts[1] : 'index';

// Kiểm tra xem controller có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found');
}

// Bao gồm controller
require_once 'app/controllers/' . $controllerName . '.php';

// Tạo đối tượng controller và truyền kết nối cơ sở dữ liệu vào constructor
$controller = new $controllerName($conn);  // Truyền đối tượng kết nối vào constructor

// Kiểm tra xem action có tồn tại trong controller không
if (!method_exists($controller, $action)) {
    die('Action not found');
}

// Lấy các tham số còn lại nếu có
$params = array_slice($urlParts, 2);

// Gọi action với các tham số
call_user_func_array([$controller, $action], $params);

