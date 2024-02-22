<?php
session_start();

// Assuming you have already established a database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "student";
$con = mysqli_connect($host, $username, $password, $database);

if ($con == false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve user inputs
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);



    // Check the database for the user with the provided email and hashed password
    $query = "SELECT * FROM student_details WHERE sd_email = '$email' AND sd_password = '$password'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION["logged_in"] = true;
            $_SESSION['email'] = $row['sd_email'];
            $_SESSION['name'] = $row['sd_first_name'];
            $_SESSION['profile_pic'] = $row['sd_image'];
            $_SESSION['student_id'] = $row['sd_student_id'];

            // You can customize the response data based on your needs
            $response = array('success' => true, 'redirectURL' => 'student-listing.php');
            echo json_encode($response);
        } else {

            $response = array('success' => false, 'message' => 'Incorrect login credentials ' . $email);
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'message' => 'Database error: ' . mysqli_error($con));
        echo json_encode($response);
    }

    // Close the database connection
    mysqli_close($con);
} else {
    $response = array('success' => false, 'message' => 'Invalid request');
    echo json_encode($response);
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
