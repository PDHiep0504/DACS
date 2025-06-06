<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    // Constructor nhận đối tượng PDO
    public function __construct($db)
    {
        if (!$db) {
            throw new Exception("Không thể kết nối tới cơ sở dữ liệu.");
        }
        $this->conn = $db; // Gán đối tượng PDO vào thuộc tính $conn
    }

    // Lấy thông tin tài khoản theo email
    public function getAccountByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lưu tài khoản mới vào cơ sở dữ liệu
    public function save($email, $username, $password, $role = 'user')
    {
        // Kiểm tra email đã tồn tại chưa
        if ($this->getAccountByEmail($email)) {
            return false; // Không thể đăng ký nếu email đã tồn tại
        }

        $query = "INSERT INTO " . $this->table_name . " (email, username, password, role) 
                  VALUES (:email, :username, :password, :role)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch và xử lý dữ liệu đầu vào
        $email = htmlspecialchars(strip_tags($email ?? ''));
        $username = htmlspecialchars(strip_tags($username ?? ''));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role ?? 'user'));

        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }

    // Lấy thông tin tài khoản theo ID
    public function getAccountById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getAllAccounts($roleFilter = '', $emailFilter = '', $nameFilter = '')
    {
        // Bắt đầu câu lệnh SQL cơ bản
        $sql = "SELECT * FROM " . $this->table_name . " WHERE 1=1";

        // Thêm điều kiện lọc theo vai trò nếu có
        if (!empty($roleFilter)) {
            $sql .= " AND role = :role";
        }

        // Thêm điều kiện lọc theo email nếu có
        if (!empty($emailFilter)) {
            $sql .= " AND email LIKE :email";
        }

        // Thêm điều kiện lọc theo tên nếu có
        if (!empty($nameFilter)) {
            $sql .= " AND username LIKE :name";
        }

        // Chuẩn bị câu lệnh SQL
        $stmt = $this->conn->prepare($sql);

        // Liên kết các tham số với các giá trị
        if (!empty($roleFilter)) {
            $stmt->bindValue(':role', $roleFilter);
        }
        if (!empty($emailFilter)) {
            $stmt->bindValue(':email', "%$emailFilter%");
        }
        if (!empty($nameFilter)) {
            $stmt->bindValue(':name', "%$nameFilter%");
        }

        // Thực thi câu lệnh
        $stmt->execute();

        // Trả về kết quả
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Cập nhật thông tin tài khoản theo ID
    public function updateAccount($id, $email, $username, $role)
    {
        // Xử lý và làm sạch dữ liệu đầu vào
        $email = htmlspecialchars(strip_tags($email ?? ''));
        $username = htmlspecialchars(strip_tags($username ?? ''));
        $role = htmlspecialchars(strip_tags($role ?? 'user'));

        // Cập nhật tài khoản trong cơ sở dữ liệu
        $query = "UPDATE " . $this->table_name . " 
              SET email = :email, username = :username, role = :role 
              WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);

        return $stmt->execute(); // Trả về true nếu thành công, false nếu có lỗi
    }
    // Xóa tài khoản theo ID
    public function deleteAccount($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Gán tham số ID
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute(); // Trả về true nếu thành công, false nếu có lỗi
    }

}

?>