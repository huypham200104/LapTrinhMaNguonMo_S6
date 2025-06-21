<?php include 'app/views/shares/header.php'; ?>

<h1 class="text-2xl font-bold mb-4">Chi tiết danh mục</h1>

<?php if ($category): ?>
    <div class="mb-3">
        <strong>Tên danh mục:</strong>
        <p><?= htmlspecialchars($category->name) ?></p>
    </div>
    <div class="mb-3">
        <strong>Mô tả:</strong>
        <p><?= nl2br(htmlspecialchars($category->description)) ?></p>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Không tìm thấy danh mục.</div>
<?php endif; ?>

<a href="/webbanhang/category/list" class="btn btn-secondary">Quay lại</a>

<?php include 'app/views/shares/footer.php'; ?>
