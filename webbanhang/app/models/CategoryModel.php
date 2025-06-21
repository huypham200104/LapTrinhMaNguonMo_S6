<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy danh sách danh mục với tìm kiếm, phân trang
    public function getCategories($keyword = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT id, name, description 
                  FROM " . $this->table_name . " 
                  WHERE name LIKE :keyword 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Đếm tổng số danh mục
    public function getTotalCategories($keyword = '')
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE name LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Thêm danh mục mới
    public function addCategory($name, $description)
    {
        $errors = [];
        if (empty(trim($name))) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }
        if (empty(trim($description))) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description) 
                  VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags(trim($name)));
        $description = htmlspecialchars(strip_tags(trim($description)));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name, $description)
    {
        $errors = [];
        if (empty(trim($name))) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }
        if (empty(trim($description))) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags(trim($name)));
        $description = htmlspecialchars(strip_tags(trim($description)));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa danh mục
    public function deleteCategory($id)
    {
        // Kiểm tra xem danh mục có sản phẩm liên quan không
        $query = "SELECT COUNT(*) FROM product WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            return ['error' => 'Không thể xóa danh mục vì có sản phẩm liên quan'];
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm danh mục theo tên
    public function searchByName($name)
    {
        $query = "SELECT id, name, description 
                  FROM " . $this->table_name . " 
                  WHERE name LIKE :name";
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . trim($name) . '%';
        $stmt->bindParam(':name', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy tất cả danh mục (không phân trang)
    public function getAllCategories()
    {
        $query = "SELECT id, name, description 
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy tên danh mục theo ID
    public function getCategoryNameById($id)
    {
        $query = "SELECT name 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->name : null;
    }
}
?>