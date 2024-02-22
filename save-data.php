<?php
// Include your database connection code here

$host = "localhost";
$username = "root";
$password = "";
$student = "student";
session_start();
$con = mysqli_connect($host, $username, $password, $student);
// Fetch data from the database
$result = mysqli_query($con, "SELECT
							sd.sd_student_id,
							sd.sd_first_name,
							sd.sd_applied_course,
							sd.sd_address,
							sd.sd_gender,
							sd.sd_dob,
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
						WHERE sd.sd_email=\"{$_SESSION['email']}\"
						GROUP BY
							sd.sd_student_id,
							sd.sd_first_name,
							sd.sd_applied_course,
							sd.sd_address,
							sd.sd_email");

if (!$result) {
	die('Error in the SQL query: ' . mysqli_error($con));
}

?>

<?php include 'header.php'; ?>
<div class="container">
	<div class="ManagementSystem">
		<h1 class="form-title">Student Data</h1>
		<div class="save-data">
			<div class="table-responsive">
				<table class="user-detail">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Student Id</th>
							<th>Student</th>
							<th>Course</th>
							<th>Email id</th>
							<th>Qualification</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$srNo = 1;
						while ($row = mysqli_fetch_assoc($result)) {
							$dob =$row['sd_dob'];
							$dateparts=explode("-",$dob);
							$std_dob=$dateparts[2]."-".$dateparts[1]."-".$dateparts[0];

						?>
							<tr>
								<td><?php echo $srNo; ?></td>
								<td><?php echo $row['sd_student_id']; ?></td>
								<td>
									<div class="image">
										<?php
										$imagePath = empty($row['sd_image']) || !file_exists("images/{$row['sd_image']}") ? "images/user.png" : "images/{$row['sd_image']}";
										echo '<img src="' . $imagePath . '" class="img-responsive" />';
										?>



										<h4 class="user-name"><?php echo $row['sd_first_name']; ?></h4>
										<h5 class="user-gender"><?php echo $row['sd_gender']; ?></h5>
										<h5 class="user-dob"><?php echo $std_dob?></h5>
										<div class="user-address">
											<p><?php echo $row['sd_address']; ?></p>
										</div>
									</div>
								</td>
								<td><?php echo $row['sd_applied_course']; ?></td>
								<td><?php echo $row['sd_email']; ?></td>
								<td><?php echo $row['highest_qualification']; ?></td>
							</tr>
						<?php
							$srNo++;
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>