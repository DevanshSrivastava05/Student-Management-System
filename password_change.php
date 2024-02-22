<?php include 'header.php'; ?>

<div class="container">
    <div class="row justify-content-center vh-100">
        <div class="col-md-6">
            <div class="password-reset-form">
                <h2 class="text-center mb-4">Reset Password</h2>
                <form action="password_reset_code.php" method="POST">
                    <input type="hidden" name="password_token" value="<?php if (isset($_GET['token'])) {
                                                                            echo $_GET['token'];
                                                                        } ?>">
                    <div class="form-group">
                        <label for="email">Your Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value=" <?php if (isset($_GET['email'])) {
                                                                                                        echo $_GET['email'];
                                                                                                    } ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new-password" required>
                    </div>
                    <div class="form-group">
                        <label for="repeat-password">Repeat Password</label>
                        <input type="password" class="form-control" id="repeat_password" name="repeat-password" required>
                    </div>
                    <button type="submit" name="password_update" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>