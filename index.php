<?php include 'header.php'; ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$host = "localhost";
$username = "root";
$password = "";
$student = "student";
session_start();
$con = mysqli_connect($host, $username, $password, $student);
if ($con == False) {
	die("ERROR: Could not connect. "
		. mysqli_connect_error());
}
function test_input($data)
{
	$host = "localhost";
	$username = "root";
	$password = "";
	$student = "student";
	$con = mysqli_connect($host, $username, $password, $student);
	if ($con == False) {
		die("ERROR: Could not connect. "
			. mysqli_connect_error());
	}
	$data = trim($data);
	$data = stripcslashes($data);
	$data = mysqli_real_escape_string($con, $data);
	$data = htmlspecialchars($data);
	return $data;
}
function Validate_date($date)
{
	$dateArr = explode("-", $date);
	if (count($dateArr) != 3) {
		return False;
	}
	if (!checkdate($dateArr[1], $dateArr[0], $dateArr[2])) {
		return False;
	}
	if ($dateArr[1] == 2 && !($dateArr[2] % 4 == 0 && ($dateArr[2] % 100 != 0 || $dateArr[2] % 400 == 0) ? $dateArr[0] <= 29 : $dateArr[0] <= 28)) {
		return False;
	}
	return True;
}

$first_name_err = $last_name_err = $Password_err = $confirm_password_err = $email_err = $Email_err = $Mobile_num_err = $Date_err = $Gender_err = $Address_err = $City_err = $Zip_err = $State_err = $Country_err = "";
$first_name = $last_name = $Password = $Password1 = $confirm_password = $Email = $Mobile_num = $Date = $Date2 = $Date1 = $Gender = $Address = $City = $Zip = $State = $Country = $CourseAP = "";
$md5pass = $sha1pass = $password = "";
$X_board = $X_perc = $X_yop = $XII_board = $XII_perc = $XII_yop = "";
$file_err = $img_err = '';
$filename = $tempname = $image_size_err = $image_type_err = $file_size_err = $file_type_err = "";
$edit_mode = false; // Flag to determine if it's edit mode



if (isset($_GET['edit'])) {
	// Check if edit parameter is in the URL
	$edit_mode = true;
	$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
}

if ($edit_mode && $student_id > 0) {


	$student_id = $_GET['student_id']; // Get the student ID from the URL
	global  $student_id;
	$fetchdeatails = mysqli_query($con, "SELECT * FROM student_details WHERE sd_student_id = $student_id");

	while ($rows = mysqli_fetch_assoc($fetchdeatails)) {
		$first_name = test_input($rows['sd_first_name']);
		$last_name = test_input($rows['sd_last_name']);
		$Date = test_input($rows['sd_dob']);
		$Email = test_input($rows['sd_email']);
		$Mobile_num = test_input($rows['sd_phone']);
		$Gender = test_input($rows['sd_gender']);
		$Address = test_input($rows['sd_address']);
		$City = test_input($rows['sd_city']);
		$Zip = test_input($rows['sd_zip_code']);
		$password = test_input($rows['sd_password']);
		$confirm_password = test_input($rows['sd_password']);
		$hobbies = explode(',', $rows['sd_hobbies']);
		$State = test_input($rows['sd_state']);
		$Country = test_input($rows['sd_country']);
		$CourseAP = test_input($rows['sd_applied_course']);
		$sd_image = test_input($rows['sd_image']);
		$sd_date_added = test_input($rows['sd_date_added']);
		$sd_date_modified = test_input($rows['sd_date_modified']);
	}


	// Fetch data for Class X
	$sqlX = "SELECT * FROM student_acedemic_details WHERE sad_student_id = '$student_id' AND sad_course_name = 'X'";
	$resultX = mysqli_query($con, $sqlX);
	if ($rowX = mysqli_fetch_assoc($resultX)) {

		$X_board = test_input($rowX['sad_board']);
		$X_perc = test_input($rowX['sad_percentage']);
		$X_yop = test_input($rowX['sad_year_of_passing']);
	}
	// Fetch data for Class XII
	$sqlXII = "SELECT * FROM student_acedemic_details WHERE sad_student_id = '$student_id' AND sad_course_name = 'XII'";
	$resultXII = mysqli_query($con, $sqlXII);
	if ($rowXII = mysqli_fetch_assoc($resultXII)) {
		$XII_board = test_input($rowXII['sad_board']);
		$XII_perc = test_input($rowXII['sad_percentage']);
		$XII_yop = test_input($rowXII['sad_year_of_passing']);
	}

	// handle hobbies update
	if (isset($_POST['hobby'])) {
		// Get selected hobbies from the form
		$newHobbies = isset($_POST['hobby']) ? $_POST['hobby'] : [];

		// Combine the selected hobbies into a comma-separated string
		$newHobbiesString = implode(',', $newHobbies);

		// Update the user's hobbies in the database
		$updateHobbiesQuery = "UPDATE student_details SET sd_hobbies = '$newHobbiesString' WHERE sd_student_id = $student_id";

		if (mysqli_query($con, $updateHobbiesQuery)) {
			//"Hobbies updated successfully.";
		} else {
			echo "Error updating hobbies: " . mysqli_error($con);
		}
	}
	// Validate and handle image file upload

	if ($edit_mode && $_SERVER["REQUEST_METHOD"] == "POST") {
		// Handle image file upload
		if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
			$imageDirectory = "images/";
			$imageFile = $imageDirectory . basename($_FILES['image_file']['name']);

			// Validate the image file type
			$allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
			$imageFileType = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));

			// Validate the image file size (2MB maximum)
			$maxImageSize = 2 * 1024 * 1024; // 2MB
			$imageSize = $_FILES['image_file']['size'];

			if (in_array($imageFileType, $allowedImageTypes)) {

				if ($imageSize <= $maxImageSize) {
					if (move_uploaded_file($_FILES['image_file']['tmp_name'], $imageFile)) {
						$imageName = $_FILES['image_file']['name'];
						$updateImageQuery = "UPDATE student_details SET sd_image = '$imageName' WHERE sd_student_id = $student_id";
						if (mysqli_query($con, $updateImageQuery)) {
							echo "Image uploaded and updated successfully.";
						} else {
							echo "Error updating image in the database: " . mysqli_error($con);
						}
					} else {
						echo "Error uploading image.";
					}
				} else {
					$image_size_err = "Image file size exceeds the maximum allowed size (2MB).";
				}
			} else {
				$image_type_err = "Invalid image file format. Allowed formats: jpg, jpeg, png, gif.";
			}
		}


		// Handle document file upload
		$uploadedFiles = [];
		if (isset($_FILES['file1'])) {
			foreach ($_FILES['file1']['name'] as $index => $fileName) {
				if (!empty($fileName) && $_FILES['file1']['error'][$index] === UPLOAD_ERR_OK) {
					$fileType = $_FILES['file1']['type'][$index];
					$fileSize = $_FILES['file1']['size'][$index];

					// Validate the document file type
					$allowedDocumentTypes = ['pdf', 'doc'];
					$documentFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

					// Validate the document file size (2MB maximum)
					$maxDocumentSize = 2 * 1024 * 1024; // 2MB

					if (in_array($documentFileType, $allowedDocumentTypes)) {
						if ($fileSize <= $maxDocumentSize) {
							// The document file type and size are valid

							// SQL query to insert file details into the database
							$updateFileQuery = "INSERT INTO uploaded_files (up_student_id, file_name, file_type, file_size, upload_date) VALUES ('$student_id', '$fileName', '$fileType', $fileSize, NOW())";

							if (mysqli_query($con, $updateFileQuery)) {
								// Database update successful
								$uploadedFiles[] = $fileName; // Store uploaded file names.
								$tempFilePath = $_FILES['file1']['tmp_name'][$index];
								$newFilePath = "uploads/" . $fileName;

								if (move_uploaded_file($tempFilePath, $newFilePath)) {
									// File successfully moved to the target directory
									echo "File uploaded and updated successfully: " . $fileName;
								} else {
									echo "Error uploading File: " . $fileName;
								}
							} else {
								echo "Error adding files to the database: " . mysqli_error($con);
							}
						} else {
							$file_size_err = "Document file size exceeds the maximum allowed size (2MB).";
						}
					} else {
						$file_type_err = "Invalid document file format. Allowed formats: pdf, doc";
					}
				}
			}
		}
		$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

		// Fetch all files for a particular student ID
		$sql = mysqli_query($con, "SELECT * FROM uploaded_files WHERE up_student_id = $student_id");

		while ($row = mysqli_fetch_assoc($sql)) {
			$uploadedFiles[] = $row['file_name']; // Store all file names in the array.
		}


		if (!empty($uploadedFiles)) {
			// Create a temporary zip file
			$zipFileName = 'downloaded_files_' . $_SESSION['student_id'] . '.zip';
			$zip = new ZipArchive;
			if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
				// Add all uploaded files to the zip archive
				foreach ($uploadedFiles as $fileName) {
					$filePath = "uploads/" . $fileName;
					$zip->addFile($filePath, $fileName);
				}

				$zip->close();
			}
		}
		if (!empty($uploadedFiles)) {
			$ZipF = 1;
		} else {
			$ZipF = 0;
		}
	} else {
		// The code below will only run when the page is not submitted via POST

		// Check if there are any files in the "uploads" directory and create a zip file

		// Fetch all files for a particular student ID
		$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

		// Fetch all files for a particular student ID
		$sql = mysqli_query($con, "SELECT * FROM uploaded_files WHERE up_student_id = $student_id");

		while ($row = mysqli_fetch_assoc($sql)) {
			$uploadedFiles[] = $row['file_name']; // Store all file names in the array.
		}



		if (!empty($uploadedFiles)) {
			// Create a temporary zip file
			$zipFileName = 'downloaded_files_' . $student_id . '.zip';
			$zip = new ZipArchive;

			if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
				// Add all uploaded files to the zip archive
				foreach ($uploadedFiles as $fileName) {
					$filePath = "uploads/" . $fileName;
					$zip->addFile($filePath, $fileName);
				}

				$zip->close();
			}
		}
		if (!empty($uploadedFiles)) {
			$ZipF = 1;
		} else {
			$ZipF = 0;
		}
	}
}


//Checking Inputs in EDIT MODE
if ($_SERVER["REQUEST_METHOD"] == "POST" && $edit_mode) {
	if (empty($_POST["first_name"])) {
		$first_name_err = "Please enter your first name";
	} else {
		$first_name = test_input($_POST["first_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
			$first_name_err = "First Name should only contains Alphabets";
		}
	}

	if (empty($_POST["last_name"])) {
		$last_name_err = "Please enter your last name";
	} else {
		$last_name = test_input($_POST["last_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
			$last_name_err = "Last Name should only contains Alphabets";
		}
	}
	if (empty($_POST["date"])) {
		$Date_err = "Please enter your date of birth";
	} else {
		$Date = $_POST['date'];

		if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $Date)) {
			$Date_err = "Please enter valid date";
		} else if (!Validate_date(($Date))) {
			$Date_err = "Please enter valid date";
		}
		$dateParts = explode('-', $Date);
		$Date2 = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
	}
	if (empty($_POST["email"])) {
		$Email_err = "Please enter your email address";
	} else {
		$Email = test_input($_POST["email"]);
		if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
			$Email_err = "Invalid email format";
		}
	}

	if (empty($_POST["password"])) {
		$Password_err = "Please enter your password";
	} else {
		$password = test_input($_POST["password"]);
		$md5pass = md5($password);
		$sha1pass = sha1($md5pass);
		$Password1 = $sha1pass;
	}

	if (empty($_POST["confirm_password"])) {
		$confirm_password_err = "Please confirm your password";
	} else {
		$confirm_password = test_input($_POST["confirm_password"]);
		if ($password !== $confirm_password) {
			$confirm_password_err = "Passwords do not match";
		}
	}

	if (empty($_POST["contact_no"])) {
		$Mobile_num_err = "Please enter your mobile number";
	} else {
		$Mobile_num = test_input($_POST["contact_no"]);
		if (!preg_match('/^\d{10}$/', $Mobile_num)) {
			$Mobile_num_err = "Please enter a valid mobile number";
		}
	}

	if (empty($_POST["gender"])) {
		$Gender_err = "Please select your gender";
	}

	if (empty($_POST["address_line_1"])) {
		$Address_err = "Please enter your address";
	} else {
		$Address = test_input($_POST["address_line_1"]);
	}

	if (empty($_POST["city"])) {
		$City_err = "Please enter your city name";
	} else {
		$City = test_input($_POST["city"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $City)) {
			$City_error = "City name should only contains Alphabets";
		}
	}

	if (empty($_POST["pincode"])) {
		$Zip_err = "Please enter your zipcode";
	} else {
		$Zip = test_input($_POST["pincode"]);
		if (!preg_match('/^\d{6}$/', $Zip)) {
			$Zip_err = "Please enter valid Pincode";
		}
	}

	if (empty($_POST["state"])) {
		$State_err = "Please enter your state";
	} else {
		$State = test_input($_POST["state"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $State)) {
			$State_err = "State name should only contains Alphabets";
		}
	}

	if (empty($_POST["course"])) {
		$CourseAP_err = "Please select a course";
	} else {
		$CourseAP = test_input($_POST["course"]);
	}

	if (empty($_POST["country"])) {
		$Country_err = "Please select your country";
	} else {
		$Country = test_input($_POST["country"]);
	}
	if (isset($_POST['X-board'])) {
		$X_board = test_input($_POST['X-board']);
	}

	if (isset($_POST['X-perc'])) {
		$X_perc = test_input($_POST['X-perc']);
	}

	if (isset($_POST['X-yop'])) {
		$X_yop = test_input($_POST['X-yop']);
	}

	if (isset($_POST['XII-board'])) {
		$XII_board = test_input($_POST['XII-board']);
	}

	if (isset($_POST['XII-perc'])) {
		$XII_perc = test_input($_POST['XII-perc']);
	}

	if (isset($_POST['XII-yop'])) {
		$XII_yop = test_input($_POST['XII-yop']);
	}
}

// Handling form submission in edit mode

if ($_SERVER["REQUEST_METHOD"] == "POST" && $edit_mode &&  empty($first_name_err) && empty($email_err) && empty($last_name_err) && empty($Password_err) && empty($confirm_password_err) && empty($Email_err) && empty($Mobile_num_err) && empty($Date_err) && empty($Gender_err) && empty($Address_err) && empty($City_err) && empty($Zip_err) && empty($State_err) && empty($Country_err) && empty($image_size_err) && empty($image_type_err) && empty($file_size_err) && empty($file_type_err)) {

	$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
	// Construct the SQL query to update student details
	$update_query = "UPDATE student_details SET
        sd_first_name = '$first_name',
        sd_last_name = '$last_name',
        sd_dob = '$Date2',
        sd_email = '$Email',
        sd_phone = '$Mobile_num',
        sd_gender = '$Gender',
        sd_address = '$Address',
        sd_city = '$City',
        sd_zip_code = '$Zip',
        sd_state = '$State',
        sd_country = '$Country',
        sd_applied_course = '$CourseAP'
        WHERE sd_student_id = $student_id";

	// Execute the query to update student details
	$result = mysqli_query($con, $update_query);

	// Check if the update was successful
	if ($result) {
		// Successfully updated student details

		// Update academic details for X
		$update_query_X = "UPDATE student_acedemic_details SET 
            sad_board = '$X_board',
            sad_percentage = '$X_perc',
            sad_year_of_passing = '$X_yop'
            WHERE sad_student_id = $student_id AND sad_course_name = 'X'";

		$resultX = mysqli_query($con, $update_query_X);

		// Check if the update for X details was successful
		if ($resultX) {
			// Successfully updated X details

			// Update academic details for XII
			$update_query_XII = "UPDATE student_acedemic_details SET 
                sad_board = '$XII_board',
                sad_percentage = '$XII_perc',
                sad_year_of_passing = '$XII_yop'
                WHERE sad_student_id = $student_id AND sad_course_name = 'XII'";

			$resultXII = mysqli_query($con, $update_query_XII);

			// Check if the update for XII details was successful
			if ($resultXII) {
				// Successfully updated XII details

				// Check if all updates were successful
				if ($result && $resultX && $resultXII) {
					echo '<script>alert("All Records Updated Successfully")</script>';
				} else {
					echo '<script>alert("Failed to update Records: ' . mysqli_errno($con) . '")</script>';
				}
			} else {
				echo "Failed to update XII details: " . mysqli_error($con);
			}
		} else {
			echo "Failed to update X details: " . mysqli_error($con);
		}
	} else {
		echo "Failed to update student details: " . mysqli_error($con);
	}
} elseif (!$edit_mode) {
	// You can add code here for handling the situation when edit_mode is false
}


//Checking input in REGISTRATION MODE
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$edit_mode) {


	if (empty($_POST["first_name"])) {
		$first_name_err = "Please enter your first name";
	} else {
		$first_name = test_input($_POST["first_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
			$first_name_err = "First Name should only contains Alphabets";
		}
	}

	if (empty($_POST["last_name"])) {
		$last_name_err = "Please enter your last name";
	} else {
		$last_name = test_input($_POST["last_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
			$last_name_err = "Last Name should only contains Alphabets";
		}
	}
	if (empty($_POST["date"])) {
		$Date_err = "Please enter your date of birth";
	} else {
		$Date = $_POST['date'];

		if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $Date)) {
			$Date_err = "Please enter valid date";
		} else if (!Validate_date(($Date))) {
			$Date_err = "Please enter valid date";
		}
		$dateParts = explode('-', $Date);
		$Date2 = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
	}
	if (empty($_POST["email"])) {
		$Email_err = "Please enter your email address";
	} else {
		$Email = test_input($_POST["email"]);
		if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
			$Email_err = "Invalid email format";
		}
	}

	if (empty($_POST["password"])) {
		$Password_err = "Please enter your password";
	} else {
		$password = test_input($_POST["password"]);
		$md5pass = md5($password);
		$sha1pass = sha1($md5pass);
		$Password1 = $sha1pass;
	}

	if (empty($_POST["confirm_password"])) {
		$confirm_password_err = "Please confirm your password";
	} else {
		$confirm_password = test_input($_POST["confirm_password"]);
		if ($password !== $confirm_password) {
			$confirm_password_err = "Passwords do not match";
		}
	}

	if (empty($_POST["contact_no"])) {
		$Mobile_num_err = "Please enter your mobile number";
	} else {
		$Mobile_num = test_input($_POST["contact_no"]);
		if (!preg_match('/^\d{10}$/', $Mobile_num)) {
			$Mobile_num_err = "Please enter a valid mobile number";
		}
	}

	if (empty($_POST["gender"])) {
		$Gender_err = "Please select your gender";
	}

	if (empty($_POST["address_line_1"])) {
		$Address_err = "Please enter your address";
	} else {
		$Address = test_input($_POST["address_line_1"]);
	}

	if (empty($_POST["city"])) {
		$City_err = "Please enter your city name";
	} else {
		$City = test_input($_POST["city"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $City)) {
			$City_error = "City name should only contains Alphabets";
		}
	}

	if (empty($_POST["pincode"])) {
		$Zip_err = "Please enter your zipcode";
	} else {
		$Zip = test_input($_POST["pincode"]);
		if (!preg_match('/^\d{6}$/', $Zip)) {
			$Zip_err = "Please enter valid Pincode";
		}
	}

	if (empty($_POST["state"])) {
		$State_err = "Please enter your state";
	} else {
		$State = test_input($_POST["state"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $State)) {
			$State_err = "State name should only contains Alphabets";
		}
	}

	if (empty($_POST["course"])) {
		$CourseAP_err = "Please select a course";
	} else {
		$CourseAP = test_input($_POST["course"]);
	}

	if (empty($_POST["country"])) {
		$Country_err = "Please select your country";
	} else {
		$Country = test_input($_POST["country"]);
	}
	$X_board = test_input($_POST["X-board"]);
	$X_perc = test_input($_POST["X-perc"]);
	$X_yop = test_input($_POST["X-yop"]);
	$XII_board = test_input($_POST["XII-board"]);
	$XII_perc = test_input($_POST["XII-perc"]);
	$XII_yop = test_input($_POST["XII-yop"]);
}
if (!$edit_mode && $_SERVER["REQUEST_METHOD"] == "POST") {
	$allowedExtensions = ["jpg", "jpeg", "gif", "png"]; // Allowed file extensions
	$maxFileSize = 2 * 1024 * 1024; // 2MB in bytes (2 * 1024 * 1024 bytes)

	// Check if the image file field is empty
	if (empty($_FILES["file"]["name"])) {
		$img_err = "Image upload is mandatory.";
	} else {
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"];
		} else {
			$filename = $_FILES["file"]["name"];
			$tempname = $_FILES["file"]["tmp_name"];
			$folder = "images/" . $filename;

			// Check file size and extension for the image
			if ($_FILES["file"]["size"] > $maxFileSize) {
				$image_size_err = "Please upload an image under 2MB";
			} elseif (!in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), $allowedExtensions)) {
				$image_type_err = "Please upload an image in JPG, JPEG, or PNG format only";
			} else {
				// Move the uploaded image to the specified directory
				if (move_uploaded_file($tempname, $folder)) {
					echo "Profile pic uploaded successfully";
				} else {
					echo "Error uploading image";
				}
			}
		}
	}

	// Check if the file file field is empty
	if (empty($_FILES["file2"]["name"])) {
		$file_err = "File upload is mandatory.";
	} else {
		// Handle File Upload
		$upload_dir = "uploads/";
		$file_name = $_FILES["file2"]["name"];
		$file_tmp = $_FILES["file2"]["tmp_name"];
		$file_size = $_FILES["file2"]["size"];
		$file_type = $_FILES["file2"]["type"];

		// Check if the file type is allowed
		$allowed_types = array("application/pdf", "application/msword");

		if ($_FILES["file2"]["error"] > 0) {
			echo "Error: " . $_FILES["file2"]["error"];
		} elseif (!in_array($file_type, $allowed_types)) {
			$file_type_err = "Invalid file type. Allowed file types are PDF and DOC.";
		} elseif ($file_size > $maxFileSize) {
			$file_size_err = "Please upload a file under 2MB";
		} else {
			// Move the uploaded file to the specified directory
			if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
				echo "File uploaded successfully";
			} else {
				echo "Error uploading file";
			}
		}
	}
}



// Handling form submission IN REGISTRATION MODE

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$edit_mode && empty($first_name_err) && empty($email_err) && empty($last_name_err) && empty($Password_err) && empty($confirm_password_err) && empty($Email_err) && empty($Mobile_num_err) && empty($Date_err) && empty($Gender_err) && empty($Address_err) && empty($City_err) && empty($Zip_err) && empty($State_err) && empty($Country_err) && empty($image_size_err) && empty($image_type_err) && empty($file_size_err) && empty($file_type_err) && empty($img_err) && empty($file_err)) {
	$first_name =  $_REQUEST['first_name'];
	$last_name = $_REQUEST['last_name'];
	$Gender =  $_REQUEST['gender'];
	$Email = $_REQUEST['email'];
	$Password = $Password1;
	$Mobile_num = $_REQUEST['contact_no'];
	$Date1 = $Date2;
	$Address = $_REQUEST['address_line_1'];
	$City = $_REQUEST['city'];
	$Zip = $_REQUEST['pincode'];
	$State = $_REQUEST['state'];
	$Country = $_REQUEST['country'];
	if (isset($_POST["course"])) {
		$CourseAP = test_input($_POST["course"]);
	} else {
		$CourseAP = '';
	}
	$selectedHobbies1 = [];
	if (isset($_POST['hobby']) && is_array($_POST['hobby'])) {
		foreach ($_POST['hobby'] as $hobby) {
			if (!empty($hobby)) {
				$selectedHobbies1[] = $hobby;
			}
		}
		$hobbies = implode(', ', $selectedHobbies1);
	}
	$query = mysqli_query($con, "SELECT *FROM student_details Where sd_email='$Email'");
	$count = mysqli_num_rows($query);
	if ($count > 0) {
		$email_err = "This email address is already registered.";
	} else {
		$sql = mysqli_query($con, "INSERT INTO student_details(sd_first_name,sd_last_name,	
		sd_dob,	
		sd_email,	
		sd_password,
		sd_phone,
		sd_gender,
		sd_address,	
		sd_city,	
		sd_zip_code,	
		sd_state,	
		sd_country,	
		sd_hobbies,	
		sd_applied_course,
		sd_image) VALUES ('$first_name',
	'$last_name','$Date1','$Email','$Password','$Mobile_num','$Gender','$Address','$City','$Zip','$State','$Country','$hobbies','$CourseAP','$filename')");
		if (!$sql) {
			echo  mysqli_error($con);
		}

		$X_board = $_REQUEST['X-board'];
		$X_perc = $_REQUEST['X-perc'];
		$X_yop = $_REQUEST['X-yop'];
		$XII_board = $_REQUEST['XII-board'];
		$XII_perc = $_REQUEST['XII-perc'];
		$XII_yop = $_REQUEST['XII-yop'];
		$sad_course_name_x = "X";
		$sad_course_name_xii = "XII";
		$sad_id = mysqli_insert_id($con);
		$sql1 = mysqli_query($con, "INSERT INTO student_acedemic_details (sad_student_id,sad_course_name,sad_board,sad_percentage,sad_year_of_passing )VALUES('$sad_id','$sad_course_name_x','$X_board', '$X_perc', '$X_yop')");
		$sql2 = mysqli_query($con, "INSERT INTO student_acedemic_details (sad_student_id,sad_course_name,sad_board,sad_percentage,sad_year_of_passing )VALUES('$sad_id','$sad_course_name_xii','$XII_board', '$XII_perc', '$XII_yop')");
		$sql3 = mysqli_query($con, "INSERT INTO uploaded_files (up_student_id,
			file_name,
			file_type,
			file_size)VALUES ('$sad_id','$file_name','$file_type','$file_size')");
		if ($sql && $sql1 && $sql2 && $sql3) {
			echo '<script>alert("Inserted Successfully")</script>';
		} else {
			echo '<script>alert("Failed To Insert: ' . mysqli_error($con) . '")</script>';
		}
	}
}



?>

<div class="container">
	<div class="ManagementSystem">
		<h1 class="form-title">Student Management System</h1>
		<?php

		if ($_SERVER["REQUEST_METHOD"] == "POST" && !$edit_mode && empty($first_name_err) && empty($email_err) && empty($last_name_err) && empty($Password_err) && empty($confirm_password_err) && empty($Email_err) && empty($Mobile_num_err) && empty($Date_err) && empty($Gender_err) && empty($Address_err) && empty($City_err) && empty($Zip_err) && empty($State_err) && empty($Country_err) && empty($image_size_err) && empty($image_type_err) && empty($file_size_err) && empty($file_type_err) && empty($img_err) && empty($file_err)) {

			echo '<div class="alert alert-success">Form submitted successfully!</div>';
		}

		?>
		<form method="POST" action="" enctype="multipart/form-data">

			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
					<div class="profile-pic">
						<div class="form-group">
							<label>Upload Image</label>
							<?php

							if ($edit_mode) {
								if (!empty($sd_image) && file_exists("images/{$sd_image}")) {
									echo '<img id="img-upload" src="images/' . htmlspecialchars($sd_image) . '" />';
								} else {
									echo '<img id="img-upload" src="images/user.png" />';
								}
								echo '<div class="input-group">
										<span class="input-group-btn">
											<label class="btn btn-default btn-file">
												Browse…
												<input type="file" name="image_file">
											</label>
										</span>
										<input type="text" class="form-control" readonly>
									</div>';
							} else {
								echo '<img id="img-upload" src="images/user.png" />';
								echo '<div class="input-group">
										<span class="input-group-btn">
											<label class="btn btn-default btn-file">
												Browse…
												<input type="file" name="file">
											</label>
										</span>
										<input type="text" class="form-control" readonly>
									</div>';
							} ?>
							<span style="color: red"><?php echo $img_err; ?></span>
							<span style="color: red"><?php echo $image_type_err; ?></span>
							<span style="color: red"><?php echo $image_size_err; ?></span>


							<div>
							</div>
							<div> <br></div>
							<div class="form-group">
								<label>Upload Documents</label>
								<div class="box">
									<?php if ($edit_mode && $ZipF) { ?>
										<input type="file" name="file1[]" id="file-1" class="inputfile inputfile-1" multiple data-multiple-caption="{count} files selected" />
										<label for="file-1">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
												<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
											</svg>
											<span style="color: red">Choose a file&hellip;</span>
										</label>
									<?php
										// Display download links or other content when in edit mode
										echo '<a download="' . $zipFileName . '" href="' . $zipFileName . '" class="btn btn-green"><i class="fa fa-download"></i> Download All Files</a>';
									} else { ?>
										<input type="file" name="file2" id="file-1" class="inputfile inputfile-1" data-multiple-caption="{count} files selected" />
										<label for="file-1">
											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
												<path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
											</svg>
											<span style="color: red">Choose a file&hellip;</span>
										</label>

									<?php } ?>
									<div>
										<br>
									</div>
									<span style="color: red"><?php echo $file_err; ?></span>
									<span style="color: red"><?php echo $file_type_err; ?></span>
									<span style="color: red"><?php echo $file_size_err; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="row">

						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>First Name <span class="color-danger">*</span></label>
								<input type="text" class="form-control" id="first_name" name="first_name" data-rule-firstname="true" value="<?php echo htmlspecialchars($first_name); ?>" />
								<span style="color: red"><?php echo $first_name_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Last Name <span class="color-danger">*</span></label>
								<input type="text" class="form-control" id="last_name" name="last_name" data-rule-lastname="true" value="<?php echo htmlspecialchars($last_name); ?>" />
								<span style="color: red"><?php echo $last_name_err; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Date of Birth <span class="color-danger">*</span></label>
								<input placeholder="DD/MM/YYYY" type="text" class="form-control" id="date1" name="date" value="<?php echo htmlspecialchars($Date); ?>" data-rule-requiredmmddyy="true" />
								<span style="color: red"><?php echo $Date_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Email <span class="color-danger">*</span></label>
								<input type="text" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($Email); ?>" data-rule-email="true" />
								<span style="color: red"><?php echo $Email_err; ?></span>
								<span style="color: red"><?php echo $email_err; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Password <span class="color-danger">*</span></label>
								<input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" data-rule-passwd="true" />
								<span style="color: red"><?php echo $Password_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Confirm Password <span class="color-danger">*</span></label>
								<input type="password" name="confirm_password" class="form-control" value="<?php echo htmlspecialchars($confirm_password); ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Mobile Number <span class="color-danger">*</span></label>
								<input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($Mobile_num); ?>" class="form-control" />
								<span style="color: red"><?php echo $Mobile_num_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Gender <span class="color-danger">*</span></label>
								<div class="gender_controls">
									<label class="radio-inline" for="gender-0">
										<input name="gender" id="gender-0" value="Male" type="radio" checked="checked">
										Male
									</label>
									<label class="radio-inline" for="gender-1">
										<input name="gender" id="gender-1" value="Female" type="radio">
										Female
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Address <span class="color-danger">*</span></label>
								<textarea class="form-control" id="address_line1" name="address_line_1"><?php echo htmlspecialchars($Address); ?></textarea>

								<span style="color: red"><?php echo $Address_err; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>City <span class="color-danger">*</span></label>
								<input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($City); ?>" />
								<span style="color: red"><?php echo $City_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Zip Code<span class="color-danger">*</span></label>
								<input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo htmlspecialchars($Zip); ?>" />
								<span style="color: red"><?php echo $Zip_err; ?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>State <span class="color-danger">*</span></label>
								<input type="text" name="state" id="state" class="form-control" value="<?php echo htmlspecialchars($State); ?>" />
								<span style="color: red"><?php echo $State_err; ?></span>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group">
								<label>Country <span class="color-danger">*</span></label>
								<select name="country" class="form-control">
									<option value="<?php echo htmlspecialchars($Country) ?>" selected="">(Please select a country)</option>
									<?php
									$sql = "SELECT * FROM country";
									$result = mysqli_query($con, $sql);
									while ($row = mysqli_fetch_assoc($result)) {
										$country_name = $row["country_name"];
										$selected = ($Country == $country_name) ? "selected" : "";
										echo '<option value="' . $country_name . '" ' . $selected . '>' . $country_name . '</option>';
									}
									?>
								</select>


								<span style="color: red"><?php echo $Country_err; ?></span>

							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Hobbies</label>
								<div class="controls">
									<?php
									// Define available hobby options
									$hobbyOptions = array("Drawing", "Singing", "Dancing", "Sketching", "Others");

									// Create an array to store selected hobbies
									$selectedHobbies = array();

									if ($edit_mode) {
										// Fetch and display selected hobbies in edit mode
										foreach ($hobbyOptions as $option) {
											$checked = in_array($option, $hobbies) ? 'checked' : '';
											echo '<label class="checkbox-inline">
        <input type="checkbox" name="hobby[]" value="' . $option . '" ' . $checked . '>' . $option . '
    </label>';
											if ($checked) {
												$selectedHobbies[] = $option;
											}
										}
									} else {
										// In default mode, display checkboxes with previously selected hobbies checked
										if (isset($_POST['hobby']) && is_array($_POST['hobby'])) {
											foreach ($_POST['hobby'] as $hobby) {
												if (!empty($hobby)) {
													$selectedHobbies[] = $hobby;
												}
											}
											$hobbies = implode(', ', $selectedHobbies);
										}

										foreach ($hobbyOptions as $option) {
											$checked = in_array($option, $selectedHobbies) ? 'checked' : '';
											echo '<label class="checkbox-inline">
        <input type="checkbox" name="hobby[]" value="' . $option . '" ' . $checked . '>' . $option . '
    </label>';
										}
									}

									// Text input to display selected hobbies
									echo '<label class="checkbox-inline">
    <input type="text" name="hobby[]" class="form-control" value="">
</label>';
									?>

								</div>



							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Qualification</label>
								<div class="table-responsive">
									<table>
										<thead>
											<tr>
												<th>Sr. No.</th>
												<th>Examination</th>
												<th>Board</th>
												<th>Percentage</th>
												<th>Year of Passing</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Class X</td>
												<td><input type="text" class="form-control" name="X-board" id="X-board" value="<?php echo ($X_board); ?>"></td>
												<td><input type="text" class="form-control" name="X-perc" id="X-perc" value="<?php echo ($X_perc); ?>"></td>
												<td><input type="text" class="form-control" name="X-yop" id="X-yop" value="<?php echo ($X_yop); ?>"></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Class XII</td>
												<td><input type="text" class="form-control" name="XII-board" id="XII-board" value="<?php echo ($XII_board); ?>"></td>
												<td><input type="text" class="form-control" name="XII-perc" id="XII-perc" value="<?php echo ($XII_perc); ?>"></td>
												<td><input type="text" class="form-control" name="XII-yop" id="XII-yop" value="<?php echo ($XII_yop); ?>"></td>
											</tr>




										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="form-group">
								<label>Courses Applied for</label>
								<div class="controls">

									<?php
									// Define available course options
									$courseOptions = array("BCA", "B.COM", "B.Sc", "B.A", "B.Tech");

									// Check if the form is in edit mode (you should define $edit_mode as a boolean before this code)
									if (!$edit_mode) {
										// Check if the course has been selected in the form submission
										$selectedCourse = isset($_POST['course']) ? $_POST['course'] : '';
									}

									// Loop through the course options and create radio buttons
									foreach ($courseOptions as $option) {
										$checked = ($CourseAP == $option) ? 'checked' : ''; // Check if this option is selected
										echo '<label class="radio-inline">
        <input name="course" id="course-' . $option . '" value="' . $option . '" type="radio" ' . $checked . '>
        ' . $option . '
    </label>';
									}
									?>



								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="action-button">
						<input type="submit" class="btn btn-green submit-form" value="Submit" />
						<input type="reset" class="btn btn-orange" value="Reset" />
					</div>
				</div>
			</div>
	</div>
</div>
</form>
</div>
</div>
<?php
echo $X_board;
echo $X_perc;
echo $XII_board;
echo $XII_perc;
?>
<?php include 'footer.php'; ?>