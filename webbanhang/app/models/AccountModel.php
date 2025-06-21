<?php

class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // --- Common ---
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // --- 1. Tạo tài khoản mới ---
    public function createAccount($username, $fullName, $password, $role = 'user', $phone = null, $avatar = null)
    {
        if ($this->getAccountByUsername($username)) {
            return false; // Tài khoản đã tồn tại
        }

        $query = "INSERT INTO " . $this->table_name . " 
            SET username = :username, 
                fullname = :fullname, 
                role = :role,
                phone = :phone,
                avatar = :avatar,
                password = :password";

        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $role = htmlspecialchars(strip_tags($role));
        $phone = $phone ? htmlspecialchars(strip_tags($phone)) : null;
        $avatar = $avatar ? htmlspecialchars(strip_tags($avatar)) : null;
        $password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":role", $role);
        $stmt->bindValue(":phone", $phone, $phone === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":avatar", $avatar, $avatar === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":password", $password);

        return $stmt->execute();
    }

    // --- 2. Cập nhật thông tin cá nhân (user tự sửa profile) ---
    public function updateProfile($username, $fullName, $phone = null, $avatar = null, $password = null)
    {
        $query = "UPDATE " . $this->table_name . " SET 
            fullname = :fullname,
            phone = :phone,
            avatar = :avatar";

        if (!empty($password)) {
            $query .= ", password = :password";
        }

        $query .= " WHERE username = :username";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $phone = $phone !== null ? htmlspecialchars(strip_tags($phone)) : null;
        $avatar = $avatar !== null ? htmlspecialchars(strip_tags($avatar)) : null;

        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindValue(":phone", $phone, $phone === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":avatar", $avatar, $avatar === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":username", $username);

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindValue(":password", $passwordHash);
        }

        return $stmt->execute();
    }

    // --- 3. Chỉnh sửa thông tin người dùng (admin chỉnh) ---
    public function adminEditUser($username, $fullName, $role, $phone = null, $avatar = null, $password = null)
    {
        $query = "UPDATE " . $this->table_name . " SET 
            fullname = :fullname,
            role = :role,
            phone = :phone,
            avatar = :avatar";

        if (!empty($password)) {
            $query .= ", password = :password";
        }

        $query .= " WHERE username = :username";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $role = htmlspecialchars(strip_tags($role));
        $phone = $phone !== null ? htmlspecialchars(strip_tags($phone)) : null;
        $avatar = $avatar !== null ? htmlspecialchars(strip_tags($avatar)) : null;

        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":role", $role);
        $stmt->bindValue(":phone", $phone, $phone === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(":avatar", $avatar, $avatar === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(":username", $username);

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindValue(":password", $passwordHash);
        }

        return $stmt->execute();
    }

    // --- Profile view ---
    public function profile($username)
    {
        $query = "SELECT username, fullname, phone, avatar, role 
                  FROM " . $this->table_name . " 
                  WHERE username = :username
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    // --- Danh sách người dùng ---
    public function getAllUsers()
    {
        $query = "SELECT username, fullname,email, phone, avatar, role FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // --- Xóa người dùng ---
    public function deleteUser($username)
    {
        if (!$this->getAccountByUsername($username)) {
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        return $stmt->execute();
    }
}
