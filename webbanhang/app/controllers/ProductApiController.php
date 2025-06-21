<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    private function authenticate($requiredRole = null)
    {
        $headers = apache_request_headers();
        error_log('Headers: ' . print_r($headers, true));
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authorization header missing']);
            return false;
        }

        $authHeader = $headers['Authorization'];
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1] ?? null;

        if (!$jwt) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token format']);
            return false;
        }

        $decoded = $this->jwtHandler->decode($jwt);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid or expired token']);
            return false;
        }

        error_log('Decoded JWT: ' . print_r($decoded, true));
        if ($requiredRole && $decoded['role'] !== $requiredRole) {
            http_response_code(403);
            echo json_encode(['message' => 'Insufficient permissions']);
            return false;
        }

        return $decoded;
    }

    // Lấy danh sách sản phẩm (admin và user)
    public function index()
    {
        header('Content-Type: application/json');
        if ($this->authenticate()) {
            $products = $this->productModel->getProducts();
            echo json_encode($products);
        }
    }

    // Lấy thông tin sản phẩm theo ID (admin và user)
    public function show($id)
    {
        header('Content-Type: application/json');
        if ($this->authenticate()) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Product not found']);
            }
        }
    }

    // Thêm sản phẩm mới (chỉ admin)
    public function store()
    {
        header('Content-Type: application/json');
        if (!$this->authenticate('admin')) {
            return;
        }

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

        $name = isset($_POST['name']) ? trim($_POST['name']) : ($data['name'] ?? '');
        $description = isset($_POST['description']) ? trim($_POST['description']) : ($data['description'] ?? '');
        $price = isset($_POST['price']) ? trim($_POST['price']) : ($data['price'] ?? '');
        $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : ($data['category_id'] ?? null);
        $imagePath = null;

        error_log('Store Product Data: ' . print_r([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'image' => $_FILES['image'] ?? ($data['image'] ?? 'No image')
        ], true));

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image = $_FILES['image'];
            $uploadDir = 'public/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $imageName = uniqid() . '-' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'Chỉ chấp nhận file JPEG, PNG hoặc GIF']]);
                return;
            }
            if ($image['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'Hình ảnh không được vượt quá 5MB']]);
                return;
            }

            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                http_response_code(500);
                echo json_encode(['errors' => ['image' => 'Lỗi khi lưu hình ảnh']]);
                return;
            }
        } elseif ($isJson && isset($data['image']) && !empty(trim($data['image']))) {
            $imagePath = trim($data['image']);
            if (!file_exists($imagePath)) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'File hình ảnh không tồn tại trên server']]);
                return;
            }
        } else {
            http_response_code(400);
            echo json_encode(['errors' => ['image' => 'Hình ảnh không được để trống']]);
            return;
        }

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $imagePath);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        }
    }

    // Cập nhật sản phẩm (chỉ admin)
    public function update($id)
    {
        header('Content-Type: application/json');
        if (!$this->authenticate('admin')) {
            return;
        }

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

        $name = isset($_POST['name']) ? trim($_POST['name']) : ($data['name'] ?? '');
        $description = isset($_POST['description']) ? trim($_POST['description']) : ($data['description'] ?? '');
        $price = isset($_POST['price']) ? trim($_POST['price']) : ($data['price'] ?? '');
        $category_id = isset($_POST['category_id']) ? trim($_POST['category_id']) : ($data['category_id'] ?? null);
        $imagePath = null;

        error_log('Update Product Data: ' . print_r([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'image' => $_FILES['image'] ?? ($data['image'] ?? 'No image')
        ], true));

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image = $_FILES['image'];
            $uploadDir = 'public/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $imageName = uniqid() . '-' . basename($image['name']);
            $imagePath = $uploadDir . $imageName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image['type'], $allowedTypes)) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'Chỉ chấp nhận file JPEG, PNG hoặc GIF']]);
                return;
            }
            if ($image['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'Hình ảnh không được vượt quá 5MB']]);
                return;
            }

            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                http_response_code(500);
                echo json_encode(['errors' => ['image' => 'Lỗi khi lưu hình ảnh']]);
                return;
            }
        } elseif ($isJson && isset($data['image']) && !empty(trim($data['image']))) {
            $imagePath = trim($data['image']);
            if (!file_exists($imagePath)) {
                http_response_code(400);
                echo json_encode(['errors' => ['image' => 'File hình ảnh không tồn tại trên server']]);
                return;
            }
        }

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $imagePath);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } elseif ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }

    // Xóa sản phẩm theo ID (chỉ admin)
    public function destroy($id)
    {
        header('Content-Type: application/json');
        if (!$this->authenticate('admin')) {
            return;
        }

        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }
}