<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Cập nhật hồ sơ cá nhân </h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['message'];
            unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <form action="/webbanhang/account/updateProfileUser" method="POST" enctype="multipart/form-data">
        <!-- Hidden username để truyền vào controller -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($account->username); ?>" disabled>
        </div>


        <div class="mb-3">
            <label for="fullname" class="form-label">Họ tên</label>
            <input type="text" class="form-control <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>" id="fullname" name="fullname" value="<?= htmlspecialchars($account->fullname ?? '') ?>">
            <?php if (isset($errors['fullname'])): ?>
                <div class="invalid-feedback"><?= $errors['fullname'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" id="phone" name="phone" value="<?= htmlspecialchars($account->phone ?? '') ?>">
            <?php if (isset($errors['phone'])): ?>
                <div class="invalid-feedback"><?= $errors['phone'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label><br>
            <?php if (!empty($account->avatar)): ?>
                <img src="/webbanhang/app/public/images/avatar/<?php echo htmlspecialchars($account->avatar); ?>" alt="Avatar" width="100" class="img-thumbnail">
            <?php endif; ?>
            <input type="file" class="form-control <?= isset($errors['avatar']) ? 'is-invalid' : '' ?>" id="avatar" name="avatar">
            <?php if (isset($errors['avatar'])): ?>
                <div class="invalid-feedback"><?= $errors['avatar'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới (nếu muốn đổi)</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="confirmpassword" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control <?= isset($errors['confirmPass']) ? 'is-invalid' : '' ?>" id="confirmpassword" name="confirmpassword">
            <?php if (isset($errors['confirmPass'])): ?>
                <div class="invalid-feedback"><?= $errors['confirmPass'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>

</div>