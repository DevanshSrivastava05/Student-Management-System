<?php
$host = "localhost";
$username = "root";
$password = "";
$student = "student";
session_start();
$con = mysqli_connect($host, $username, $password, $student);
if (isset($_GET["export-file"])) {
   $filename = "student_details.csv";
   // Create a file pointer connected to the output stream
   $output = fopen('php://output', 'w');
   $header = array("Student Id", "Student Name", "Address", "Gender", "Course", "Email");
   fputcsv($output, $header);
   $studentId = mysqli_real_escape_string($con, $_SESSION['student_id']);
   $query = "SELECT
   sd_student_id,
   sd_first_name,
   CONCAT('\"', sd_address, '\"') as sd_address, 
   sd_gender,
   sd_applied_course,
   sd_email
FROM
   student_details 
WHERE sd_email=\"{$_SESSION['email']}\"";

   $result = mysqli_query($con, $query);
   if (!$result) {
      die('Error in the SQL query: ' . mysqli_error($con));
   }

   header('Content-Type: text/csv');
   header('Content-Disposition: attachment; filename="' . $filename . '"');

   while ($row = mysqli_fetch_assoc($result)) {
      fputcsv($output, [
         $row['sd_student_id'],
         $row['sd_first_name'],
         $row['sd_address'],
         $row['sd_gender'],
         $row['sd_applied_course'],
         $row['sd_email']

      ]);
   }
   fclose($output);
   exit;  // Terminate the script here
}
if (isset($_GET["Import"])) {
   $resultSummary = [];
   $studentId = mysqli_real_escape_string($con, $_SESSION['student_id']);
   if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
      $filename = $_FILES["file"]["tmp_name"];
      $handle = fopen($filename, "r");

      if ($handle !== FALSE) {
         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $studentName = mysqli_real_escape_string($con, $data[1]);
            $studentAddress = mysqli_real_escape_string($con, $data[2]);
            $studentGender = mysqli_real_escape_string($con, $data[3]);
            $studentCourse = mysqli_real_escape_string($con, $data[4]);
            $studentEmail = mysqli_real_escape_string($con, $data[5]);

            // Check if the student already exists in the database
            $existingStudentQuery = "SELECT * FROM student_details WHERE sd_student_id = '$studentId'";
            $existingStudentResult = mysqli_query($con, $existingStudentQuery);

            if (!$existingStudentResult) {
               echo '<script>' . 'Error: ' . mysqli_error($con) . '</script>';
            } else {
               if (mysqli_num_rows($existingStudentResult) > 0) {
                  // Student exists, perform update
                  $updateQuery = "UPDATE student_details
            SET sd_first_name = '$studentName',
                sd_address = '$studentAddress',
                sd_gender = '$studentGender',
                sd_applied_course = '$studentCourse',
                sd_email = '$studentEmail'
            WHERE sd_student_id = '$studentId'";

                  if (mysqli_query($con, $updateQuery)) {
                     echo '<script>' . 'Student with ID ' . $studentId . ' has been updated</script>';
                  } else {
                     echo '<script>' . 'Failed to update student with ID ' . $studentId . '</script>';
                     echo mysqli_error($con);
                  }
               } else {
                  // Student doesn't exist, perform insert
                  $insertQuery = "INSERT INTO student_details (sd_student_id, sd_first_name, sd_address, sd_gender, sd_applied_course, sd_email) VALUES ('$studentId', '$studentName', '$studentAddress', '$studentGender', '$studentCourse', '$studentEmail')";
                  if (mysqli_query($con, $insertQuery)) {
                     echo '<script>' . 'Student with ID ' . $studentId . ' has been inserted</script>';
                  } else {
                     echo '<script>' . 'Failed to insert student with ID ' . $studentId . '</script>';
                  }
               }
            }
         }
         fclose($handle);
      } else {
         echo '<script>' . 'Error opening the CSV file' . '</script>';
      }
   } else {
      echo '<script>' . 'Error uploading the file' . '</script>';
   }
   header('location: save-data.php');
}
