<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Handling System</title>
    <link rel="icon" type="image/x-icon"
        href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
    <link rel="stylesheet" href="../css/report.css">

</head>

<body>
    <div class="navContainer">
        <header>
            <div class="container">
                <!--<a href="../html/index.html" class="logout">LOG OUT</a> -->
                <div class="mainicon">
                    <img class="mainicon1"
                        src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg"
                        alt="school icon">
                    <h1>Marks Handling and Grading System</h1>
                </div>
            </div>
        </header>
    </div>

    <div class="bodyContainer">
        <main>
            <div class="container">
                <div class="container1">
                    <div class="report">
                        <h4><a href="generatereport.php" style="color: aliceblue;text-decoration: none;">Generate
                                Report</a></h4>
                    </div>
                    <div class="report">
                        <h4><a href="index.html" style="color: aliceblue; text-decoration: none;">Log Out</a></h4>
                    </div>
                    <div class="report">
                        <h4><a href="main.html" style="color: aliceblue; text-decoration: none;">Insert New Marks</a>
                        </h4>
                    </div>
                </div>
                <section class="input-section">
                    <form id="marksForm" action="" method="POST">
                        <div class="form-group">
                            <label for="studentName">Student's Name:</label>
                            <input type="text" id="studentName" name="studentName" placeholder="Enter student name"
                                required>

                            <button id="js1" type="submit" class="btn">Submit</button>
                            <button id="js1" type="button" onclick="window.print()" class="btn">Print Report</button>

                        </div>
                    </form>
                </section>

                <div class="student-results">
                    <?php
                    // 1. Connect to the database
                    $host = "localhost";
                    $user = "root";
                    $password = "";
                    $database = "reports";

                    $conn = new mysqli($host, $user, $password, $database);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // 2. Get student ID from form
                    if (isset($_POST['studentName'])) {
                        $studentName = $_POST['studentName'];

                        // 3. Use prepared statement to 0prevent SQL injection
                        $stmt = $conn->prepare("SELECT * FROM student_marks WHERE studentName = ?");
                        $stmt->bind_param("s", $studentName);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // 4. Check if student exists
                        if ($result->num_rows > 0) {
                            // Fetch and display student data
                            $row = $result->fetch_assoc();
                            echo "<h3 style='margin-left:30%; '>Student Results:</h3>";
                            echo "<table border='5'
 cellpadding='10' cellspacing='0' style='border-collapse:collapse; border-color:rgb(11, 62, 109); margin-left:10%; width: 80%;'>";

                            $fields = [
                                "Student ID" => $row['id'],
                                "Student Name" => $row['studentName'],
                                "Class Teacher" => $row['classTeacherName'],
                                "Term" => $row['term'],
                                "Stage" => $row['stage'],
                                "Mathematics" => $row['mathematics'],
                                "Chemistry" => $row['chemistry'],
                                "Biology" => $row['biology'],
                                "Physics" => $row['physics'],
                                "Geography" => $row['geography'],
                                "History" => $row['history'],
                                "Business" => $row['business'],
                                "Economics" => $row['economics'],
                                "ICT" => $row['ict'],
                                "GlobalP" => $row['globalP'],
                                "Literature" => $row['literature'],
                                "French" => $row['french'],
                                "Mutoon" => $row['mutoon'],
                                "Qoran" => $row['qoran'],
                                "Total Marks" => $row['Total_Marks'],
                                "Average Marks" => $row['Average_Marks'],
                                "Created At" => $row['created_at']
                            ];

                            foreach ($fields as $label => $value) {
                                echo "<tr><td><strong>$label</strong></td><td>$value</td></tr>";
                            }

                            /*switch ($row['Average_Marks']) {
                                case 80:
                                    echo "A";
                                    break;

                                case 75:
                                    echo "B+";
                                    break;

                                case 70:
                                    echo "B";
                                    break;

                                case 65:
                                    echo "C+";
                                    break;

                                case 60:
                                    echo "C";
                                    break;

                                case 50:
                                    echo "D";
                                    break;

                                default:
                                    echo "F";
                                    break;
                            }*/

                            echo "</table>";
                        } else {
                            echo "The name doesn't exist in the database: " . htmlspecialchars($studentName);
                        }

                        $stmt->close();
                    }

                    $conn->close();
                    ?>
                </div>



                <footer class="containerDown">
                    <div>
                        <p>&copy; 2025 Marks Handling System @piranockharvey03. All rights reserved.</p>
                    </div>
                </footer>
            </div>

</body>

</html>