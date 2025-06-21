<?php
require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler
{
    private $secret_key;

    public function __construct()
    {
        $this->secret_key = "HUTECH"; // Thay thế bằng khóa bí mật của bạn
    }

    // Tạo JWT
    public function encode($data, $username, $role)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // jwt valid for 1 hour from the issued time
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data,
            'username' => $username,
            'role' => $role
        );
        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    // Giải mã JWT
    public function decode($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return array(
                'data' => (array) $decoded->data,
                'username' => $decoded->username,
                'role' => $decoded->role
            );
        } catch (Exception $e) {
            return null;
        }
    }
}