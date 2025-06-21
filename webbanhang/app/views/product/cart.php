<?php include 'app/views/shares/header.php'; ?>
<div class="container my-5">
    <h1 class="text-center mb-4">Giỏ Hàng</h1>

    <?php if (!empty($cart)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Sản Phẩm Trong Giỏ Hàng</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($cart as $id => $item): 
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="/webbanhang/Product/decreaseQuantity/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>">-</a>
                                        <span class="mx-2"><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <a href="/webbanhang/Product/increaseQuantity/<?php echo $id; ?>" class="btn btn-sm btn-outline-secondary">+</a>
                                    </div>
                                </td>
                                <td><?php echo number_format($itemTotal, 0, ',', '.'); ?> VND</td>
                                <td>
                                    <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" class="btn btn-sm btn-danger">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="fw-bold"><?php echo number_format($total, 0, ',', '.'); ?> VND</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="/webbanhang/Product" class="btn btn-outline-secondary">Tiếp tục mua sắm</a>
            <a href="/webbanhang/Product/checkout" class="btn btn-primary">Thanh Toán</a>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Giỏ hàng của bạn đang trống. 
            <a href="/webbanhang/Product" class="alert-link">Tiếp tục mua sắm</a>.
        </div>
    <?php endif; ?>
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
.btn-outline-secondary, .btn-danger {
    padding: 5px 10px;
}
.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
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