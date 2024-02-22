<?php include 'header2.php'; ?>
<?php include 'session.php'; ?>
<?php
$host = "localhost";
$username = "root";
$password = "";
$student = "student";
$con = mysqli_connect($host, $username, $password, $student);
?>
<div class='container'>
	<div class='ManagementSystem'>
		<h1 class='form-title'>Student Detail</h1>

		<div class='row'>
			<div class='col-lg-2 col-md-3 col-sm-3 col-xs-12'>
				<div class='profile-pic viewimage'>
					<span><img class="account-pic" src="images/<?php echo $_SESSION['profile_pic']; ?>" /></span>
				</div>
			</div>
			<div class='col-lg-10 col-md-9 col-sm-9 col-xs-12'>
				<div class='user-detail-view'>
					<div class='row'>
						<div class='col-lg-6 col-md-6 col-sm-12'>
							<?php
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
							sd.sd_email,
							sd.sd_image") or die(mysqli_error($con));
							while ($row = mysqli_fetch_assoc($result)) {
								echo "<div class='user-data'>
								<span class='grey'>Student Id:</span>
								<span class='blue'>" . $row['sd_student_id'] .
									'</span>';

								echo "</div>
						</div>
						<div class='col-lg-6 col-md-6 col-sm-12'>
							<div class='user-data'>
								<span class='grey'>Name:</span>
								<span class='blue'>" . $row['sd_first_name'] .
									'</span>';
								echo "</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-lg-12 col-md-12 col-sm-12'>
							<div class='user-data full-width'>
								<span class='grey'>Address:</span>
								<span class='blue'>" . $row['sd_address'] .
									'</span>';
								echo "</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-lg-6 col-md-6 col-sm-12'>
							<div class='user-data'>
								<span class='grey'>Course:</span>
								<span class='blue'>" . $row['sd_applied_course'] . '</span>';
								echo "</div>
						</div>
						<div class='col-lg-6 col-md-6 col-sm-12'>
							<div class='user-data'>
								<span class='grey'>Date of Birth:</span>
								<span class='blue'>" . $row['sd_dob'] . '</span>';
								echo "</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-lg-6 col-md-6 col-sm-12'>
							<div class='user-data'>
								<span class='grey'>Gender:</span>
								<span class='blue'>" . $row['sd_gender'] . '</span>';
								echo "</div>
					</div>
					<div class='col-lg-6 col-md-6 col-sm-12'>
						<div class='user-data'>
							<span class='grey'>Email:</span>
							<span class='blue'>" . $row['sd_email'] . '</span>';
								echo "</div>
					</div>
				</div>
				<div class='row'>
					<div class='col-lg-12 col-md-12 col-sm-12'>
						<div class='user-data'>
							<span class='grey'>Qualification:</span>
							<span class='blue'>" . $row['highest_qualification'] . '</span>';
								echo "</div>
							</div>
						</div>
						<div class='print-actions'>
							<div class='col-lg-12 col-md-12 col-sm-12'>";
								echo "<a href='index.php?edit=1&student_id=" . $row['sd_student_id'] . "' class='btn btn-warning' title='Edit'><i class='fa fa-edit'></i> Edit</a>
								<a href='#' class='btn btn-warning' title='Print' onclick='printPage()'>
								<i class='fa fa-print'></i> Print
							</a>";
								echo "</div>
						</div>";
							?>

						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
							};
	?>
	<script>
		function printPage() {
			window.print();
		}
	</script>

	<?php include 'footer.php'; ?>