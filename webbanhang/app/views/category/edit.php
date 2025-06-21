<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-center align-items-center">
    <div class="bg-white rounded shadow p-4" style="width: 100%; max-width: 480px;">
        <h1 class="text-center mb-4" style="font-weight: 700; font-size: 1.75rem;">Sửa danh mục</h1>

        <div id="alert-container"></div>

        <form id="edit-category-form">
            <div class="form-group mb-3">
                <label for="name">Tên danh mục:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group mb-4">
                <label for="description">Mô tả:</label>
                <textarea name="description" id="description" class="form-control" rows="4"></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                <a href="/webbanhang/category/list" class="btn btn-secondary px-4">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const categoryId = window.location.pathname.split('/').pop();
    
    // Tải dữ liệu danh mục
    fetch(`/webbanhang/api/category/${categoryId}`)
        .then(response => response.json())
        .then(category => {
            if (category.message) {
                showAlert('danger', category.message);
            } else {
                document.getElementById('name').value = category.name;
                document.getElementById('description').value = category.description;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi tải dữ liệu danh mục');
        });

    // Xử lý form submit
    document.getElementById('edit-category-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const jsonData = Object.fromEntries(formData);

        fetch(`/webbanhang/api/category/${categoryId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Category updated successfully') {
                showAlert('success', 'Cập nhật danh mục thành công');
                setTimeout(() => location.href = '/webbanhang/category/list', 2000);
            } else {
                showAlert('danger', JSON.stringify(data.errors) || 'Cập nhật danh mục thất bại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi cập nhật danh mục');
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