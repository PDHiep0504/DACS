<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    public function __construct($db)
    {
        $this->conn = $db;
    }    public function getProducts($search = '')
    {
        // Nếu có từ khóa tìm kiếm, thêm điều kiện LIKE vào câu truy vấn
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id";

        if ($search) {
            // Thêm điều kiện tìm kiếm vào câu truy vấn nếu có từ khóa
            $query .= " WHERE p.name LIKE :search";
        }

        $stmt = $this->conn->prepare($query);

        if ($search) {
            // Liên kết giá trị tìm kiếm vào câu truy vấn
            $stmt->bindValue(':search', '%' . $search . '%');
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function addProduct($name, $description, $price, $category_id, $image)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }
        $query = "INSERT INTO " . $this->table_name . " (name, description, price,
category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function updateProduct(
        $id,
        $name,
        $description,
        $price,
        $category_id,
        $image
    ) {
        $query = "UPDATE " . $this->table_name . " SET name=:name,
description=:description, price=:price, category_id=:category_id, image=:image WHERE
id=:id";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function deleteProduct($id)
    {
        try {
            // Bắt đầu transaction để đảm bảo tính nhất quán của dữ liệu
            $this->conn->beginTransaction();

            // Xoá các bản ghi liên quan trong bảng order_details trước
            $query1 = "DELETE FROM order_details WHERE product_sizes_id IN (SELECT id FROM product_sizes WHERE product_id = :id)";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':id', $id);
            $stmt1->execute();

            // Xoá các bản ghi liên quan trong bảng product_sizes
            $query2 = "DELETE FROM product_sizes WHERE product_id = :id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id', $id);
            $stmt2->execute();

            // Xoá các bản ghi đánh giá (rating) liên quan đến sản phẩm (nếu có)
            $query3 = "DELETE FROM rating WHERE order_detail_id IN (SELECT id FROM order_details WHERE product_sizes_id IN (SELECT id FROM product_sizes WHERE product_id = :id))";
            $stmt3 = $this->conn->prepare($query3);
            $stmt3->bindParam(':id', $id);
            $stmt3->execute();

            // Xoá sản phẩm trong bảng product
            $query4 = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt4 = $this->conn->prepare($query4);
            $stmt4->bindParam(':id', $id);
            $stmt4->execute();

            // Nếu tất cả các câu lệnh đều thành công, commit transaction
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            // Nếu có lỗi, rollback transaction
            $this->conn->rollBack();

            // Bạn có thể log lỗi nếu cần
            // error_log($e->getMessage());
            return false;
        }
    }

    // Phương thức tìm kiếm sản phẩm
    public function searchProducts($query)
    {
        $query = "%" . htmlspecialchars(strip_tags($query)) . "%"; // Làm sạch và tạo chuỗi tìm kiếm
        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE p.name LIKE :query";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':query', $query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function addProductSize($product_id, $size_id)
    {
        $query = "INSERT INTO product_sizes (product_id, size_id) VALUES (:product_id, :size_id)";
        $stmt = $this->conn->prepare($query);  // Sử dụng $this->conn thay vì $this->db
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->execute();
    }

    public function deleteProductSizes($product_id)
    {
        $query = "DELETE FROM product_sizes WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);  // Sử dụng $this->conn thay vì $this->db
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
    public function getProductSizes($product_id)
    {
        $query = "SELECT s.id, s.size AS name
              FROM product_sizes ps
              INNER JOIN sizes s ON ps.size_id = s.id
              WHERE ps.product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getProductsByCategory($categoryId)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name
                 FROM " . $this->table_name . " p
                 LEFT JOIN category c ON p.category_id = c.id
                 WHERE p.category_id = :category_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}