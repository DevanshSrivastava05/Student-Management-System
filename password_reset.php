<?php include 'header.php'; ?>

<div class="card login-form">
    <div class="card-body">
        <h3 class="card-title text-center">Reset password</h3>

        <div class="card-text">
            <form action="password_reset_code.php" method="POST">
                <div class="form-group">
                    <label for="email">Enter your email address and we will send you a link to reset your password.</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email address" required>
                </div>

                <button type="submit" name="forgot-pass" class="btn btn-primary btn-block">Send password reset email</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>