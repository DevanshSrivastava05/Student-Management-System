<?php
include 'header.php';

$host = "localhost";
$username = "root";
$password = "";
$student = "student";

$con = mysqli_connect($host, $username, $password, $student);

function test_input($data)
{
    global $con;
    $data = trim($data);
    $data = stripslashes($data);
    $data = mysqli_real_escape_string($con, $data);
    $data = htmlspecialchars($data);
    return $data;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_password_reset($get_name, $get_email_id, $token)
{
    $mail = new PHPMailer(true);

    // Enable SMTP
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    // SMTP server settings for Gmail
    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'w83417977@gmail.com';
    $mail->Password = 'ekfu qmsx uifg tazx';
    $mail->SMTPSecure = 'tls'; // Use TLS encryption
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom($get_email_id, $get_name);
    $mail->addAddress($get_email_id, $get_name); // Recipient's email and name

    // Email content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Reset Password Notification';
    $email_template = "
        <h2>Hello</h2>
        <h3>You are receiving this email because we received a password reset request from your account.</h3>
        <br><br/>
        <a href='http://localhost/Student_Management_System_HTML/password_change.php?token=$token&email=$get_email_id'>Click me</a>
    ";
    $mail->Body = $email_template;

    $mail->send(); // Send the email
}

if (isset($_POST['forgot-pass'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(uniqid(rand(), true)); // Use a more secure token generation
    $check_email = mysqli_query($con, "SELECT * FROM student_details WHERE sd_email='$email' LIMIT 1");

    if (mysqli_num_rows($check_email) > 0) {
        $row = mysqli_fetch_assoc($check_email);
        $get_name = $row['sd_first_name'];
        $get_email_id = $row['sd_email'];
        $update_token = "UPDATE student_details SET verify_token='$token' WHERE sd_email='$get_email_id' LIMIT 1";
        $result = mysqli_query($con, $update_token);
        if ($result) {
            send_password_reset($get_name, $get_email_id, $token);

            echo '<script type="text/javascript">';
            echo 'alert("We have sent the password reset link to your Email ID!");';
            echo '</script>';
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error in sending Email successfully!");';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("No such Email found!");';
        echo '</script>';
    }
}

if (isset($_POST['password_update'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new-password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['repeat-password']);
    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    $password1 = test_input($new_password);
    $md5pass1 = md5($password1); // Fix variable names
    $sha1pass1 = sha1($md5pass1);

    $Password2 = test_input($confirm_password);
    $md5pass2 = md5($Password2);
    $sha1pass2 = sha1($md5pass2);

    if (!empty($token)) {
        if (!empty($email) && !empty($new_password) && !empty($confirm_password)) {
            $check_token = "SELECT verify_token FROM student_details WHERE verify_token='$token' AND sd_email='$email' LIMIT 1";
            $check_token_run = mysqli_query($con, $check_token);
            if (mysqli_num_rows($check_token_run) > 0) {
                if ($sha1pass1 == $sha1pass2) {
                    $update = mysqli_query($con, "UPDATE student_details SET sd_password='$sha1pass1', verify_token=NULL WHERE sd_email='$email' LIMIT 1");
                    if ($update) {
                        echo '<script type="text/javascript">';
                        echo 'alert("Password reset successfully");';
                        echo 'location="password_change.php?token=' . $token . '&email=' . $email . '";';
                        echo '</script>';
                    } else {
                        echo '<script type="text/javascript">';
                        echo 'alert("Something went wrong");';
                        echo 'location="password_change.php?token=' . $token . '&email=' . $email . '";';
                        echo '</script>';
                    }
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Password and confirm password do not match");';
                    echo 'location="password_change.php?token=' . $token . '&email=' . $email . '";';
                    echo '</script>';
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'alert("Invalid Token");';
                echo 'location="password_change.php?token=' . $token . '&email=' . $email . '";';
                echo '</script>';
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("All Fields are Mandatory");';
            echo 'location="password_change.php?token=' . $token . '&email=' . $email . '";';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("No Token Available");';
        echo 'location="password_reset.php";';
        echo '</script>';
    }
}
?>
<?php include 'footer.php'; ?>
