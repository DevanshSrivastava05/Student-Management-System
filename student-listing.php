<?php include 'header2.php'; ?>
<?php include 'session.php'; ?>
<?php
$host = "localhost";
$username = "root";
$password = "";
$student = "student";

$con = mysqli_connect($host, $username, $password, $student);
function  getEmailAssociatedWithDocument($studentId)
{
	global $host, $username, $password, $student;
	$con = mysqli_connect($host, $username, $password, $student);
	$query = "SELECT sd_email FROM student_details WHERE sd_student_id = '$studentId'";
	$result = mysqli_query($con, $query);
	if ($result) {
		$row = mysqli_fetch_assoc($result);
		if ($row) {
			$Email = $row['sd_email'];
		} else {
			$Email = "";
		}
	} else {
		$Email = ""; // Query execution failed
	}
	mysqli_close($con);
	return $Email;
}
echo '<div class="container">
	<div class="ManagementSystem">
		<h1 class="form-title">Student Detail</h1>
		<div class="option-buttons">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-8">
				<form method="POST" action="function_csv.php?Import=1" enctype="multipart/form-data">

    <label class="btn btn-orange">
        <i class="fa fa-plus-circle"></i> Add Student 
        <input type="file" name="add-file" id="add-file" class="inputfile" />
    </label>

    <label class="btn btn-green">
        <i class="fa fa-plus-circle"></i> Import CSV
        <input type="file" name="file" id="file" class="inputfile" accept=".csv" required>
    </label>
    <label>
    <button type="submit" id="submit" class="btn btn-green" name="Import">
        <i class="fa fa-upload"></i> Upload CSV
    </button>
	</label>
	
 <label class="btn btn-orange" onclick="location.href=\'function_csv.php?export-file=1\'"><i class="fa fa-plus-circle"></i> Export CSV<input name="export-file" id="export-file" class="inputfile" /></label>
</form>';
echo '
				</div>
				<form method="POST" action="student-listing.php" >
				<div class="input-group">
					<input type="text" class="form-control"  id="search" name="search" autocomplete="off" placeholder="Search for...">
						<span class="input-group-btn">
						<button class="btn btn-search btn-green" type="submit" name="submit"><i class="fa fa-search fa-fw"></i> Search</button>
								</span>
				
				</div>
				</form>
			</div>


		</div>
		<div class="table-responsive">
			<table class="user-detail">
				<thead>
					<tr>
						<th><input type="checkbox" name="all-selected" value="" /></th>
						<th>Student Id</th>
						<th>Student</th>
						<th>Course</th>
						<th>Email id</th>
						<th>Qualification</th>
						<th></th>
					</tr>
				</thead>
				<tbody>';
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#search").on("input", function() {
			var inputLetter = $(this).val().charAt(0);

			console.log('Input Letter:', inputLetter);

			$.ajax({
				url: 'student_names.php',
				type: 'GET',
				data: {
					letter: inputLetter
				},
				dataType: 'json',
				success: function(data) {
					console.log('Ajax Success. Data:', data);

					// Assuming data is already an array, update the autocomplete source
					$("#search").autocomplete({
						source: data,
						minLength: 1,
						response: function(event, ui) {
							// Map each suggestion to a custom format
							var suggestions = $.map(ui.content, function(item) {
								return {
									label: item.label,
									value: item.value
								};
							});
							ui.content = suggestions;
						},
						open: function(event, ui) {
							// Apply custom styles to the suggestion list
							var autocompleteWidget = $(this).autocomplete('widget');
							autocompleteWidget.css({
								"width": "200px",
								"background-color": "#fff",
								"border": "1px solid #ddd",
								"border-radius": "3px",
								"font-family": "san-serif",
								"font-size": "15px",
								"box-shadow": "0 1px 2px rgba(0, 0, 0, 0.9)",
								"cursor": "pointer"
							});

							autocompleteWidget.find('.ui-menu-item').hover(
								function() {
									$(this).css({
										"background-color": "#add8e6", // Light blue on hover
										"color": "#007bff", // Deeper blue text color on hover
										"border-color": "#6c757d",
										"border-width": "auto",
										"display": "inline-block",
										"font-weight": "bold", // Make the text bold on hover
										"transition": "all 0.3s ease-in-out",
										// Smooth transition effect
									});
								},
								function() {
									$(this).css({
										// Revert to default styles on mouseout
										"background-color": "",
										"color": "",
										"border-color": "",
										"display": "",
										"font-weight": "",
										"transition": "all 0.0s ease-in-out"
									});
								}
							);
						},
						select: function(event, ui) {
							$(this).val(ui.item.value);
							return false; // Prevent the default behavior
						},
					});
				},
				error: function(xhr, status, error) {
					console.error('Ajax Error:', status, error);
				}
			});
		});
	});
</script>
<?php
$result_per_page = 5;

$sql3 = "SELECT DISTINCT
sd.sd_student_id,
sd.sd_first_name,
sd.sd_applied_course,
sd.sd_address,
sd.sd_email,
sd.sd_image,
    CASE
	WHEN MAX(
		CASE
			WHEN sad.sad_course_name = 'XII'
			AND sad.sad_percentage IS NOT NULL
			AND sad.sad_percentage != ''
			AND sad.sad_year_of_passing IS NOT NULL
			AND sad.sad_year_of_passing != ''
			THEN 1
			ELSE 0
		END
	) = 1 THEN 'XII passed'
	WHEN MAX(
		CASE
			WHEN sad.sad_course_name = 'X'
			AND sad.sad_percentage IS NOT NULL
			AND sad.sad_percentage != ''
			AND sad.sad_year_of_passing IS NOT NULL
			AND sad.sad_year_of_passing != ''
			THEN 1
			ELSE 0
		END
	) = 1 THEN 'X passed'
	ELSE NULL
END AS highest_qualification
FROM
student_details AS sd
LEFT JOIN student_acedemic_details AS sad ON sd.sd_student_id = sad.sad_student_id
LEFT JOIN uploaded_files AS uf ON sd.sd_student_id = uf.up_student_id
GROUP BY
sd.sd_student_id,
sd.sd_first_name,
sd.sd_applied_course,
sd.sd_address,
sd.sd_email,
sd.sd_image,
uf.file_name";
$result2 = mysqli_query($con, $sql3);
$numb_rows = mysqli_num_rows($result2);
$numb_total_pages = ceil($numb_rows / $result_per_page);


if (!isset($_GET['page'])) {
	$page = 1;
} else {
	$page = $_GET['page'];
}
$start_limit = ($page - 1) * $result_per_page;
if (isset($_POST['submit'])) {
	$search = $_POST['search'];
	$sql2 = "SELECT 
    sd.sd_student_id,
    sd.sd_first_name,
    sd.sd_applied_course,
    sd.sd_address,
    sd.sd_email,
    sd.sd_image,
    uf.file_name,
    CASE
        WHEN MAX(
            CASE
                WHEN sad.sad_course_name = 'XII'
                AND sad.sad_percentage IS NOT NULL
                AND sad.sad_percentage != ''
                AND sad.sad_year_of_passing IS NOT NULL
                AND sad.sad_year_of_passing != ''
                THEN 1
                ELSE 0
            END
        ) = 1 THEN 'XII passed'
        WHEN MAX(
            CASE
                WHEN sad.sad_course_name = 'X'
                AND sad.sad_percentage IS NOT NULL
                AND sad.sad_percentage != ''
                AND sad.sad_year_of_passing IS NOT NULL
                AND sad.sad_year_of_passing != ''
                THEN 1
                ELSE 0
            END
        ) = 1 THEN 'X passed'
        ELSE NULL
    END AS highest_qualification, (SELECT uf.file_name FROM uploaded_files uf WHERE uf.up_student_id = sd.sd_student_id LIMIT 1) AS file_name
FROM
    student_details AS sd
LEFT JOIN student_acedemic_details AS sad ON sd.sd_student_id = sad.sad_student_id
LEFT JOIN uploaded_files AS uf ON sd.sd_student_id = uf.up_student_id
WHERE
	sd.sd_first_name LIKE '%$search%'
    OR CONCAT(sd.sd_first_name, ' ', sd.sd_last_name) LIKE '%$search%'
	OR sd.sd_email LIKE '%$search%'
GROUP BY
    sd.sd_student_id, sd.sd_first_name, sd.sd_applied_course, sd.sd_address, sd.sd_email
LIMIT " . $start_limit . ',' . $result_per_page;
	$result = mysqli_query($con, $sql2);
} else {
	$sql = "SELECT 
    sd.sd_student_id,
    sd.sd_first_name,
    sd.sd_applied_course,
    sd.sd_address,
    sd.sd_email,
    sd.sd_image,
    uf.file_name,
    CASE
        WHEN MAX(
            CASE
                WHEN sad.sad_course_name = 'XII'
                AND sad.sad_percentage IS NOT NULL
                AND sad.sad_percentage != ''
                AND sad.sad_year_of_passing IS NOT NULL
                AND sad.sad_year_of_passing != ''
                THEN 1
                ELSE 0
            END
        ) = 1 THEN 'XII passed'
        WHEN MAX(
            CASE
                WHEN sad.sad_course_name = 'X'
                AND sad.sad_percentage IS NOT NULL
                AND sad.sad_percentage != ''
                AND sad.sad_year_of_passing IS NOT NULL
                AND sad.sad_year_of_passing != ''
                THEN 1
                ELSE 0
            END
        ) = 1 THEN 'X passed'
        ELSE NULL
    END AS highest_qualification, (SELECT uf.file_name FROM uploaded_files uf WHERE uf.up_student_id = sd.sd_student_id LIMIT 1) AS file_name
FROM
    student_details AS sd
LEFT JOIN student_acedemic_details AS sad ON sd.sd_student_id = sad.sad_student_id
LEFT JOIN uploaded_files AS uf ON sd.sd_student_id = uf.up_student_id
GROUP BY
    sd.sd_student_id, sd.sd_first_name, sd.sd_applied_course, sd.sd_address, sd.sd_email
LIMIT " . $start_limit . ',' . $result_per_page;
	$result = mysqli_query($con, $sql);
}
$uploadedFiles = [];
$sql = mysqli_query($con, "SELECT * FROM uploaded_files WHERE up_student_id = {$_SESSION['student_id']}");

while ($row1 = mysqli_fetch_assoc($sql)) {
	$uploadedFiles[] = $row1['file_name']; // Store all file names in the array.
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
if ($result) {
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<tr>';
		echo '<td><input type="checkbox" name="" value="" /></td>';
		echo '<td>' . $row['sd_student_id'] . '</td>';

		$imagePath = empty($row['sd_image']) || !file_exists("images/{$row['sd_image']}") ? "images/user.png" : "images/{$row['sd_image']}";

		echo '<td><div class="image"><img src="' . $imagePath . '" class="img-responsive"/>' . $row['sd_first_name'] . '</div><div>' . $row['sd_address'] . '</div></td>';
		echo '<td>' . $row["sd_applied_course"] . '</td>';
		echo '<td>' . $row["sd_email"] . '</td>';
		echo '<td>' . $row["highest_qualification"] . '</td>';
		$emailAssociatedWithDocument = getEmailAssociatedWithDocument($row['sd_student_id']); // Replace this with your actual function to get the email associated with the document
		$documentFilePath = "uploads/{$row['file_name']}";
		echo '<td>';
		echo '<div class="user-actions">';

		if ($_SESSION['email'] === $emailAssociatedWithDocument) {
			// Allow the logged-in user to delete their own document
			echo '<a download="' . $zipFileName . '" href="' . $zipFileName . '" class="btn btn-green"><i class="fa fa-download"></i> Document</a>';
			echo '<a href="student-detail.php" class="btn btn-orange" title="View"><i class="fa fa-eye"></i></a>';
			echo '<a href="delete.php?id=' . $row['sd_student_id'] . '" class="btn btn-orange" title="Delete"><i class="fa fa-trash"></i></a>';
			echo '<a href="index.php?edit=1&student_id=' . $row['sd_student_id'] . '" class="btn btn-orange" title="Edit"><i class="fa fa-pencil"></i></a>';
		} else {
			echo '<a href="" class="btn btn-green"><i class="fa fa-download"></i> Document</a>';
			echo '<a href="" class="btn btn-orange" title="View"><i class="fa fa-eye"></i></a>';
			echo '<a href="" class="btn btn-orange" title="Delete"><i class="fa fa-trash"></i></a>';
			echo '<a href="" class="btn btn-orange" title="Edit"><i class="fa fa-pencil"></i></a>';
		}
		echo '</div></td>';
		echo "</tr>";
	}
} else {
	echo "Invalid query: " . mysqli_error($con);
}

echo '</tbody></table></div></div></div>';

echo '<div class="pager-navigation">';
echo '<ul class="pagination">';
echo '<li><a href="student-listing.php?page=1">«</a></li>';

$current_page = $page;
for ($page = 1; $page <= $numb_total_pages; $page++) {
	if ($page == $current_page) {
		echo '<li class="active"><a href="student-listing.php?page=' . $page . '">' . $page . '</a></li>';
	} else {
		echo '<li><a href="student-listing.php?page=' . $page . '">' . $page . '</a></li>';
	}
}

echo '<li><a href="student-listing.php?page=' . $numb_total_pages . '">»</a></li>';
echo '</ul>';
echo '</div>';
?>
<?php include 'footer.php'; ?>