<?php
include 'app/views/shares/header.php';
?>

<h1>Sửa sản phẩm</h1>
<div id="alert-container"></div>

<?php if (!isset($editId) || !is_numeric($editId) || $editId <= 0): ?>
    <div class="alert alert-danger">
        Lỗi: ID sản phẩm không hợp lệ. Vui lòng quay lại <a href="/webbanhang/Product/list">danh sách sản phẩm</a>.
    </div>
<?php else: ?>
<form id="edit-product-form">
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($editId) ?>">
    <input type="hidden" id="image-path" name="image_path" value="">
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
        <input type="number" id="price" name="price" class="form-control" step="0.01" required min="0">
    </div>
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">Chọn danh mục</option>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/gif">
        <small class="text-muted">Chọn ảnh mới nếu muốn thay đổi (JPEG, PNG, GIF - tối đa 5MB)</small>
        <div class="mt-2">
            <img id="image-preview" src="" alt="Ảnh sản phẩm" style="max-width: 200px; display: none;">
            <div id="current-image-info" style="display: none;">
                <small class="text-muted">Ảnh hiện tại: <span id="current-image-name"></span></small>
                <button type="button" id="remove-image" class="btn btn-sm btn-danger ml-2">Xóa ảnh</button>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>
<a href="/webbanhang/Product/list" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
<?php endif; ?>
<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productId = <?= json_encode($editId ?? null) ?>;
    if (!productId || isNaN(productId) || productId <= 0) {
        showAlert('danger', 'ID sản phẩm không hợp lệ');
        return;
    }

    let originalProductData = null;
    let newImagePath = null;

    // Load product data and categories
    Promise.all([
        fetch(`/webbanhang/api/product/${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Không tìm thấy sản phẩm (HTTP ${response.status})`);
                }
                return response.json();
            }),
        fetch('/webbanhang/api/category')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Lỗi khi tải danh mục (HTTP ${response.status})`);
                }
                return response.json();
            })
    ])
    .then(([productData, categoryData]) => {
        console.log('Product Data:', productData);
        console.log('Category Data:', categoryData);

        if (!productData || !productData.id) {
            throw new Error('Dữ liệu sản phẩm không hợp lệ');
        }

        originalProductData = productData;

        // Fill product data into form
        document.getElementById('id').value = productData.id;
        document.getElementById('name').value = productData.name || '';
        document.getElementById('description').value = productData.description || '';
        document.getElementById('price').value = productData.price || '';

        // Handle image display
        if (productData.image) {
            const imagePreview = document.getElementById('image-preview');
            const currentImageInfo = document.getElementById('current-image-info');
            const currentImageName = document.getElementById('current-image-name');
            const imagePathInput = document.getElementById('image-path');

            let imagePath = productData.image;
            if (!imagePath.startsWith('/webbanhang/')) {
                if (imagePath.startsWith('public/')) {
                    imagePath = `/webbanhang/${imagePath}`;
                } else if (imagePath.startsWith('/public/')) {
                    imagePath = `/webbanhang${imagePath}`;
                } else {
                    imagePath = `/webbanhang/public/images/${imagePath}`;
                }
            }

            imagePreview.src = imagePath;
            imagePreview.onload = () => {
                imagePreview.style.display = 'block';
            };
            imagePreview.onerror = () => {
                console.error('Hình ảnh không tồn tại:', imagePath);
                imagePreview.style.display = 'none';
            };

            currentImageName.textContent = productData.image.split('/').pop();
            currentImageInfo.style.display = 'block';
            imagePathInput.value = productData.image;
        }

        // Populate category dropdown
        const categorySelect = document.getElementById('category_id');
        if (categoryData && categoryData.length > 0) {
            categorySelect.innerHTML = '<option value="">Chọn danh mục</option>';
            categoryData.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
            if (productData.category_id) {
                categorySelect.value = productData.category_id;
            }
        } else {
            console.warn('Không có danh mục nào được tải');
            categorySelect.innerHTML = '<option value="">Không có danh mục</option>';
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        showAlert('danger', `Không thể tải thông tin sản phẩm hoặc danh mục: ${error.message}`);
    });

    // Handle image upload and preview
    document.getElementById('image')?.addEventListener('change', function(event) {
        const imagePreview = document.getElementById('image-preview');
        const currentImageInfo = document.getElementById('current-image-info');
        const imagePathInput = document.getElementById('image-path');
        const file = event.target.files[0];

        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showAlert('warning', 'Vui lòng chọn file hình ảnh (JPEG, PNG, GIF)');
                this.value = '';
                return;
            }

            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                showAlert('warning', 'Kích thước file không được vượt quá 5MB');
                this.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                currentImageInfo.style.display = 'none';
            };
            reader.readAsDataURL(file);

            // Upload image to server
            const formData = new FormData();
            formData.append('image', file);
            fetch('/webbanhang/api/upload-image', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Lỗi khi upload ảnh (HTTP ${response.status})`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.image_path) {
                    newImagePath = data.image_path;
                    imagePathInput.value = newImagePath;
                    console.log('Uploaded image path:', newImagePath);
                } else {
                    throw new Error(data.message || 'Lỗi khi upload ảnh');
                }
            })
            .catch(error => {
                console.error('Lỗi upload ảnh:', error);
                showAlert('danger', `Không thể upload ảnh: ${error.message}`);
                this.value = '';
                imagePreview.style.display = 'none';
                if (originalProductData && originalProductData.image) {
                    let imagePath = originalProductData.image;
                    if (!imagePath.startsWith('/webbanhang/')) {
                        imagePath = `/webbanhang/public/images/${imagePath}`;
                    }   
                    imagePreview.src = imagePath;
                    imagePreview.style.display = 'block';
                    currentImageInfo.style.display = 'block';
                    imagePathInput.value = originalProductData.image;
                }
            });
        } else if (originalProductData && originalProductData.image) {
            let imagePath = originalProductData.image;
            if (!imagePath.startsWith('/webbanhang/')) {
                imagePath = `/webbanhang/public/images/${imagePath}`;
            }
            imagePreview.src = imagePath;
            imagePreview.style.display = 'block';
            currentImageInfo.style.display = 'block';
            imagePathInput.value = originalProductData.image;
            newImagePath = originalProductData.image;
        }
    });

    // Handle remove image
    document.getElementById('remove-image')?.addEventListener('click', function() {
        const imagePreview = document.getElementById('image-preview');
        const currentImageInfo = document.getElementById('current-image-info');
        const imagePathInput = document.getElementById('image-path');
        const imageInput = document.getElementById('image');

        imagePreview.src = '';
        imagePreview.style.display = 'none';
        currentImageInfo.style.display = 'none';
        imagePathInput.value = '';
        imageInput.value = '';
        newImagePath = null;
        showAlert('info', 'Ảnh hiện tại đã được xóa.');
    });

    // Handle form submission
    document.getElementById('edit-product-form')?.addEventListener('submit', function(event) {
        event.preventDefault();

        // Validate form data
        const name = document.getElementById('name').value.trim();
        const description = document.getElementById('description').value.trim();
        const price = parseFloat(document.getElementById('price').value);
        const categoryId = document.getElementById('category_id').value;

        if (!name) {
            showAlert('danger', 'Tên sản phẩm không được để trống');
            document.getElementById('name').focus();
            return;
        }
        if (name.length < 2) {
            showAlert('danger', 'Tên sản phẩm phải có ít nhất 2 ký tự');
            document.getElementById('name').focus();
            return;
        }
        if (!description) {
            showAlert('danger', 'Mô tả không được để trống');
            document.getElementById('description').focus();
            return;
        }
        if (description.length < 10) {
            showAlert('danger', 'Mô tả phải có ít nhất 10 ký tự');
            document.getElementById('description').focus();
            return;
        }
        if (isNaN(price) || price < 0) {
            showAlert('danger', 'Giá sản phẩm không hợp lệ');
            document.getElementById('price').focus();
            return;
        }
        if (!categoryId) {
            showAlert('danger', 'Vui lòng chọn danh mục');
            document.getElementById('category_id').focus();
            return;
        }

        const jsonData = {
            id: parseInt(productId),
            name: name,
            description: description,
            price: price,
            category_id: parseInt(categoryId),
            image: newImagePath
        };

        console.log('JSON Data being sent:', jsonData);

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang cập nhật...';

        fetch(`/webbanhang/api/product/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.text().then(text => {
                    let errorMessage;
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorData.error || JSON.stringify(errorData);
                    } catch (e) {
                        errorMessage = text || `HTTP ${response.status}`;
                    }
                    throw new Error(`Lỗi khi cập nhật sản phẩm (${response.status}): ${errorMessage}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data);
            if (data.success || data.message === 'Product updated successfully') {
                showAlert('success', 'Cập nhật sản phẩm thành công!');
                setTimeout(() => {
                    window.location.href = '/webbanhang/Product/list';
                }, 1500);
            } else {
                let errorMessage = 'Cập nhật sản phẩm thất bại';
                if (data.errors) {
                    errorMessage += ': ' + (typeof data.errors === 'object' ? Object.values(data.errors).join(', ') : data.errors);
                } else if (data.message) {
                    errorMessage += ': ' + data.message;
                }
                showAlert('danger', errorMessage);
            }
        })
        .catch(error => {
            console.error('Lỗi cập nhật sản phẩm:', error);
            showAlert('danger', `Lỗi khi cập nhật sản phẩm: ${error.message}`);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        });
    });

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertId = 'alert-' + Date.now();
        alertContainer.innerHTML = `
            <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        if (type === 'success') {
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});
</script>