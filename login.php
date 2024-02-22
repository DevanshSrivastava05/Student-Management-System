<?php include 'header.php'; ?>
<?php
session_start();
$referringPage = isset($_GET['referringPage']) ? $_GET['referringPage'] : '';

$_SESSION['filename'] = "";
$_SESSION['email'] = "";
$_SESSION['name'] = "";
$_SESSION["logged_in"] = false;
$_SESSION['profile_pic'] = "";
$_SESSION['student_id'] = "";

$host = "localhost";
$username = "root";
$password = "";
$student = "student";
$con = mysqli_connect($host, $username, $password, $student);
if ($con == false) {
	die("ERROR: Could not connect. " . mysqli_connect_error());
}

function test_input($data)
{
	global $con;
	$data = trim($data);
	$data = stripslashes($data);
	$data = mysqli_real_escape_string($con, $data);
	$data = htmlspecialchars($data);
	return $data;
}

?>
<div class="container">
	<div class="ManagementSystem">
		<button id="openLoginDialog" class="btn btn-green sign_in">Login</button>


		<button type="button" class="btn btn-green sign_in sign_up pull-right" onclick="window.location.href='index.php'">Sign Up</button>
		<div id="loginDialog" style="display: none">
			<h1 class="form-title">Sign In</h1>

			<div class="signin-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
						<form id="sample" method="post" action="">
							<div class="form-group">
								<label>Email Address <span class="color-danger">*</span></label>
								<input type="text" id="email" name="email" class="form-control" value="" data-rule-email="true" />
								<span id="email_error" style="color: red"> </span>
							</div>
							<div class="form-group">
								<label>Password <span class="color-danger">*</span></label>
								<input type="password" class="form-control" id="password" name="password" value="" data-rule-passwd="true" />
								<span id="password_error" style="color: red"></span>
							</div>
							<div class="form-group" style="display: inline-block; width: 100%;">
								<div class="rememberme_block pull-left">
									<label for="rememberme">
										<input type="checkbox" name="rememberme" id="rememberme" class="" value="yes"> Remember me</label>
								</div>
								<div class="forgot_block pull-right"><a href="password_reset.php">Forgot Password?</a></div>
							</div>
							<div class="form-group">
								<input type="button" value="Sign In" class="btn btn-green sign_in" onclick="validateLoginForm();" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="http://www.myersdaily.org/joseph/javascript/md5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha1/0.6.0/sha1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
	function openLoginDialog() {
		$('#loginDialog').show();
		$('#openLoginDialog').hide();
	}

	// Function to hide the login dialog
	function closeLoginDialog() {
		$('#loginDialog').hide();
	}

	// Attach click event to open the login dialog
	$('#openLoginDialog').click(openLoginDialog);


	function validateLoginForm() {
		console.log('validateLoginForm called');
		var email = $('#email').val();
		var password = $('#password').val();
		var emailError = $('#email_error');
		var passwordError = $('#password_error');
		var isValid = true;

		// Reset error messages
		emailError.html("");
		passwordError.html("");

		// Email validation
		if (email.trim() === "") {
			emailError.html("Please enter Email Address");
			isValid = false;
		} else if (!validateEmail(email)) {
			emailError.html("Invalid email format");
			isValid = false;
		}

		// Password validation
		if (password.trim() === "") {
			passwordError.html("Please enter Password");
			isValid = false;
		}

		if (isValid) {
			var md5pass = md5(password);
			var sha1pass = sha1(md5pass);
			var Password = sha1pass;

			$.ajax({
				type: 'POST',
				url: 'login_handeler.php',
				data: {
					email: email,
					password: Password
				},
				dataType: 'json',
				success: function(response) {
					console.log("response login", response);
					console.log("response success", response.success);
					if (response.success == true) {
						console.log("respose login success", response);
						window.location.href = response.redirectURL;
					} else {
						console.log("respose login failed", response);
						alert("Incorrect Login Credential");
					}
				},
				error: function(error) {
					alert("Error: " + error.responseText);
				}
			});

			return false;
		}
		return false;
	}

	function validateEmail(email) {
		var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return regex.test(email);
	}
</script>

<?php include 'footer.php'; ?>