<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/AddressModel.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
class AccountController
{
    private $accountModel;
    private $addressModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->addressModel = new AddressModel($this->db);
    }

    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    public function save()
    {
        $errors = [];
        $successMessage = "";

        // Kiểm tra username
        if (empty($_POST['username'])) {
            $errors[] = 'Username is required.';
        }

        // Kiểm tra email
        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format.';
        }

        // Kiểm tra mật khẩu và xác nhận mật khẩu
        if (empty($_POST['password']) || empty($_POST['confirmpassword'])) {
            $errors[] = 'Password and confirm password are required.';
        } elseif ($_POST['password'] !== $_POST['confirmpassword']) {
            $errors[] = 'Passwords do not match.';
        }

        // Nếu không có lỗi, lưu tài khoản
        if (empty($errors)) {
            $result = $this->accountModel->save(
                $email,
                $_POST['username'],
                $_POST['password']
            );

            if ($result) {
                $successMessage = "Registration successful! You can now log in.";
            } else {
                $errors[] = "Registration failed. Email may already exist.";
            }
        }

        include 'app/views/account/register.php';
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['email']);
        unset($_SESSION['username']);
        unset($_SESSION['address']);
        unset($_SESSION['role']);
        header('Location:/webbanhang/product');
        exit;
    }

    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->getAccountByEmail($email);

            if ($account && password_verify($password, $account->password)) {
                // Tạo JWT
                require_once 'app/utils/JWTHandler.php';
                $jwtHandler = new JWTHandler();
                $token = $jwtHandler->encode([
                    'id' => $account->id,
                    'email' => $account->email,
                    'username' => $account->username,
                    'role' => $account->role
                ]);

                // Lưu session như cũ (nếu dùng session cho web)
                session_start();
                $_SESSION['id'] = $account->id;
                $_SESSION['email'] = $account->email;
                $_SESSION['username'] = $account->username;
                $_SESSION['address'] = $account->address;
                $_SESSION['role'] = $account->role;

                // Nếu là API, trả về JSON chứa token
                if (isset($_POST['api']) && $_POST['api'] == 1) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'token' => $token,
                        'user' => [
                            'id' => $account->id,
                            'email' => $account->email,
                            'username' => $account->username,
                            'role' => $account->role
                        ]
                    ]);
                    exit;
                } else {
                    // Nếu là login web thông thường
                    header('Location: /webbanhang/product');
                    exit;
                }
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }

    public function profile()
    {
        SessionHelper::start();

        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit;
        }

        $account_id = SessionHelper::getAccountId();
        $account = $this->accountModel->getAccountById($account_id);

        if ($account) {
            include 'app/views/account/profile.php';
        } else {
            echo "Không tìm thấy tài khoản!";
        }
    }

    // --- Address functions ---

    public function address()
    {
        SessionHelper::start();

        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit;
        }

        $account_id = SessionHelper::getAccountId();
        $account = $this->accountModel->getAccountById($account_id); // <<--- thêm dòng này
        $addresses = $this->addressModel->getAddressesByAccountId($account_id);

        include 'app/views/account/address.php';
    }

    // Thêm địa chỉ mới
    public function addAddress()
    {
        SessionHelper::start();
        $account_id = SessionHelper::getAccountId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname']);
            $phone = trim($_POST['phone']);
            $provinceName = trim($_POST['provinceName']);
            $districtName = trim($_POST['districtName']);
            $wardName = trim($_POST['wardName']);
            $address_detail = trim($_POST['address_detail']);

            // Gọi đúng hàm addAddress mới
            $this->addressModel->addAddress($account_id, $fullname, $phone, $provinceName, $districtName, $wardName, $address_detail);

            header('Location: /webbanhang/account/address');
            exit();
        }
    }


    // Xóa địa chỉ
    public function deleteAddress($id)
    {
        SessionHelper::start();
        $this->account = $this->accountModel->getAccountById(SessionHelper::getAccountId());

        if (!$this->account) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $address = $this->addressModel->getAddressById($id);

        if ($address && $address->account_id == $this->account->id) {
            $this->addressModel->deleteAddress($id);
        }

        header('Location: /webbanhang/account/address');
        exit();
    }


    // Trang sửa địa chỉ
    public function editAddress($id)
    {
        SessionHelper::start();
        $this->account = $this->accountModel->getAccountById(SessionHelper::getAccountId());

        if (!$this->account) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $address = $this->addressModel->getAddressById($id);

        if (!$address || $address->account_id != $this->account->id) {
            header('Location: /webbanhang/account/address');
            exit();
        }

        include 'app/views/account/edit_address.php';
    }


    public function updateAddress($id)
    {
        SessionHelper::start();
        $account_id = SessionHelper::getAccountId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $provinceName = isset($_POST['provinceName']) ? trim($_POST['provinceName']) : '';
            $districtName = isset($_POST['districtName']) ? trim($_POST['districtName']) : '';
            $wardName = isset($_POST['wardName']) ? trim($_POST['wardName']) : '';
            $address_detail = isset($_POST['address_detail']) ? trim($_POST['address_detail']) : '';

            $updateSuccessful = $this->addressModel->updateAddress($id, $account_id, $name, $phone, $provinceName, $districtName, $wardName, $address_detail);

            if ($updateSuccessful) {
                header('Location: /webbanhang/account/address');
                exit();
            } else {
                echo "Cập nhật địa chỉ không thành công.";
            }
        }
    }
    public function forgotPassword() {
        include 'app/views/account/forgot_password.php';
    }
    
    public function sendResetEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $account = $this->accountModel->getAccountByEmail($email);
    
            if ($account) {
                // Tạo token và thời gian hết hạn
                $token = bin2hex(random_bytes(50));
                $expiry = date('Y-m-d H:i:s', strtotime('+8 hours'));
    
                // Lưu vào DB
                $query = "UPDATE account SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':expiry', $expiry);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
    
                // Gửi email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'leducthinh203@gmail.com';
                    $mail->Password = 'slzb xfec hrfb spfy';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
    
                    $mail->setFrom('leducthinh203@gmail.com', '=?UTF-8?B?'.base64_encode('Dimodo Shop 🏬').'?=');

                    $mail->addAddress($email, $account->username);
                    $mail->isHTML(true);
                    $mail->Subject = 'Khôi phục mật khẩu';
                    $mail->CharSet = 'UTF-8';  // Đảm bảo mã hóa UTF-8 cho tiêu đề
                    $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                color: #333;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 0;
                            }
                            .container {
                                width: 100%;
                                max-width: 600px;
                                margin: 20px auto;
                                background-color: #fff;
                                padding: 20px;
                                border-radius: 8px;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                text-align: center;
                                font-size: 24px;
                                color: #4CAF50;
                            }
                            .content {
                                font-size: 16px;
                                line-height: 1.5;
                                margin-top: 15px;
                            }
                            .button {
                                display: block;
                                width: 100%;
                                max-width: 200px;
                                padding: 10px;
                                margin: 20px auto;
                                text-align: center;
                                background-color: #4CAF50;
                                color: #fff;
                                text-decoration: none;
                                font-size: 16px;
                                border-radius: 5px;
                                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                            }
                            .footer {
                                font-size: 12px;
                                text-align: center;
                                color: #888;
                                margin-top: 30px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h2>Khôi phục mật khẩu</h2>
                            </div>
                            <div class='content'>
                                <p>Xin chào,</p>
                                <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình. Vui lòng nhấn vào nút dưới đây để tiến hành thay đổi mật khẩu.</p>
                                <a href='http://localhost/webbanhang/account/resetPasswordForm?token=$token' class='button'>Đặt lại mật khẩu</a>
                                <p>Link này sẽ hết hạn sau 60 phút. Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.</p>
                            </div>
                            <div class='footer'>
                                <p>&copy; 2025 Công ty của chúng tôi. Mọi quyền lợi được bảo lưu.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";
    
                    $mail->send();
    
                    // Lưu thông báo vào session và chuyển hướng về trang login
                    session_start(); // Khởi động session nếu chưa khởi động
                    $_SESSION['reset_email_sent'] = 'Link đổi mật khẩu đã được gửi, vui lòng kiểm tra email.';
                    header('Location: http://localhost/webbanhang/account/login');
                    exit(); // Dừng script để không thực thi tiếp
                } catch (Exception $e) {
                    echo "Không gửi được email. Lỗi: {$mail->ErrorInfo}";
                }
            } else {
                $_SESSION['email_not_found'] = 'Email không tồn tại.';
                header('Location: http://localhost/webbanhang/account/forgotPassword');
                exit(); // Dừng script để không thực thi tiếp
                
            }
        }
    }
    
    
    public function resetPasswordForm() {
        $token = $_GET['token'] ?? '';
        include 'app/views/account/reset_password.php';
    }
    
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy token và mật khẩu từ POST
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            // Kiểm tra nếu mật khẩu và xác nhận mật khẩu không khớp
            if ($password !== $confirmPassword) {
                $_SESSION['diffirent_password'] = 'Mật khẩu không khớp';
                header('Location: http://localhost/webbanhang/account/forgotpassword');
             
                return;
            }
    
            // Truy vấn kiểm tra token hợp lệ và chưa hết hạn
            $query = "SELECT * FROM account WHERE reset_token = :token AND reset_token_expiry > NOW()";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $account = $stmt->fetch(PDO::FETCH_OBJ);
    
            if ($account) {
                // Nếu token hợp lệ, tiến hành cập nhật mật khẩu
                $newPassword = password_hash($password, PASSWORD_BCRYPT);
    
                // Cập nhật mật khẩu mới và xóa token và thời gian hết hạn
                $query = "UPDATE account SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':password', $newPassword);
                $stmt->bindParam(':id', $account->id);
                $stmt->execute();
    
                $_SESSION['change_password'] = 'Đổi mật khẩu thành công!';
                header('Location: http://localhost/webbanhang/account/login');
                exit(); // Dừng script để không thực thi tiếp
            } else {
                // Nếu token không hợp lệ hoặc đã hết hạn
                $_SESSION['token_Time'] = 'Liên kết đã hết hạn';
                header('Location: http://localhost/webbanhang/account/forgotpassword');
                exit(); // Dừng script để không thực thi tiếp
            }
        }
    }
    

}
?>