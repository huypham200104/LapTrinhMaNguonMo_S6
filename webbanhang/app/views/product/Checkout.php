<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <h1 class="text-center mb-4">Thanh Toán</h1>

    <!-- Cart Summary -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Tóm Tắt Đơn Hàng</h2>
        </div>
        <div class="card-body">
            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['cart'] as $id => $item): 
                                $itemTotal = $item['price'] * $item['quantity'];
                                $total += $itemTotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if ($item['image']): ?>
                                        <img src="/webbanhang/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" class="img-fluid rounded" style="max-width: 60px;">
                                    <?php else: ?>
                                        <span class="text-muted">Không có hình</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                                <td><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo number_format($itemTotal, 0, ',', '.'); ?> VND</td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center" role="alert">
                    Giỏ hàng của bạn đang trống. 
                    <a href="/webbanhang/Product" class="alert-link">Tiếp tục mua sắm</a>.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Checkout Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Thông Tin Thanh Toán</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="/webbanhang/Product/processCheckout">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Họ tên:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">Địa chỉ:</label>
                        <textarea id="address" name="address" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <label for="payment_method" class="form-label">Phương thức thanh toán:</label>
                        <select id="payment_method" name="payment_method" class="form-select" required>
                            <option value="" disabled selected>Chọn phương thức</option>
                            <option value="VNpay">VNpay</option>
                            <option value="Momo">Momo</option>
                            <option value="Ngân hàng">Ngân hàng</option>
                            <option value="Tiền mặt">Tiền mặt</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="/webbanhang/Product/cart" class="btn btn-outline-secondary">Quay lại giỏ hàng</a>
                    <button type="submit" class="btn btn-primary">Thanh toán</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f8f9fa;
}
.card {
    border-radius: 10px;
}
.card-header {
    border-radius: 10px 10px 0 0;
}
.table th, .table td {
    vertical-align: middle;
}
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 10px 20px;
}
.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
.btn-outline-secondary {
    padding: 10px 20px;
}
.form-control, .form-select {
    border-radius: 5px;
}
@media (max-width: 576px) {
    .table-responsive {
        font-size: 14px;
    }
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    .d-flex {
        flex-direction: column;
    }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>