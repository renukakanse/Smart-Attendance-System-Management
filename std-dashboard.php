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
    <title>MySchool - Student Dashboard</title>
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

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .card {
            padding: 1rem;
            border-radius: 5px;
            color: white;
            text-align: center;
        }

        .card.blue { background-color: #3498db; }
        .card.green { background-color: #2ecc71; }
        .card.orange { background-color: #f39c12; }

        .card a {
            color: white;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .bottom-section {
            display: flex;
            gap: 1rem;
        }

        .assignments, .events {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            flex: 1;
        }

        .assignments table, .events table {
            width: 100%;
            border-collapse: collapse;
        }

        .assignments th, .assignments td,
        .events th, .events td {
            padding: 0.5rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        .assignments th, .events th {
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
                <img src="https://via.placeholder.com/40" alt="Student Avatar" class="avatar">
                <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
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
                    <span>Home / Dashboard</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                    <a href="login.php?logout=true"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <h1>Student Dashboard</h1>
            <div class="cards">
                <div class="card blue">
                    <h2>5</h2>
                    <p>Assignments</p>
                    <a href="std-assignments.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card green">
                    <h2>90%</h2>
                    <p>Attendance</p>
                    <a href="std-attendance.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card orange">
                    <h2>3</h2>
                    <p>Upcoming Events</p>
                    <a href="std-events.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
            </div>

            <div class="bottom-section">
                <div class="assignments">
                    <h3>Recent Assignments</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Class</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody id="assignments-table-body"></tbody>
                    </table>
                </div>

                <div class="events">
                    <h3>Upcoming Events</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="events-table-body"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const assignments = JSON.parse(localStorage.getItem('teacherAssignments')) || [];
        const events = JSON.parse(localStorage.getItem('calendarEvents')) || {};

        function updateAssignmentTable() {
            const tableBody = document.getElementById('assignments-table-body');
            tableBody.innerHTML = '';
            assignments.slice(0, 5).forEach(assignment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${assignment.title}</td>
                    <td>${assignment.className}</td>
                    <td>${assignment.dueDate}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        function updateEventsTable() {
            const tableBody = document.getElementById('events-table-body');
            tableBody.innerHTML = '';
            const now = new Date();
            let eventList = [];

            for (const date in events) {
                const eventDate = new Date(date.split('-').reverse().join('-'));
                if (eventDate >= now) {
                    events[date].forEach(event => {
                        eventList.push({ date, ...event });
                    });
                }
            }

            eventList.sort((a, b) => new Date(a.date.split('-').reverse().join('-')) - new Date(b.date.split('-').reverse().join('-')));
            eventList.slice(0, 5).forEach(event => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${event.title}</td>
                    <td>${event.date}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        updateAssignmentTable();
        updateEventsTable();
    </script>
</body>
</html>