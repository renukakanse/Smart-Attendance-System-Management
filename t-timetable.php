<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "school_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';
$result = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch'])) {
    $teacher_name = trim($_POST['teacher_name']);
    
    if (empty($teacher_name)) {
        $message = "Please enter a teacher name!";
    } else {
        // Fetch timetable for the entered teacher name
        $sql = "SELECT * FROM timetable WHERE teacher = ? ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $teacher_name);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            $message = "Error fetching timetable: " . $conn->error;
        } elseif ($result->num_rows == 0) {
            $message = "No schedules found for teacher: " . htmlspecialchars($teacher_name);
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool - Teacher Timetable</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        /* Sidebar */
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

        /* Main Content */
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

        h1 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        /* Form */
        .teacher-form {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .teacher-form label {
            display: block;
            margin: 0.5rem 0 0.2rem;
        }

        .teacher-form input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .teacher-form button {
            padding: 0.5rem 1rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .teacher-form button:hover {
            background-color: #2980b9;
        }

        /* Timetable Table */
        .timetable-table {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
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

        /* Print Button */
        .print-btn {
            padding: 0.5rem 1rem;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .print-btn:hover {
            background-color: #219653;
        }

        /* Message Styling */
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

        /* Print Styles */
        @media print {
            .sidebar, header, .teacher-form, .print-btn, .message {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 0;
            }

            .timetable-table {
                padding: 0;
            }

            table {
                width: 100%;
            }

            th {
                background-color: #3498db !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            h3 {
                margin-bottom: 1rem;
            }
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
                <span>Teacher</span>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="t-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="t-event.php"><i class="fas fa-chalkboard"></i> Events</a>
                    </li>
                    <li>
                        <a href="t-assignments.php"><i class="fas fa-book-open"></i> Assignments</a>
                    </li>
                    <li>
                        <a href="t-attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a>
                    </li>
                    <li class="active">
                        <a href="t-timetable.php"><i class="fas fa-clock"></i> Timetable</a>
                    </li>
                    <li>
                        <a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
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

            <h1>My Timetable</h1>

            <!-- Display Messages -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Teacher Name Input Form -->
            <div class="teacher-form">
                <h3>Fetch Your Timetable</h3>
                <form method="POST">
                    <label for="teacher_name">Enter Your Name:</label>
                    <input type="text" id="teacher_name" name="teacher_name" placeholder="e.g., John Doe" required>
                    <button type="submit" name="fetch">Fetch Timetable</button>
                </form>
            </div>

            <!-- Timetable Display -->
            <?php if ($result): ?>
            <div class="timetable-table">
                <h3>Weekly Schedule for <?php echo htmlspecialchars($teacher_name); ?></h3>
                <button class="print-btn" onclick="window.print()">Print Timetable</button>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Subject</th>
                            <th>Standard</th>
                            <th>Division</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($row['std']) . 'th'; ?></td>
                                    <td><?php echo htmlspecialchars($row['division']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6">No schedules found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>