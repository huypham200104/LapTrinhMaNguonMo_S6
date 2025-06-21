<?php include 'app/views/shares/header.php'; ?>

<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh; background: #f8f9fa;">
    <div class="bg-white p-4 rounded shadow" style="width: 100%; max-width: 500px;">
        <h1 class="text-center mb-4">Thêm danh mục mới</h1>

        <div id="alert-container"></div>

        <form id="add-category-form">
            <div class="form-group mb-3">
                <label for="name">Tên danh mục:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="description">Mô tả:</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Thêm</button>
            <a href="/webbanhang/category/list" class="btn btn-secondary mt-2 w-100">Quay lại</a>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Xử lý form submit
    document.getElementById('add-category-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const jsonData = Object.fromEntries(formData);

        fetch('/webbanhang/api/category', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Category created successfully') {
                showAlert('success', 'Thêm danh mục thành công');
                setTimeout(() => location.href = '/webbanhang/category/list', 2000);
            } else {
                showAlert('danger', JSON.stringify(data.errors) || 'Thêm danh mục thất bại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi thêm danh mục');
        });
    });
});

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
}
</script>