<?php
// Include your database connection here
$host = "localhost";
$username = "root";
$password = "";
$database = "student";
$con = mysqli_connect($host, $username, $password, $database);

if ($con == false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Check which chart is requested
if (isset($_GET['chart'])) {
    $chartType = $_GET['chart'];

    // Fetch data based on chart type and return as JSON
    switch ($chartType) {
        case '1':
            // Pie chart for Total Male/Female Students
            $maleCount = mysqli_query($con, "SELECT COUNT(*) as count FROM student_details WHERE sd_gender='Male'");
            $femaleCount = mysqli_query($con, "SELECT COUNT(*) as count FROM student_details WHERE sd_gender='Female'");

            $data = [
                'labels' => ['Male', 'Female'],
                'data' => [
                    (int) mysqli_fetch_assoc($maleCount)['count'],
                    (int) mysqli_fetch_assoc($femaleCount)['count'],
                ],
            ];
            break;
        case '2':

            $query = "SELECT DATE(sd_date_added) as date, COUNT(*) as count FROM student_details GROUP BY DATE(sd_date_added) ORDER BY DATE(sd_date_added) DESC LIMIT 15000";
            $result = mysqli_query($con, $query);

            $labels = [];
            $counts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $labels[] = $row['date'];
                $counts[] = (int) $row['count'];
            }

            $data = [
                'labels' => array_reverse($labels),
                'data' => array_reverse($counts),
            ];
            break;
        case '3':
            // Line Graph & Bar Graph in Single Graph (Daywise student registration)
            $query = "SELECT DATE(sd_date_added) as date, 
                                    COUNT(*) as total_count,
                                    SUM(CASE WHEN sd_gender='Male' THEN 1 ELSE 0 END) as male_count,
                                    SUM(CASE WHEN sd_gender='Female' THEN 1 ELSE 0 END) as female_count
                                FROM student_details
                                GROUP BY DATE(sd_date_added) 
                                ORDER BY DATE(sd_date_added) DESC 
                                LIMIT 15000";
            $result = mysqli_query($con, $query);

            $labels = [];
            $totalCounts = [];
            $maleCounts = [];
            $femaleCounts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $labels[] = $row['date'];
                $totalCounts[] = (int) $row['total_count'];
                $maleCounts[] = (int) $row['male_count'];
                $femaleCounts[] = (int) $row['female_count'];
            }

            $data = [
                'labels' => array_reverse($labels),
                'barData' => array_reverse($totalCounts), // Bar graph data
                'lineData' => array_reverse($totalCounts), // Line graph data (update this line to use a different dataset if needed)
                'maleData' => array_reverse($maleCounts),
                'femaleData' => array_reverse($femaleCounts),
            ];
            break;
        case '4':
            // Bar Graph with Three bars i.e. Male/Female/Total Registration in each day (last 30 days)
            $query = "SELECT DATE(sd_date_added) as date, 
                            SUM(CASE WHEN sd_gender='Male' THEN 1 ELSE 0 END) as male_count,
                            SUM(CASE WHEN sd_gender='Female' THEN 1 ELSE 0 END) as female_count,
                            COUNT(*) as total_count
                        FROM student_details
                        GROUP BY DATE(sd_date_added) 
                        ORDER BY DATE(sd_date_added) DESC 
                        LIMIT 30";
            $result = mysqli_query($con, $query);

            $labels = [];
            $maleCounts = [];
            $femaleCounts = [];
            $totalCounts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $labels[] = $row['date'];
                $maleCounts[] = (int) $row['male_count'];
                $femaleCounts[] = (int) $row['female_count'];
                $totalCounts[] = (int) $row['total_count'];
            }

            $data = [
                'labels' => array_reverse($labels),
                'maleData' => array_reverse($maleCounts),
                'femaleData' => array_reverse($femaleCounts),
                'totalData' => array_reverse($totalCounts),
            ];
            break;
        default:
            // Handle invalid chart type
            $data = [];
            break;
    }

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Close the database connection
mysqli_close($con);
