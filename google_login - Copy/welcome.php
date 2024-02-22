<?php
require_once 'header.php';
?>
<?php
session_start();

if (isset($_SESSION['user_email_address'])) {
    echo "Welcome, " . $_SESSION['user_first_name'] . " " . $_SESSION['user_last_name'] . "<br>";
    echo "Email: " . $_SESSION['user_email_address'] . "<br>";
    echo "Gender: " . $_SESSION['user_gender'] . "<br>";

    // Display the user's image
    echo "<img src='images/user_image.jpg' alt='User Image'><br>";

    echo "<a href='index.php'>Logout</a>";
} else {
    header("Location: index.php");
}
?>

<?php
require_once 'footer.php';
?>