<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class ProductController
{
    private $productModel;
    private $categoryModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    // Kiểm tra quyền Admin bằng JWT
    private function authenticate()
    {
        $headers = apache_request_headers();
        error_log('Headers: ' . print_r($headers, true));
        if (!isset($headers['Authorization'])) {
            include_once 'app/views/shares/AccessDenied.php';
            error_log('Authorization header missing');
            exit;
        }

        $authHeader = $headers['Authorization'];
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1] ?? null;

        if (!$jwt) {
            include_once 'app/views/shares/AccessDenied.php';
            error_log('Invalid token format');
            exit;
        }

        $decoded = $this->jwtHandler->decode($jwt);
        if (!$decoded) {
            include_once 'app/views/shares/AccessDenied.php';
            error_log('Invalid or expired token');
            exit;
        }

        error_log('Decoded JWT: ' . print_r($decoded, true));
        if ($decoded->role !== 'admin') {
            include_once 'app/views/shares/AccessDenied.php';
            error_log('Insufficient permissions');
            exit;
        }

        return $decoded;
    }

    public function index()
    {
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $keyword = $_GET['keyword'] ?? '';

        $products = $this->productModel->getProducts($keyword, $limit, $offset);
        $totalProducts = $this->productModel->getTotalProducts($keyword);
        $totalPages = ceil($totalProducts / $limit);

        include 'app/views/product/list.php';
    }

    public function list()
    {
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $keyword = $_GET['keyword'] ?? '';

        $products = $this->productModel->getProducts($keyword, $limit, $offset);
        $totalProducts = $this->productModel->getTotalProducts($keyword);
        $totalPages = ceil($totalProducts / $limit);

        include 'app/views/product/list.php';
    }

    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->getProducts($keyword);
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    public function add()
    {
        $this->authenticate();
        $categories = $this->categoryModel->getCategories();
        include 'app/views/product/add.php';
    }

    public function save()
    {
        $this->authenticate();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            $image = isset($_FILES['image']) && $_FILES['image']['error'] == 0
                ? $this->uploadImage($_FILES['image']) : "";

            try {
                $result = $this->productModel->addProduct(
                    $name,
                    $description,
                    $price,
                    $category_id,
                    $image
                );

                if ($result) {
                    header('Location: /webbanhang/Product');
                    exit;
                } else {
                    throw new Exception("Lỗi khi thêm sản phẩm.");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function edit($id)
    {
        $this->authenticate();

        if (!is_numeric($id) || $id <= 0) {
            die('Lỗi: ID sản phẩm không hợp lệ');
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die('Lỗi: Không tìm thấy sản phẩm');
        }

        $editId = (int)$id;
        include 'app/views/product/edit.php';
    }

    public function update()
    {
        $this->authenticate();

        // Handle POST with _method=PUT to simulate PUT request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            $image = isset($_FILES['image']) && $_FILES['image']['error'] == 0
                ? $this->uploadImage($_FILES['image']) : $_POST['existing_image'] ?? '';

            try {
                $edit = $this->productModel->updateProduct(
                    $id,
                    $name,
                    $description,
                    $price,
                    $category_id,
                    $image
                );

                if ($edit) {
                    header('Location: /webbanhang/Product');
                    exit;
                } else {
                    throw new Exception("Đã xảy ra lỗi khi sửa sản phẩm.");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function delete($id)
    {
        $this->authenticate();

        try {
            $deleted = $this->productModel->deleteProduct($id);
            if ($deleted) {
                header('Location: /webbanhang/Product');
                exit;
            } else {
                throw new Exception("Không thể xóa sản phẩm.");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "public/images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }

        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi khi tải lên hình ảnh.");
        }

        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        header('Location: /webbanhang/Product/list');
    }

    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $payment_method = $_POST['payment_method'] ?? '';

            if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($payment_method)) {
                echo "Vui lòng điền đầy đủ thông tin.";
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email không hợp lệ.";
                return;
            }

            if (!in_array($payment_method, ['VNpay', 'Momo', 'Ngân hàng', 'Tiền mặt'])) {
                echo "Phương thức thanh toán không hợp lệ.";
                return;
            }

            if (empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->begin.UtilTransaction();
            try {
                $query = "INSERT INTO orders (name, email, phone, address, payment_method) 
                          VALUES (:name, :email, :phone, :address, :payment_method)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':payment_method', $payment_method);
                $stmt->execute();

                $order_id = $this->db->lastInsertId();

                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $stmt = $this->db->prepare(
                        "INSERT INTO order_details (order_id, product_id, quantity, price) 
                         VALUES (:order_id, :product_id, :quantity, :price)"
                    );
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                unset($_SESSION['cart']);
                $this->db->commit();

                header('Location: /webbanhang/Product/orderConfirmation');
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }

    public function getProductsByCategory($category_id)
    {
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;

        $products = $this->productModel->getProductsByCategory($category_id, $limit, $offset);
        $categoryName = $this->categoryModel->getCategoryNameById($category_id);
        $categories = $this->categoryModel->getCategories();

        include 'app/views/product/list.php';
    }
}