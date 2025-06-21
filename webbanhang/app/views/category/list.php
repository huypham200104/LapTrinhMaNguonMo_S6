<?php include 'app/views/shares/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="w-75 bg-primary p-4 rounded shadow-lg text-white">
        <h1 class="text-center mb-4 font-weight-bold">Danh sách danh mục</h1>

        <div id="alert-container"></div>

        <div class="mb-4 text-right">
            <a href="/webbanhang/category/add" class="btn btn-light font-weight-bold">+ Thêm danh mục</a>
        </div>

        <table class="table table-bordered table-light text-dark">
            <thead class="thead-dark bg-dark text-white">
                <tr>
                    <th colspan="4" class="text-center">Danh mục (4 danh mục trên 1 hàng)</th>
                </tr>
            </thead>
            <tbody id="category-list"></tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tải danh sách danh mục từ API
    fetch('/webbanhang/api/category')
        .then(response => response.json())
        .then(categories => {
            const categoryList = document.getElementById('category-list');
            if (categories.length === 0) {
                categoryList.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Không có danh mục nào.</td></tr>';
                return;
            }

            // Chia danh mục thành nhóm 4 phần tử
            const chunks = [];
            for (let i = 0; i < categories.length; i += 4) {
                chunks.push(categories.slice(i, i + 4));
            }

            // Hiển thị danh mục
            chunks.forEach(chunk => {
                let row = '<tr>';
                chunk.forEach(category => {
                    row += `
                        <td style="vertical-align: top; width: 25%;">
                            <h5>${category.name}</h5>
                            <p>${category.description}</p>
                            <div class="mb-2">
                                <a href="/webbanhang/category/show/${category.id}" class="btn btn-sm btn-primary">Xem</a>
                                <a href="/webbanhang/category/edit/${category.id}" class="btn btn-sm btn-warning">Sửa</a>
                                <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.id})">Xóa</button>
                            </div>
                        </td>`;
                });
                // Thêm ô trống nếu không đủ 4 danh mục
                for (let i = chunk.length; i < 4; i++) {
                    row += '<td></td>';
                }
                row += '</tr>';
                categoryList.innerHTML += row;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi tải danh sách danh mục');
        });
});

function deleteCategory(id) {
    if (confirm('Bạn có chắc muốn xóa danh mục này?')) {
        fetch(`/webbanhang/api/category/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Category deleted successfully') {
                showAlert('success', 'Xóa danh mục thành công');
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert('danger', data.errors.error || 'Xóa danh mục thất bại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi xóa danh mục');
        });
    }
}

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
}
</script>