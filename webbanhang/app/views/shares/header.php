<?php
require_once __DIR__ . '/../../helpers/SessionHelper.php';
use App\Helpers\SessionHelper;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quản lý sản phẩm</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
        crossorigin="anonymous" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        rel="stylesheet" />

    <style>
        .navbar {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .nav-link:hover {
            color: #f8f9fa !important;
            transform: translateY(-2px);
        }

        .btn-search {
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .content-card {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-item.dropdown:hover>.dropdown-menu {
            display: block;
            margin-top: 0;
        }

        .dropdown-menu {
            margin-top: 0;
        }

        .nav-link.dropdown-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light text-dark">
    <?php
    $currentPath = $_SERVER['REQUEST_URI'];
    $isProduct = strpos($currentPath, '/Product') !== false;
    $isCategory = strpos($currentPath, '/Category') !== false;
    $isUser = strpos($currentPath, '/User') !== false;

    $searchAction = '/webbanhang/Product/search';
    if ($isCategory) {
        $searchAction = '/webbanhang/Category/search';
    } elseif ($isUser) {
        $searchAction = '/webbanhang/User/search';
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/webbanhang/Product/list">
                <i class="bi bi-house-door mr-2"></i> Trang chủ
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= $isProduct ? 'active' : '' ?>">
                        <a class="nav-link" href="/webbanhang/Product/list">
                            <i class="bi bi-box mr-1"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item <?= $isCategory ? 'active' : '' ?>">
                        <a class="nav-link" href="/webbanhang/Category/list">
                            <i class="bi bi-tags mr-1"></i> Danh mục
                        </a>
                    </li>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <!-- Admin-only links -->
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Product/add">
                                <i class="bi bi-plus-square mr-1"></i> Thêm sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Category/add">
                                <i class="bi bi-plus-square mr-1"></i> Thêm danh mục
                            </a>
                        </li>
                        <li class="nav-item <?= $isUser ? 'active' : '' ?>">
                            <a class="nav-link" href="/webbanhang/account/list">
                                <i class="bi bi-people mr-1"></i> Danh sách người dùng
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- User-only link -->
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Product/cart">
                                <i class="bi bi-cart mr-1"></i> Giỏ hàng
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Login / Username / Logout -->
                    <li class="nav-item" id="nav-login">
                        <a class="nav-link" href="/webbanhang/account/login">Đăng nhập</a>
                    </li>
                    <li class="nav-item dropdown" id="nav-user" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-person-circle mr-1"></i>
                            <span id="username"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/webbanhang/account/profile">Chỉnh sửa trang cá nhân</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="logout()">Đăng xuất</a>
                        </div>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0" method="GET" action="<?= $searchAction ?>">
                    <input class="form-control mr-sm-2 rounded-left"
                        type="search"
                        name="keyword"
                        placeholder="Tìm kiếm..."
                        aria-label="Search"
                        value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES) : '' ?>">
                    <button class="btn btn-primary btn-search my-2 my-sm-0 rounded-right" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
    <script>
    function logout() {
        localStorage.removeItem('jwtToken');
        location.href = '/webbanhang/account/login';
    }

    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('jwtToken');
        if (token) {
            document.getElementById('nav-login').style.display = 'none';
            document.getElementById('nav-user').style.display = 'block';
            // Decode JWT to get username
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const payload = JSON.parse(atob(base64));
                document.getElementById('username').textContent = payload.username || 'User';
            } catch (e) {
                console.error('Error decoding token:', e);
                document.getElementById('username').textContent = 'User';
            }
        } else {
            document.getElementById('nav-login').style.display = 'block';
            document.getElementById('nav-user').style.display = 'none';
        }
    });
    </script>
</body>