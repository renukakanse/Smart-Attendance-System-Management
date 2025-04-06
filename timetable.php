<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "school_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

// Function to check for conflicts
function hasConflict($conn, $day, $start_time, $end_time, $teacher, $std, $division, $exclude_id = null) {
    // Base SQL query for time overlap check
    $sql = "SELECT * FROM timetable WHERE day = ? AND (
        (start_time < ? AND end_time > ?) OR
        (start_time < ? AND end_time > ?) OR
        (start_time >= ? AND end_time <= ?)
    )";
    
    // Check teacher conflict
    $teacher_sql = $sql . " AND teacher = ?";
    if ($exclude_id) {
        $teacher_sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($teacher_sql);
    if ($exclude_id) {
        $stmt->bind_param("ssssssssi", $day, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time, $teacher, $exclude_id);
    } else {
        $stmt->bind_param("ssssssss", $day, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time, $teacher);
    }
    $stmt->execute();
    $teacher_result = $stmt->get_result();
    
    // Check class conflict
    $class_sql = $sql . " AND std = ? AND division = ?";
    if ($exclude_id) {
        $class_sql .= " AND id != ?";
    }
    
    $class_stmt = $conn->prepare($class_sql);
    if ($exclude_id) {
        $class_stmt->bind_param("sssssssssi", $day, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time, $std, $division, $exclude_id);
    } else {
        $class_stmt->bind_param("sssssssss", $day, $end_time, $start_time, $end_time, $start_time, $start_time, $end_time, $std, $division);
    }
    $class_stmt->execute();
    $class_result = $class_stmt->get_result();
    
    $has_conflict = $teacher_result->num_rows > 0 || $class_result->num_rows > 0;
    
    $stmt->close();
    $class_stmt->close();
    
    return $has_conflict;
}

// Handle form submission (Add)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    try {
        $day = $_POST['day'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $subject = $_POST['subject'];
        $teacher = $_POST['teacher'];
        $std = $_POST['std'];
        $division = $_POST['division'];

        // Basic validation
        if (empty($day) || empty($start_time) || empty($end_time) || empty($subject) || empty($teacher) || empty($std) || empty($division)) {
            $message = "All fields are required!";
        } elseif ($start_time >= $end_time) {
            $message = "End time must be after start time!";
        } elseif (hasConflict($conn, $day, $start_time, $end_time, $teacher, $std, $division)) {
            $message = "Scheduling conflict detected! Teacher or class already booked for this time slot.";
        } else {
            $sql = "INSERT INTO timetable (day, start_time, end_time, subject, teacher, std, division) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssss", $day, $start_time, $end_time, $subject, $teacher, $std, $division);
                if ($stmt->execute()) {
                    $message = "Schedule added successfully!";
                    $stmt->close();
                    header("Location: timetable.php?message=" . urlencode($message));
                    exit();
                } else {
                    $message = "Error adding schedule: " . $stmt->error;
                    $stmt->close();
                }
            } else {
                $message = "Error preparing statement: " . $conn->error;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Handle edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    try {
        $id = $_POST['id'];
        $day = $_POST['day'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $subject = $_POST['subject'];
        $teacher = $_POST['teacher'];
        $std = $_POST['std'];
        $division = $_POST['division'];

        // Basic validation
        if (empty($day) || empty($start_time) || empty($end_time) || empty($subject) || empty($teacher) || empty($std) || empty($division)) {
            $message = "All fields are required!";
        } elseif ($start_time >= $end_time) {
            $message = "End time must be after start time!";
        } elseif (hasConflict($conn, $day, $start_time, $end_time, $teacher, $std, $division, $id)) {
            $message = "Scheduling conflict detected! Teacher or class already booked for this time slot.";
        } else {
            $sql = "UPDATE timetable SET day=?, start_time=?, end_time=?, subject=?, teacher=?, std=?, division=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssssi", $day, $start_time, $end_time, $subject, $teacher, $std, $division, $id);
                if ($stmt->execute()) {
                    $message = "Schedule updated successfully!";
                    $stmt->close();
                    header("Location: timetable.php?message=" . urlencode($message));
                    exit();
                } else {
                    $message = "Error updating schedule: " . $stmt->error;
                    $stmt->close();
                }
            } else {
                $message = "Error preparing statement: " . $conn->error;
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
        if ($id === false) {
            $message = "Invalid ID!";
            header("Location: timetable.php?message=" . urlencode($message));
            exit();
        } else {
            $sql = "DELETE FROM timetable WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message = "Schedule deleted successfully!";
                    } else {
                        $message = "No schedule found with that ID!";
                    }
                    $stmt->close();
                    header("Location: timetable.php?message=" . urlencode($message));
                    exit();
                } else {
                    $message = "Error deleting schedule: " . $stmt->error;
                    $stmt->close();
                    header("Location: timetable.php?message=" . urlencode($message));
                    exit();
                }
            } else {
                $message = "Error preparing statement: " . $conn->error;
                header("Location: timetable.php?message=" . urlencode($message));
                exit();
            }
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        header("Location: timetable.php?message=" . urlencode($message));
        exit();
    }
}

// Fetch timetable data
$sql = "SELECT * FROM timetable ORDER BY std, division, FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time";
$result = $conn->query($sql);
if (!$result) {
    $message = "Error fetching timetable: " . $conn->error;
}

// Fetch data for editing if edit ID is provided
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);
    if ($edit_id !== false) {
        $edit_sql = "SELECT * FROM timetable WHERE id = ?";
        $edit_stmt = $conn->prepare($edit_sql);
        $edit_stmt->bind_param("i", $edit_id);
        $edit_stmt->execute();
        $edit_result = $edit_stmt->get_result();
        $edit_data = $edit_result->fetch_assoc();
        $edit_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool - Timetable</title>
    <link rel="stylesheet" href="dashbaord.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f7fa; }
        .container { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar {
            width: 250px; background-color: #2c3e50; color: white; padding: 1rem;
            position: fixed; height: 100vh; overflow-y: auto;
        }
        .logo h2 { color: #3498db; margin-bottom: 1rem; }
        .user-info { display: flex; align-items: center; background-color: #34495e; padding: 0.5rem; border-radius: 5px; margin-bottom: 1rem; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; margin-right: 0.5rem; }
        .sidebar nav ul { list-style: none; }
        .sidebar nav ul li { margin-bottom: 0.2rem; }
        .sidebar nav ul li a {
            color: white; text-decoration: none; display: flex; align-items: center; padding: 0.75rem; width: 100%;
            transition: background-color 0.3s ease;
        }
        .sidebar nav ul li a i { margin-right: 0.5rem; }
        .sidebar nav ul li a:hover { background-color: #34495e; border-radius: 5px; }
        .sidebar nav ul li.active a { background-color: #3498db; border-radius: 5px; }

        /* Main Content */
        .main-content { flex: 1; padding: 1rem; margin-left: 250px; }
        header { display: flex; justify-content: space-between; align-items: center; background-color: #3498db; color: white; padding: 0.5rem 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .breadcrumb { font-size: 0.9rem; }
        h1 { margin-bottom: 1rem; color: #2c3e50; }

        /* Forms */
        .timetable-form, .edit-form { 
            background-color: white; 
            padding: 1rem; 
            border-radius: 5px; 
            margin-bottom: 1rem; 
        }
        .timetable-form label, .edit-form label { 
            display: block; 
            margin: 0.5rem 0 0.2rem; 
        }
        .timetable-form select, .timetable-form input,
        .edit-form select, .edit-form input { 
            width: 100%; 
            padding: 0.5rem; 
            margin-bottom: 1rem; 
            border: 1px solid #ddd; 
            border-radius: 3px; 
        }
        .timetable-form button, .edit-form button { 
            padding: 0.5rem 1rem; 
            background-color: #3498db; 
            color: white; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
        }
        .timetable-form button:hover, .edit-form button:hover { 
            background-color: #2980b9; 
        }
        .edit-form button.cancel {
            background-color: #e74c3c;
            margin-left: 0.5rem;
        }
        .edit-form button.cancel:hover {
            background-color: #c0392b;
        }
        .edit-form {
            display: <?php echo $edit_data ? 'block' : 'none'; ?>;
        }

        /* Timetable Table */
        .timetable-table { 
            background-color: white; 
            padding: 1rem; 
            border-radius: 5px; 
            overflow-x: auto; 
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem; text-align: left; border: 1px solid #ddd; }
        th { background-color: #3498db; color: white; }
        td a { color: #e74c3c; text-decoration: none; margin-right: 0.5rem; }
        td a:hover { text-decoration: underline; }

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
        .print-btn:hover { background-color: #219653; }

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
            .sidebar, .timetable-form, .edit-form, header, .print-btn, .message { display: none; }
            .main-content { margin-left: 0; padding: 0; }
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
            <div class="logo"><h2>MySchool</h2></div>
            <div class="user-info">
                <img src="ad.jpeg" alt="User Avatar" class="avatar">
                <span>admin007</span>
            </div>
            <nav>
            <ul>
        <li>
        <a href="dashboard.php" padding ="10px" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="students.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-users"></i> Students
        </a>
    </li>
    <li>
        <a href="teachers.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-chalkboard-teacher"></i> Teachers
        </a>
    </li>
    <li >
        <a href="parents.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-user-friends"></i> Parents
        </a>
    </li>
    <li >
        <a href="attendance.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clipboard-check"></i> Attendance
        </a>
    </li>
    <li >
        <a href="subjects.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-book-open"></i> Subjects
        </a>
    </li>
    <li>
        <a href="noticeboard.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-file-alt"></i> Digital Notice Board
        </a>
    </li>
    <li>
        <a href="event.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
    </li>
    <li class="active" style="padding: 10px; background-color: transparent;">
        <a href="timetable.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clock"></i> Time Table
        </a>
    </li>
</ul>

            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div class="breadcrumb"><span>Home / Time Table</span></div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span>admin007</span>
                    <i class="fas fa-sign-out-alt"></i>
                </div>
            </header>

            <h1>Timetable Scheduling</h1>

            <!-- Display Messages -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo strpos($message, 'success') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Edit Timetable Form -->
            <?php if ($edit_data): ?>
            <div class="edit-form">
                <h3>Edit Schedule</h3>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                    
                    <label for="day">Day:</label>
                    <select id="day" name="day" required>
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days as $d) {
                            $selected = $edit_data['day'] === $d ? 'selected' : '';
                            echo "<option value='$d' $selected>$d</option>";
                        }
                        ?>
                    </select>

                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($edit_data['start_time']); ?>" required>

                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($edit_data['end_time']); ?>" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($edit_data['subject']); ?>" required>

                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher" value="<?php echo htmlspecialchars($edit_data['teacher']); ?>" required>

                    <label for="std">Standard:</label>
                    <select id="std" name="std" required>
                        <?php
                        for ($i = 1; $i <= 10; $i++) {
                            $selected = $edit_data['std'] == $i ? 'selected' : '';
                            echo "<option value='$i' $selected>{$i}th</option>";
                        }
                        ?>
                    </select>

                    <label for="division">Division:</label>
                    <select id="division" name="division" required>
                        <?php
                        $divisions = ['A', 'B', 'C', 'D'];
                        foreach ($divisions as $div) {
                            $selected = $edit_data['division'] === $div ? 'selected' : '';
                            echo "<option value='$div' $selected>$div</option>";
                        }
                        ?>
                    </select>

                    <button type="submit" name="edit">Update Schedule</button>
                    <button type="button" class="cancel" onclick="window.location.href='timetable.php'">Cancel</button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Add Timetable Form -->
            <div class="timetable-form">
                <h3>Add Schedule</h3>
                <form method="POST">
                    <label for="day">Day:</label>
                    <select id="day" name="day" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>

                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required>

                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter subject" required>

                    <label for="teacher">Teacher:</label>
                    <input type="text" id="teacher" name="teacher" placeholder="Enter teacher name" required>

                    <label for="std">Standard:</label>
                    <select id="std" name="std" required>
                        <option value="">Select Standard</option>
                        <option value="1">1st</option>
                        <option value="2">2nd</option>
                        <option value="3">3rd</option>
                        <option value="4">4th</option>
                        <option value="5">5th</option>
                        <option value="6">6th</option>
                        <option value="7">7th</option>
                        <option value="8">8th</option>
                        <option value="9">9th</option>
                        <option value="10">10th</option>
                    </select>

                    <label for="division">Division:</label>
                    <select id="division" name="division" required>
                        <option value="">Select Division</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>

                    <button type="submit" name="add">Add Schedule</button>
                </form>
            </div>

            <!-- Timetable Display -->
            <div class="timetable-table">
                <h3>Weekly Timetable</h3>
                <button class="print-btn" onclick="window.print()">Print Timetable</button>
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Standard</th>
                            <th>Division</th>
                            <th>Actions</th>
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
                                    <td><?php echo htmlspecialchars($row['std']) . 'th'; ?></td>
                                    <td><?php echo htmlspecialchars($row['division']); ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id']; ?>">Edit</a> | 
                                        <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No schedules found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>