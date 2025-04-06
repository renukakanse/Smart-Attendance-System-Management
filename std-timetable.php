<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "school_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

// Fetch student timetable based on selected or default class
$studentStd = isset($_GET['std']) ? $_GET['std'] : 5; // Default to 5th standard for demo
$studentDivision = isset($_GET['division']) ? $_GET['division'] : 'A'; // Default to division A
$studentTimetable = $conn->prepare("SELECT * FROM timetable WHERE std = ? AND division = ? ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time");
$studentTimetable->bind_param("ss", $studentStd, $studentDivision);
$studentTimetable->execute();
$result = $studentTimetable->get_result();
if (!$result) {
    $message = "Error fetching student timetable: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool - Student Timetable</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com Ascendant.css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo h2 {
            color: #3498db;
            margin-bottom: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            background-color: #34495e;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            padding: 0.75rem;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .sidebar nav ul li.active {
            background-color: #3498db;
            border-radius: 5px;
        }

        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .sidebar nav ul li i {
            margin-right: 0.5rem;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 1rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .student-filter {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .student-filter label {
            margin-right: 1rem;
            font-weight: bold;
        }

        .student-filter select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-right: 1rem;
        }

        .student-filter button {
            padding: 0.5rem 1rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .student-filter button:hover {
            background-color: #357abd;
        }

        .timetable-table {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        td {
            background-color: #fff;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1rem;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            animation: fadeInOut 4s ease-in-out forwards;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); display: none; }
        }

        @media print {
            .sidebar, .student-filter, .message { display: none; }
            .main-content { margin-left: 0; }
            .timetable-table { padding: 0; }
            table { width: 100%; }
            th { background-color: #3498db !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            h3 { margin-bottom: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>MySchool</h2>
            </div>
            <div class="user-info">
                <img src="logo.jpeg" alt="User Avatar" class="avatar">
                <span>Student</span>
            </div>
            <nav>
                <ul>
                    <li><a href="s-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="s-classes.php"><i class="fas fa-chalkboard"></i> Classes</a></li>
                    <li><a href="std-assignment.php"><i class="fas fa-book-open"></i> Assignments</a></li>
                    <li><a href="s-attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                    <li><a href="s-event.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                    <li class="active"><a href="s-timetable.php"><i class="fas fa-clock"></i> Timetable</a></li>
                    <li><a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div class="breadcrumb">
                    <span>Home / Timetable</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                    <a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <!-- Display Messages -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Student Timetable Filter -->
            <div class="student-filter">
                <h3>Select Your Class</h3>
                <form method="GET" action="">
                    <label for="std">Standard:</label>
                    <select id="std" name="std" onchange="this.form.submit()">
                        <option value="">Select Standard</option>
                        <option value="1" <?php echo $studentStd == 1 ? 'selected' : ''; ?>>1st</option>
                        <option value="2" <?php echo $studentStd == 2 ? 'selected' : ''; ?>>2nd</option>
                        <option value="3" <?php echo $studentStd == 3 ? 'selected' : ''; ?>>3rd</option>
                        <option value="4" <?php echo $studentStd == 4 ? 'selected' : ''; ?>>4th</option>
                        <option value="5" <?php echo $studentStd == 5 ? 'selected' : ''; ?>>5th</option>
                        <option value="6" <?php echo $studentStd == 6 ? 'selected' : ''; ?>>6th</option>
                        <option value="7" <?php echo $studentStd == 7 ? 'selected' : ''; ?>>7th</option>
                        <option value="8" <?php echo $studentStd == 8 ? 'selected' : ''; ?>>8th</option>
                        <option value="9" <?php echo $studentStd == 9 ? 'selected' : ''; ?>>9th</option>
                        <option value="10" <?php echo $studentStd == 10 ? 'selected' : ''; ?>>10th</option>
                    </select>

                    <label for="division">Division:</label>
                    <select id="division" name="division" onchange="this.form.submit()">
                        <option value="">Select Division</option>
                        <option value="A" <?php echo $studentDivision == 'A' ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?php echo $studentDivision == 'B' ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?php echo $studentDivision == 'C' ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?php echo $studentDivision == 'D' ? 'selected' : ''; ?>>D</option>
                    </select>
                    <button type="submit">View Timetable</button>
                </form>
            </div>

            <!-- Student Timetable Display -->
            <div class="timetable-table">
                <h3>Timetable (Std: <?php echo $studentStd . 'th, Division: ' . $studentDivision; ?>)</h3>
                <button class="print-btn" onclick="window.print()">Print Timetable</button>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($row['teacher']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No schedules found for this class</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>