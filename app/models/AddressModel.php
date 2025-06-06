<?php
class AddressModel
{
    private $conn;
    private $table = 'address';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách địa chỉ theo account_id
    public function getAddressesByAccountId($account_id)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE account_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$account_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy 1 địa chỉ theo id
    public function getAddressById($id)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Thêm địa chỉ mới
    public function addAddress($account_id, $name, $phone, $provinceName, $districtName, $wardName, $address_detail)
    {
        $sql = "INSERT INTO " . $this->table . " 
            (account_id, name, phone, provinceName, districtName, wardName, address_detail) 
            VALUES (:account_id, :name, :phone, :provinceName, :districtName, :wardName, :address_detail)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':account_id' => $account_id,
            ':name' => $name,
            ':phone' => $phone,
            ':provinceName' => $provinceName,
            ':districtName' => $districtName,
            ':wardName' => $wardName,
            ':address_detail' => $address_detail
        ]);
    }

    // Cập nhật địa chỉ
    public function updateAddress($id, $account_id, $name, $phone, $provinceName, $districtName, $wardName, $address_detail)
    {
        $query = "UPDATE " . $this->table . " 
              SET name = :name, phone = :phone, provinceName = :provinceName, 
                  districtName = :districtName, wardName = :wardName, address_detail = :address_detail 
              WHERE id = :id AND account_id = :account_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':account_id' => $account_id,
            ':name' => $name,
            ':phone' => $phone,
            ':provinceName' => $provinceName,
            ':districtName' => $districtName,
            ':wardName' => $wardName,
            ':address_detail' => $address_detail
        ]);
    }



    // Xóa địa chỉ
    public function deleteAddress($id)
    {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>