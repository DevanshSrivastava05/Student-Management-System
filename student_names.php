<?php


$host = "localhost";
$username = "root";
$password = "";
$student = "student";
$con = mysqli_connect($host, $username, $password, $student);

// Check if a letter is provided in the request
if (isset($_GET['letter'])) {

    $letter = $_GET['letter'];
    $query = "SELECT * FROM student_details WHERE sd_first_name LIKE '%$letter%'";
    $result = mysqli_query($con, $query);
} else {
    // If no letter is provided, fetch all names
    $query = "SELECT * FROM student_details";
    $result = mysqli_query($con, $query);
}

$studentNames = array();
while ($row = mysqli_fetch_assoc($result)) {
    $studentNames[] = array(
        'label' => $row['sd_first_name'] . ' ' . $row['sd_last_name'],
        'value' => $row['sd_first_name'] . ' ' . $row['sd_last_name']
    );
}

header('Content-Type: application/json');
echo json_encode($studentNames);
