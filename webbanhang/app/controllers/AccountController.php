<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');

class AccountController {
    private $accountModel;
    private $db;
    private $jwtHandler;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    public function register() {
        include_once 'app/views/account/register.php';
    } 

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa đúng";
            }

            // Kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tài khoản này đã có người đăng ký!";
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->createAccount($username, $fullName, $password);
                if ($result) {
                    header('Location: /webbanhang/account/login');
                    exit();
                }
            }
        }
    }

    public function logout() {
        // Since we're using JWT, no need to unset session variables unless they're used elsewhere
        // Remove session usage if JWT is the primary authentication mechanism
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header('Location: /webbanhang/product');
        exit();
    }

    public function checkLogin() {
        header('Content-Type: application/json');
        if (ob_get_level()) ob_end_clean();
    
        try {
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            if (!$data || empty($data['username']) || empty($data['password'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Username and password are required']);
                return;
            }
    
            $user = $this->accountModel->getAccountByUsername($data['username']);
            if ($user && password_verify($data['password'], $user->password)) {
                $token = $this->jwtHandler->encode([
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                ]);
                echo json_encode(['token' => $token, 'message' => 'Login successful']);
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid credentials']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error', 'error' => $e->getMessage()]);
        }
    }
    
    
}