<?php
/*if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    $referringPage = basename($_SERVER['PHP_SELF']);

    // Set the 'return_to' parameter based on the referring page

    echo "<script> 
 console.log('this is a Variable:',  $referringPage);
</script>";
    $returnTo = $referringPage == 'student-detail.php' ? 'student-detail' : null;

    // Redirect to the login page with the 'return_to' parameter
    header("Location: login.php?return_to=$returnTo");
    exit;
}
*/
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    $referringPage = basename($_SERVER['PHP_SELF']); // Default to the current page
    if (isset($_GET['referringPage'])) {
        $referringPage = $_GET['referringPage'];
    }
    $_SESSION['referringPage'] = $referringPage;
    // Redirect to the login page with the 'referringPage' parameter
    header("Location: login.php?referringPage=$referringPage");
    exit;
}
