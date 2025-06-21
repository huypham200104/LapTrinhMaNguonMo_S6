<?php include 'app/views/shares/header.php'; ?>
<?php
if (isset($errors) && count($errors) > 0) {
    echo "<ul>";
    foreach ($errors as $err) {
        echo "<li class='text-danger'>$err</li>";
    }
    echo "</ul>";
}
?>
<div class="card-body p-5 text-center">
    <form class="user" action="/webbanhang/account/save" method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="text" class="form-control form-control-user"
                    id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-user"
                    id="fullname" name="fullname" placeholder="Fullname" required value="<?php echo htmlspecialchars($_POST['fullname'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" class="form-control form-control-user"
                    id="password" name="password" placeholder="Password" required>
            </div>
            <div class="col-sm-6">
                <input type="password" class="form-control form-control-user"
                    id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="email" class="form-control form-control-user"
                    id="email" name="email" placeholder="Email (optional)" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-user"
                    id="phone" name="phone" placeholder="Phone (optional)" maxlength="11" value="<?php echo htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="avatar">Avatar (optional):</label>
            <input type="file" class="form-control-file" id="avatar" name="avatar" accept="image/*">
        </div>
        <input type="hidden" name="role" value="user"> <!-- hoặc cho admin chọn nếu cần -->
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary btn-icon-split p-3">
                Register
            </button>
        </div>
    </form>
</div>
<?php include 'app/views/shares/footer.php'; ?>