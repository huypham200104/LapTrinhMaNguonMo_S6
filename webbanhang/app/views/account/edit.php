<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Chỉnh sửa thông tin người dùng</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message'];
            unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">ID</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($account->id); ?>" disabled>
        </div>
        <!-- Username (không cho sửa) -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($account->username); ?>" disabled>
        </div>

        <!-- Full Name -->
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($account->fullname); ?>">
            <?php if (isset($errors['fullname'])): ?>
                <small class="text-danger"><?php echo $errors['fullname']; ?></small>
            <?php endif; ?>
        </div>

        <!-- Role -->
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role">
                <option value="admin" <?php echo ($account->role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($account->role == 'user') ? 'selected' : ''; ?>>User</option>
            </select>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($account->phone); ?>">
        </div>

        <!-- Avatar -->
        <div class="form-group">
            <label for="avatar">Avatar</label>

            <?php if (!empty($account->avatar)): ?>
                <div class="mb-3">
                    <label>Ảnh hiện tại:</label>
                    <img src="/webbanhang/app/public/images/avatar/<?php echo htmlspecialchars($account->avatar); ?>" alt="Avatar" width="100" class="img-thumbnail">
                </div>
            <?php endif; ?>

            <input type="file" class="form-control" id="avatar" name="avatar">
            <small class="form-text text-muted">Chọn avatar mới (nếu muốn thay đổi).</small>
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirmpassword">Confirm Password</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword">
            <?php if (isset($errors['confirmPass'])): ?>
                <small class="text-danger"><?php echo $errors['confirmPass']; ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>