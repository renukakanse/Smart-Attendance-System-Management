<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool - Student Assignments</title>
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

        .assignments-section {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
        }

        .assignments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .assignments-table th, .assignments-table td {
            padding: 0.5rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        .assignments-table th {
            background-color: #3498db;
            color: white;
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
                <li class="active"><a href="std-dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="std-attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                    <li><a href="std-assignment.php"><i class="fas fa-book"></i> Assignments</a></li>
                    <li><a href="std-event.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                    <li><a href="std-timetable.php"><i class="fas fa-clock"></i> Timetable</a></li>
                    <li><a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <div class="breadcrumb">
                    <span>Home / Assignments</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                    <a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <h1>Assignments</h1>
            <div class="assignments-section">
                <h2>Your Assignments</h2>
                <table class="assignments-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Class</th>
                            <th>Due Date</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="assignments-table-body"></tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Load assignments from localStorage (assuming shared storage for simplicity)
        let assignments = JSON.parse(localStorage.getItem('teacherAssignments')) || [];

        function updateAssignmentTable() {
            const tableBody = document.getElementById('assignments-table-body');
            tableBody.innerHTML = '';
            assignments.forEach(assignment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${assignment.title}</td>
                    <td>${assignment.className}</td>
                    <td>${assignment.dueDate}</td>
                    <td>${assignment.description || 'No description provided'}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Update table on page load
        updateAssignmentTable();
    </script>
</body>
</html>