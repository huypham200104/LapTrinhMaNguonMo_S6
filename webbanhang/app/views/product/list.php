<?php include 'app/views/shares/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="w-75 bg-primary p-4 rounded shadow-lg text-white">
        <h1 class="text-center mb-4 font-weight-bold">Danh sách sản phẩm</h1>

        <div id="alert-container"></div>

        <div class="mb-4 text-right">
            <a href="/webbanhang/Product/add" class="btn btn-success font-weight-bold">+ Thêm sản phẩm mới</a>
        </div>

        <table class="table table-bordered table-light text-dark">
            <thead class="thead-dark bg-dark text-white">
                <tr>
                    <th colspan="4" class="text-center">Sản phẩm (4 sản phẩm trên 1 hàng)</th>
                </tr>
            </thead>
            <tbody id="product-list"></tbody>
        </table>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const token = localStorage.getItem('jwtToken');
    if (!token) {
        alert('Vui lòng đăng nhập');
        location.href = '/webbanhang/account/login';
        return;
    }

    // Tải danh sách sản phẩm từ API
    fetch('/webbanhang/api/product', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Lỗi khi tải danh sách sản phẩm');
        }
        return response.json();
    })
    .then(products => {
        const productList = document.getElementById('product-list');
        if (products.length === 0) {
            productList.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Không có sản phẩm nào.</td></tr>';
            return;
        }

        // Chia sản phẩm thành nhóm 4 phần tử
        const chunks = [];
        for (let i = 0; i < products.length; i += 4) {
            chunks.push(products.slice(i, i + 4));
        }

        // Hiển thị sản phẩm
        chunks.forEach(chunk => {
            let row = '<tr>';
            chunk.forEach(product => {
                // Format price để loại bỏ .00
                const formattedPrice = parseFloat(product.price).toLocaleString('vi-VN');
                row += `
                    <td style="vertical-align: top; width: 25%;">
                        <h5><a href="/webbanhang/Product/show/${product.id}">${product.name}</a></h5>
                        <p>${product.description}</p>
                        <p>Giá: ${formattedPrice} VND</p>
                        <p>Danh mục: ${product.category_name || 'Không có danh mục'}</p>
                        <img src="/webbanhang/${product.image}" alt="${product.name}" style="max-width: 200px; max-height: 200px;" />
                        <div class="mb-2 mt-2">
                            <a href="/webbanhang/Product/edit/${product.id}" class="btn btn-sm btn-warning">Sửa</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">Xóa</button>
                        </div>
                    </td>`;
            });
            // Thêm ô trống nếu không đủ 4 sản phẩm
            for (let i = chunk.length; i < 4; i++) {
                row += '<td></td>';
            }
            row += '</tr>';
            productList.innerHTML += row;
        });
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Lỗi khi tải danh sách sản phẩm');
    });
});

function deleteProduct(id) {
    const token = localStorage.getItem('jwtToken');
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        fetch(`/webbanhang/api/product/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product deleted successfully') {
                showAlert('success', 'Xóa sản phẩm thành công');
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert('danger', 'Xóa sản phẩm thất bại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Lỗi khi xóa sản phẩm');
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