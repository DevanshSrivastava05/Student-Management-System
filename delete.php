<?php
$host = "localhost";
$username = "root";
$password = "";
$student = "student";
$con = mysqli_connect($host, $username, $password, $student);
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $sql = "DELETE
        sd,
        sad
    FROM
        student_details AS sd
    INNER JOIN student_acedemic_details AS sad
    ON
        sd.sd_student_id = sad.sad_student_id
    WHERE
        sd.sd_student_id = '$id'";

    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Record deleted Successfully")</script>';
        header("Location: student-listing.php");
        exit();
    } else {

        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Invalid request"; // Handle cases where 'id' parameter is missing
}
