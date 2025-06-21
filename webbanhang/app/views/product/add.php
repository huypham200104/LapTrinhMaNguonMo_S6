<?php include 'app/views/shares/header.php'; ?>
<h1>Thêm sản phẩm mới</h1>
<form id="add-product-form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <!-- Các danh mục sẽ được tải từ API và hiển thị tại đây -->
        </select>
    </div>
    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
</form>
<a href="/webbanhang/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tải danh sách danh mục
    fetch('/webbanhang/api/category')
        .then(response => response.json())
        .then(data => {
            const categorySelect = document.getElementById('category_id');
            data.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
        });

    // Xử lý submit form
    document.getElementById('add-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this); // Sử dụng FormData để gửi file

        fetch('/webbanhang/api/product', {
            method: 'POST',
            body: formData // Gửi FormData thay vì JSON
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.message === 'Product created successfully') {
                location.href = '/webbanhang/Product';
            } else {
                alert('Thêm sản phẩm thất bại: ' + (data.errors ? JSON.stringify(data.errors) : 'Lỗi không xác định'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi: Không thể gửi yêu cầu đến máy chủ.');
        });
    });
});
</script>