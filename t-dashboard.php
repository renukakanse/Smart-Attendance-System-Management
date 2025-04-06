<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: t-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool Teacher Dashboard</title>
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

        .assignments, .attendance {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            flex: 1;
        }

        .assignments table, .attendance table {
            width: 100%;
            border-collapse: collapse;
        }

        .assignments th, .assignments td,
        .attendance th, .attendance td {
            padding: 0.5rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        .assignments th, .attendance th {
            background-color: #3498db;
            color: white;
        }

        /* Popup Styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .popup-content {
            background-color: white;
            width: 400px;
            padding: 1.5rem;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .popup-content label {
            display: block;
            margin: 0.5rem 0 0.2rem;
        }

        .popup-content input,
        .popup-content textarea,
        .popup-content select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .popup-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn.save {
            background-color: #3498db;
            color: white;
        }

        .btn.cancel {
            background-color: #ecf0f1;
            color: #2c3e50;
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
                    <li class="active">
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
                    <li>
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
                    <span>Home / Teacher Dashboard</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                    <a href="index.php?logout=true"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>

            <h1>Teacher Dashboard</h1>
            <div class="cards">
                <div class="card blue">
                    <h2>3</h2>
                    <p>Classes</p>
                    <a href="t-classes.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card green">
                    <h2>5</h2>
                    <p>Assignments</p>
                    <a href="t-assignments.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card orange">
                    <h2>30</h2>
                    <p>Students</p>
                    <a href="t-attendance.php">MORE INFO <i class="fas fa-plus-circle"></i></a>
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
                        <tbody id="assignment-table-body">
                            <!-- Sample data, replace with dynamic content -->
                            <tr>
                                <td>Math Homework</td>
                                <td>Class 10A</td>
                                <td>10-04-2025</td>
                            </tr>
                            <tr>
                                <td>Science Project</td>
                                <td>Class 9B</td>
                                <td>15-04-2025</td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn save" onclick="openAssignmentPopup()" style="margin-top: 1rem;">Add Assignment</button>
                </div>

                <div class="attendance">
                    <h3>Today's Attendance</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Present</th>
                                <th>Absent</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <!-- Sample data, replace with dynamic content -->
                            <tr>
                                <td>Class 10A</td>
                                <td>25</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Class 9B</td>
                                <td>28</td>
                                <td>2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="popup" id="assignmentPopup">
                <div class="popup-content">
                    <h3>Add Assignment</h3>
                    <form id="assignmentForm">
                        <label for="assignmentTitle">Title:</label>
                        <input type="text" id="assignmentTitle" name="assignmentTitle" placeholder="Enter assignment title" required>

                        <label for="assignmentClass">Class:</label>
                        <select id="assignmentClass" name="assignmentClass" required>
                            <option value="" disabled selected>Select Class</option>
                            <option value="Class 10A">Class 10A</option>
                            <option value="Class 9B">Class 9B</option>
                            <!-- Add more classes as needed -->
                        </select>

                        <label for="dueDate">Due Date:</label>
                        <input type="date" id="dueDate" name="dueDate" required>

                        <label for="assignmentDescription">Description:</label>
                        <textarea id="assignmentDescription" name="assignmentDescription" rows="3" placeholder="Enter assignment description"></textarea>

                        <div class="popup-buttons">
                            <button type="button" class="btn save" onclick="saveAssignment()">Save</button>
                            <button type="button" class="btn cancel" onclick="closeAssignmentPopup()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        let assignments = JSON.parse(localStorage.getItem('teacherAssignments')) || [];

        function openAssignmentPopup() {
            document.getElementById('assignmentPopup').style.display = 'block';
        }

        function closeAssignmentPopup() {
            document.getElementById('assignmentPopup').style.display = 'none';
            document.getElementById('assignmentForm').reset();
        }

        function saveAssignment() {
            const title = document.getElementById('assignmentTitle').value;
            const className = document.getElementById('assignmentClass').value;
            const dueDate = document.getElementById('dueDate').value;
            const description = document.getElementById('assignmentDescription').value;

            if (!title || !className || !dueDate) {
                alert('Please fill in all required fields');
                return;
            }

            const assignment = { title, className, dueDate, description };
            assignments.push(assignment);
            localStorage.setItem('teacherAssignments', JSON.stringify(assignments));

            updateAssignmentTable();
            closeAssignmentPopup();
        }

        function updateAssignmentTable() {
            const tableBody = document.getElementById('assignment-table-body');
            tableBody.innerHTML = '';
            assignments.forEach(assignment => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${assignment.title}</td>
                    <td>${assignment.className}</td>
                    <td>${assignment.dueDate}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Initialize assignment table
        updateAssignmentTable();
    </script>
</body>
</html>