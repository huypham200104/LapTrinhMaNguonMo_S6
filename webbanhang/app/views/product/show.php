<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-header bg-primary text-white text-center py-3">
      <h2 class="mb-0 fw-semibold">Chi tiết sản phẩm</h2>
    </div>
    <div class="card-body">
      <?php if ($product): ?>
        <div class="row g-4">
          <!-- Ảnh sản phẩm -->
          <div class="col-md-6 d-flex justify-content-center align-items-center">
            <?php if ($product->image): ?>
              <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                   class="img-fluid rounded shadow-sm product-image" 
                   alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                   style="max-height: 400px; object-fit: contain;">
            <?php else: ?>
              <img src="/webbanhang/images/no-image.png" 
                   class="img-fluid rounded shadow-sm" 
                   alt="Không có ảnh" style="max-height: 400px; object-fit: contain;">
            <?php endif; ?>
          </div>

          <!-- Thông tin sản phẩm -->
          <div class="col-md-6 d-flex flex-column justify-content-center">
            <h3 class="card-title text-primary fw-bold mb-3 fs-2">
              <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
            </h3>
            <p class="card-text text-secondary mb-4" style="white-space: pre-line; font-size: 1.1rem;">
              <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <p class="text-danger fw-bold display-5 mb-4">
              <i class="bi bi-currency-dollar me-2"></i> <?php echo number_format($product->price, 0, ',', '.'); ?> VND
            </p>

            <p class="mb-4">
              <strong>Danh mục:</strong>
              <span class="badge bg-info text-white fs-6">
                <?php echo !empty($product->category_name) ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 'Chưa có danh mục'; ?>
              </span>
            </p>

            <div class="d-flex flex-wrap gap-3">
              <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success btn-lg d-flex align-items-center gap-2 px-4">
                <i class="bi bi-cart-plus-fill fs-4"></i>
                <span>Thêm vào giỏ hàng</span>
              </a>
              <a href="/webbanhang/Product/list" class="btn btn-outline-secondary btn-lg px-4">
                Quay lại danh sách
              </a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-danger text-center my-5">
          <h4>Không tìm thấy sản phẩm!</h4>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
  .product-image {
    transition: transform 0.3s ease;
    cursor: pointer;
  }
  .product-image:hover {
    transform: scale(1.08);
  }
  /* Responsive text sizing */
  @media (max-width: 576px) {
    .card-title {
      font-size: 1.5rem !important;
    }
    .display-5 {
      font-size: 2rem !important;
    }
  }
</style>

<?php include 'app/views/shares/footer.php'; ?>
