<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Lấy danh sách danh mục
    public function index()
    {
        header('Content-Type: application/json');
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $categories = $this->categoryModel->getCategories($keyword, $limit, $offset);
        echo json_encode($categories);
    }

    // Lấy thông tin danh mục theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
        }
    }

    // Thêm danh mục mới
    public function store()
    {
        header('Content-Type: application/json');

        // Kiểm tra nếu request gửi JSON
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        $isJson = strpos($contentType, 'application/json') !== false;
        $data = [];
        if ($isJson) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['errors' => ['json' => 'Dữ liệu JSON không hợp lệ']]);
                return;
            }
        }

        // Lấy dữ liệu từ POST hoặc JSON
        $name = isset($_POST['name']) ? trim($_POST['name']) : ($data['name'] ?? '');
        $description = isset($_POST['description']) ? trim($_POST['description']) : ($data['description'] ?? '');

        // Gọi addCategory
        $result = $this->categoryModel->addCategory($name, $description);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        }
    }

    // Cập nhật danh mục theo ID
    public function update($id)
    {
        header('Content-Type: application/json');

        // Kiểm tra nếu request gửi JSON
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        $isJson = strpos($contentType, 'application/json') !== false;
        $data = [];
        if ($isJson) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['errors' => ['json' => 'Dữ liệu JSON không hợp lệ']]);
                return;
            }
        }

        // Lấy dữ liệu từ POST hoặc JSON
        $name = isset($_POST['name']) ? trim($_POST['name']) : ($data['name'] ?? '');
        $description = isset($_POST['description']) ? trim($_POST['description']) : ($data['description'] ?? '');

        // Gọi updateCategory
        $result = $this->categoryModel->updateCategory($id, $name, $description);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'Category updated successfully']);
        }
    }

    // Xóa danh mục theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->categoryModel->deleteCategory($id);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'Category deleted successfully']);
        }
    }
}
?>