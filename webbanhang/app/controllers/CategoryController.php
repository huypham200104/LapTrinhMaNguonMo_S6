<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }
    public function show($id)
{
    $category = $this->categoryModel->getCategoryById($id);
    include 'app/views/category/show.php';
}


    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                $error = "Tên danh mục không được để trống.";
            } else {
                $result = $this->categoryModel->addCategory($name, $description);
                if ($result) {
                    $success = "Thêm danh mục thành công!";
                } else {
                    $error = "Thêm danh mục thất bại!";
                }
            }
        }

        include 'app/views/category/add.php';
    }
    public function delete($id)
{
    if ($this->categoryModel->deleteCategory($id)) {
        header("Location: /webbanhang/category/list?success=Xóa danh mục thành công");
        exit;
    } else {
       
        header("Location: /webbanhang/category/list?error=Xóa danh mục thất bại");
        exit;
    }
}
public function edit($id)
{
    $category = $this->categoryModel->getCategoryById($id);
    if (!$category) {
        die('Không tìm thấy danh mục');
    }
    include 'app/views/category/edit.php';
}
public function search()
{
    $keyword = trim($_GET['keyword'] ?? '');
    $categories = [];

    if (!empty($keyword)) {
        $categories = $this->categoryModel->searchByName($keyword);
    }

    include 'app/views/category/list.php'; // tái dùng file list
}




public function update($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        if (empty($name)) {
            $error = "Tên danh mục không được để trống";
            $category = (object)['id' => $id, 'name' => $name, 'description' => $description];
            include 'app/views/category/edit.php';
            return;
        }

        $success = $this->categoryModel->updateCategory($id, $name, $description);
        if ($success) {
            header("Location: /webbanhang/category/list?success=Cập nhật thành công");
            exit;
        } else {
            $error = "Cập nhật thất bại";
            $category = (object)['id' => $id, 'name' => $name, 'description' => $description];
            include 'app/views/category/edit.php';
        }
    }
}


}
?>
