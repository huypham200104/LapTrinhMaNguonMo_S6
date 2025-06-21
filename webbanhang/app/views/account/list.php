<?php include 'app/views/shares/header.php'; ?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['message'];
        unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<h2>Danh sách người dùng</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Avatar</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user->username ?? ''); ?></td>
                <td><?php echo htmlspecialchars($user->fullname ?? ''); ?></td>
                <td><?php echo htmlspecialchars($user->email ?? ''); ?></td>
                <td><?php echo htmlspecialchars($user->phone ?? ''); ?></td>
                <td>
                    <?php if (!empty($user->avatar)): ?>
                        <img src="/webbanhang/app/public/images/avatar/<?php echo htmlspecialchars($user->avatar); ?>" alt="Avatar" width="50" class="img-thumbnail">
                    <?php else: ?>
                        <span>Không có</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($user->role ?? ''); ?></td>

                <td>
                    <a href="/webbanhang/account/edit/<?php echo $user->username; ?>" class="btn btn-warning btn-sm">Edit</a> |
                    <a href="/webbanhang/account/delete/<?php echo $user->username; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'app/views/shares/footer.php'; ?>