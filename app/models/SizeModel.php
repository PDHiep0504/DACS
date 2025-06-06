<?php
class SizeModel
{
    private $conn;
    private $table_name = "sizes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả các kích thước
    public function getSizes()
    {
        $query = "SELECT id, size FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Thêm một kích thước mới
    public function addSize($size)
    {
        $query = "INSERT INTO " . $this->table_name . " (size) VALUES (:size)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':size', $size);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật kích thước
    public function updateSize($id, $size)
    {
        $query = "UPDATE " . $this->table_name . " SET size = :size WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':size', $size);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa một kích thước
    public function deleteSize($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
