<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool Dashboard</title>
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
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            overflow-y: auto;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
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

        .sidebar nav ul li i {
            margin-right: 0.5rem;
        }

        .sidebar nav ul li.active-page {
            background-color: #3498db;
            border-radius: 5px;
            color: white;
        }

        .badge {
            background-color: #e74c3c;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 1rem;
            flex: 1;
            overflow-x: hidden;
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

        .breadcrumb {
            font-size: 0.9rem;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-actions i {
            cursor: pointer;
        }

        /* Student Section */
        .student-section {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
        }

        .student-section h1 {
            margin-bottom: 1rem;
            color: #2c3e50;
            font-size: 1.5rem;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .controls-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .controls-container label {
            font-size: 0.9rem;
            color: #2c3e50;
        }

        .controls-container select, .controls-container input {
            padding: 0.3rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 0.9rem;
        }

        .right-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-grow: 1;
            justify-content: flex-end;
        }

        .right-controls .search-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .right-controls .search-box input {
            height: 38px;
        }

        .right-controls .btn, .controls-container .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            background-color: #ecf0f1;
            color: #2c3e50;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: background-color 0.3s;
            height: 38px;
        }

        .right-controls .btn:hover, .controls-container .btn:hover {
            background-color: #dfe6e9;
        }

        .right-controls .btn.add-student {
            background-color: #3498db;
            color: white;
        }

        .right-controls .btn.add-student:hover {
            background-color: #2980b9;
        }

        /* Table Wrapper */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        /* Student Table */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            table-layout: fixed;
        }

        .student-table th, .student-table td {
            padding: 0.75rem;
            text-align: left;
            border: 1px solid #ddd;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .student-table th {
            background-color: #f1f3f5;
            color: #2c3e50;
            font-weight: bold;
        }

        .student-table th:nth-child(1), .student-table td:nth-child(1) {
            width: 5%;
        }
        .student-table th:nth-child(2), .student-table td:nth-child(2) {
            width: 10%;
        }
        .student-table th:nth-child(3), .student-table td:nth-child(3) {
            width: 15%;
        }
        .student-table th:nth-child(4), .student-table td:nth-child(4) {
            width: 15%;
        }
        .student-table th:nth-child(5), .student-table td:nth-child(5) {
            width: 30%;
            cursor: pointer;
        }
        .student-table th:nth-child(6), .student-table td:nth-child(6) {
            width: 15%;
        }
        .student-table th:nth-child(7), .student-table td:nth-child(7) {
            width: 10%;
            text-align: center;
        }

        .student-table td {
            color: #2c3e50;
        }

        .student-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.3rem;
        }

        /* Action Buttons */
        .action-btn {
            padding: 0.3rem 0.5rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 0.9rem;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn.edit {
            background-color: #3498db;
            color: white;
        }

        .action-btn.delete {
            background-color: #e74c3c;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        /* Add Student Form */
        .add-student-form {
            display: none;
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .form-section {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 5px;
        }

        .form-section h2 {
            color: #2c3e50;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .form-group label {
            flex: 1;
            min-width: 150px;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            flex: 2;
            padding: 0.3rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-size: 0.9rem;
            width: 100%;
            max-width: 300px;
        }

        .form-group .radio-group {
            display: flex;
            gap: 1rem;
        }

        .form-group .radio-group input {
            margin-right: 0.3rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Popup Styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .popup-content h2 {
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .popup-content p {
            margin: 0.5rem 0;
            color: #2c3e50;
        }

        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 0.3rem 0.6rem;
            cursor: pointer;
        }

        .popup-close:hover {
            background: #c0392b;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>myschool</h2>
            </div>
            <div class="user-info">
                <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar">
                <span>admin007</span>
            </div>
            <nav>
                <ul>
                    <li><i class="fas fa-tachometer-alt"></i> Dashboard</li>
                    <li class="active-page"><i class="fas fa-users"></i> Students</li>
                    <li><i class="fas fa-envelope"></i> Messages <span class="badge">1</span></li>
                    <li><i class="fas fa-chalkboard-teacher"></i> Teachers</li>
                    <li><i class="fas fa-user-friends"></i> Parents</li>
                    <li><i class="fas fa-book"></i> Classes</li>
                    <li><i class="fas fa-clipboard-check"></i> Attendance</li>
                    <li><i class="fas fa-book-open"></i> Subjects</li>
                    <li><i class="fas fa-star"></i> Marks</li>
                    <li><i class="fas fa-file-alt"></i> Exams</li>
                    <li><i class="fas fa-calendar-alt"></i> Events</li>
                    <li><i class="fas fa-clock"></i> Time Table</li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header>
                <div class="breadcrumb">
                    <span>Dashboard / Students</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span class="badge">1</span>
                    <span>admin007</span>
                    <i class="fas fa-sign-out-alt"></i>
                </div>
            </header>

            <!-- Student Section -->
            <section class="student-section">
                <h1>Department/Semester/Scheme: CO6I</h1>
                <div class="table-controls">
                    <div class="controls-container">
                        <label for="class-select">Select Class Name</label>
                        <select id="class-select" onchange="filterByClass()">
                            <option value="" selected>Select</option>
                            <option value="CO6IA">CO6IA</option>
                            <option value="CO6IB">CO6IB</option>
                            <option value="CO6IC">CO6IC</option>
                        </select>
                        <label for="view-student">View</label>
                        <button id="view-student" class="btn" onclick="viewStudent()">View Student</button>
                    </div>
                    <div class="right-controls">
                        <div class="search-box">
                            <label for="search">Search:</label>
                            <input type="text" id="search" placeholder="Roll No. or Name" oninput="filterStudents()">
                        </div>
                        <button class="btn add-student" onclick="showAddStudentForm()"><i class="fas fa-plus"></i> Add student</button>
                    </div>
                </div>

                <!-- Student Table -->
                <div class="table-wrapper" id="table-wrapper">
                    <table class="student-table" id="student-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox"></th>
                                <th>Roll No.</th>
                                <th>Full Name</th>
                                <th>Parent</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="student-table-body"></tbody>
                    </table>
                </div>

                <!-- Add Student Form -->
                <div class="add-student-form" id="add-student-form">
                    <h1>Add New Student</h1>
                    <div class="form-section">
                        <h2>Personal Details</h2>
                        <div class="form-group">
                            <label for="first-name">First Name *</label>
                            <input type="text" id="first-name" required>
                        </div>
                        <div class="form-group">
                            <label for="middle-name">Middle Name *</label>
                            <input type="text" id="middle-name" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name *</label>
                            <input type="text" id="last-name" required>
                        </div>
                        <div class="form-group">
                            <label for="date-of-birth">Date of Birth (mm/dd/yyyy) *</label>
                            <input type="text" id="date-of-birth" placeholder="mm/dd/yyyy" required>
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <div class="radio-group">
                                <input type="radio" id="male" name="gender" value="Male" required>
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="gender" value="Female">
                                <label for="female">Female</label>
                                <input type="radio" id="other" name="gender" value="Other">
                                <label for="other">Other</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="current-address">Current Address</label>
                            <input type="text" id="current-address">
                        </div>
                        <div class="form-group">
                            <label for="city">City Name</label>
                            <input type="text" id="city">
                        </div>
                        <div class="form-group">
                            <label for="country">Select Country</label>
                            <select id="country">
                                <option value="">Select Country</option>
                                <option value="India">India</option>
                                <option value="USA">USA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pin-code">Pin Code</label>
                            <input type="text" id="pin-code">
                        </div>
                    </div>
                    <div class="form-section">
                        <h2>School Details</h2>
                        <div class="form-group">
                            <label for="class">Class *</label>
                            <select id="class" required>
                                <option value="">Select Class</option>
                                <option value="CO6IA">CO6IA</option>
                                <option value="CO6IB">CO6IB</option>
                                <option value="CO6IC">CO6IC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="roll-number">Roll Number</label>
                            <input type="text" id="roll-number">
                        </div>
                    </div>
                    <div class="form-section">
                        <h2>Parent Detail</h2>
                        <div class="form-group">
                            <label for="parent-email">Email Address *</label>
                            <input type="email" id="parent-email" required>
                        </div>
                        <div class="form-group">
                            <label for="parent-phone">Phone Number *</label>
                            <input type="tel" id="parent-phone" required>
                        </div>
                        <div class="form-group">
                            <label for="parent-first-name">First Name *</label>
                            <input type="text" id="parent-first-name" required>
                        </div>
                        <div class="form-group">
                            <label for="parent-middle-name">Middle Name *</label>
                            <input type="text" id="parent-middle-name" required>
                        </div>
                        <div class="form-group">
                            <label for="parent-last-name">Last Name *</label>
                            <input type="text" id="parent-last-name" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn" onclick="addStudent()">Add</button>
                        <button class="btn" onclick="cancelAddStudent()">Cancel</button>
                    </div>
                </div>

                <!-- Popup for Student Info -->
                <div class="overlay" id="popup-overlay"></div>
                <div class="popup" id="student-popup">
                    <button class="popup-close" onclick="closePopup()">X</button>
                    <div class="popup-content" id="popup-content"></div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Function to view the full address
        function viewAddress(address) {
            alert(Full Address: ${address});
        }

        // Function to edit student details
        function editStudent(button, studentId, studentName) {
            const row = button.closest('tr');
            const currentName = row.cells[2].textContent;
            const currentParent = row.cells[3].textContent;
            const currentAddress = row.cells[4].textContent;
            const currentPhone = row.cells[5].textContent;

            const newName = prompt(Edit Full Name for ${studentName} (ID: ${studentId}):, currentName);
            const newParent = prompt(Edit Parent for ${studentName} (ID: ${studentId}):, currentParent);
            const newAddress = prompt(Edit Address for ${studentName} (ID: ${studentId}):, currentAddress);
            const newPhone = prompt(Edit Phone for ${studentName} (ID: ${studentId}):, currentPhone);

            if (newName && newName.trim() !== '') row.cells[2].textContent = newName;
            if (newParent && newParent.trim() !== '') row.cells[3].textContent = newParent;
            if (newAddress && newAddress.trim() !== '') row.cells[4].textContent = newAddress;
            if (newPhone && newPhone.trim() !== '') row.cells[5].textContent = newPhone;

            saveStudentsToLocalStorage();
        }

        // Function to delete student
        function deleteStudent(button, studentId, studentName) {
            if (confirm(Are you sure you want to delete student: ${studentName} (ID: ${studentId})?)) {
                const row = button.closest('tr');
                row.remove();
                saveStudentsToLocalStorage();
            }
        }

        // Function to filter students by class
        function filterByClass() {
            const selectedClass = document.getElementById('class-select').value;
            const rows = document.querySelectorAll('#student-table-body tr');

            rows.forEach(row => {
                const className = row.getAttribute('data-class');
                row.style.display = (selectedClass === '' || className === selectedClass) ? '' : 'none';
            });

            filterStudents();
        }

        // Function to filter students based on search input
        function filterStudents() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('#student-table-body tr');

            rows.forEach(row => {
                const rollNo = row.cells[1].textContent.toLowerCase();
                const name = row.cells[2].textContent.toLowerCase();
                const classMatch = row.style.display !== 'none';
                if (classMatch) {
                    row.style.display = (rollNo.includes(searchInput) || name.includes(searchInput)) ? '' : 'none';
                }
            });
        }

        // Function to view student details
        function viewStudent() {
            const rollNo = prompt('Enter Student Roll Number:');
            if (!rollNo) return;

            const students = JSON.parse(localStorage.getItem('students')) || [];
            const student = students.find(s => s.rollNo.toLowerCase() === rollNo.toLowerCase());

            if (student) {
                const fullNameParts = student.fullName.split(' ');
                const firstName = fullNameParts[0];
                const middleName = fullNameParts[1];
                const lastName = fullNameParts.slice(2).join(' ');
                const parentNameParts = student.parent.split(' ');
                const parentFirstName = parentNameParts[0];
                const parentMiddleName = parentNameParts[1];
                const parentLastName = parentNameParts.slice(2).join(' ');

                const popupContent = document.getElementById('popup-content');
                popupContent.innerHTML = `
                    <h2>Student Information</h2>
                    <h3>Personal Details</h3>
                    <p><strong>First Name:</strong> ${firstName}</p>
                    <p><strong>Middle Name:</strong> ${middleName}</p>
                    <p><strong>Last Name:</strong> ${lastName}</p>
                    <p><strong>Date of Birth:</strong> ${student.dateOfBirth || 'Not specified'}</p>
                    <p><strong>Gender:</strong> ${student.gender || 'Not specified'}</p>
                    <p><strong>Email:</strong> ${student.email || 'Not specified'}</p>
                    <p><strong>Address:</strong> ${student.address}</p>
                    <p><strong>City:</strong> ${student.city || 'Not specified'}</p>
                    <p><strong>Country:</strong> ${student.country || 'Not specified'}</p>
                    <p><strong>Pin Code:</strong> ${student.pinCode || 'Not specified'}</p>
                    <h3>School Details</h3>
                    <p><strong>Class:</strong> ${student.class}</p>
                    <p><strong>Roll No:</strong> ${student.rollNo}</p>
                    <h3>Parent Details</h3>
                    <p><strong>Parent First Name:</strong> ${parentFirstName}</p>
                    <p><strong>Parent Middle Name:</strong> ${parentMiddleName}</p>
                    <p><strong>Parent Last Name:</strong> ${parentLastName}</p>
                    <p><strong>Parent Email:</strong> ${student.parentEmail || 'Not specified'}</p>
                    <p><strong>Parent Phone:</strong> ${student.phone}</p>
                `;
                document.getElementById('student-popup').style.display = 'block';
                document.getElementById('popup-overlay').style.display = 'block';
            } else {
                alert('Student not found!');
            }
        }

        // Function to close popup
        function closePopup() {
            document.getElementById('student-popup').style.display = 'none';
            document.getElementById('popup-overlay').style.display = 'none';
        }

        // Function to show the add student form
        function showAddStudentForm() {
            document.getElementById('table-wrapper').style.display = 'none';
            document.getElementById('add-student-form').style.display = 'block';
        }

        // Function to add student
        function addStudent() {
            const firstName = document.getElementById('first-name').value.trim();
            const middleName = document.getElementById('middle-name').value.trim();
            const lastName = document.getElementById('last-name').value.trim();
            const dateOfBirth = document.getElementById('date-of-birth').value.trim();
            const gender = document.querySelector('input[name="gender"]:checked')?.value || '';
            const email = document.getElementById('email').value.trim();
            const currentAddress = document.getElementById('current-address').value.trim();
            const city = document.getElementById('city').value.trim();
            const country = document.getElementById('country').value.trim();
            const pinCode = document.getElementById('pin-code').value.trim();
            const className = document.getElementById('class').value.trim();
            const rollNumber = document.getElementById('roll-number').value.trim();
            const parentEmail = document.getElementById('parent-email').value.trim();
            const parentPhone = document.getElementById('parent-phone').value.trim();
            const parentFirstName = document.getElementById('parent-first-name').value.trim();
            const parentMiddleName = document.getElementById('parent-middle-name').value.trim();
            const parentLastName = document.getElementById('parent-last-name').value.trim();

            if (firstName && middleName && lastName && dateOfBirth && gender && email && className && parentEmail && parentPhone && parentFirstName && parentMiddleName && parentLastName) {
                const student = {
                    id: Date.now(),
                    rollNo: rollNumber || New-${Date.now()},
                    fullName: ${firstName} ${middleName} ${lastName},
                    parent: ${parentFirstName} ${parentMiddleName} ${parentLastName},
                    address: currentAddress || ${city}, ${country} ${pinCode || ''},
                    phone: parentPhone,
                    class: className,
                    dateOfBirth: dateOfBirth,
                    gender: gender,
                    email: email,
                    city: city,
                    country: country,
                    pinCode: pinCode,
                    parentEmail: parentEmail
                };

                addStudentToTable(student);
                saveStudentsToLocalStorage();
                cancelAddStudent();
                document.getElementById('table-wrapper').style.display = 'block';
                filterByClass();
                filterStudents();
                alert('Student added successfully!');
            } else {
                alert('Please fill all required fields!');
            }
        }

        // Function to add student to table
        function addStudentToTable(student) {
            const tableBody = document.querySelector('#student-table-body');
            const newRow = document.createElement('tr');
            newRow.setAttribute('data-student-id', student.id);
            newRow.setAttribute('data-class', student.class);
            newRow.innerHTML = `
                <td><input type="checkbox"></td>
                <td>${student.rollNo}</td>
                <td>${student.fullName}</td>
                <td>${student.parent}</td>
                <td class="address-cell" onclick="viewAddress('${student.address}')">${student.address}</td>
                <td>${student.phone}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick="editStudent(this, ${student.id}, '${student.fullName}')"><i class="fas fa-edit"></i></button>
                        <button class="action-btn delete" onclick="deleteStudent(this, ${student.id}, '${student.fullName}')"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            `;
            tableBody.appendChild(newRow);
        }

        // Function to save students to localStorage
        function saveStudentsToLocalStorage() {
            const rows = document.querySelectorAll('#student-table-body tr');
            const students = [];
            rows.forEach(row => {
                const student = {
                    id: row.getAttribute('data-student-id'),
                    rollNo: row.cells[1].textContent,
                    fullName: row.cells[2].textContent,
                    parent: row.cells[3].textContent,
                    address: row.cells[4].textContent,
                    phone: row.cells[5].textContent,
                    class: row.getAttribute('data-class'),
                    dateOfBirth: row.getAttribute('data-date-of-birth') || '',
                    gender: row.getAttribute('data-gender') || '',
                    email: row.getAttribute('data-email') || '',
                    city: row.getAttribute('data-city') || '',
                    country: row.getAttribute('data-country') || '',
                    pinCode: row.getAttribute('data-pin-code') || '',
                    parentEmail: row.getAttribute('data-parent-email') || ''
                };
                students.push(student);
            });
            localStorage.setItem('students', JSON.stringify(students));
        }

        // Function to load students from localStorage
        function loadStudentsFromLocalStorage() {
            const students = JSON.parse(localStorage.getItem('students')) || [];
            const tableBody = document.querySelector('#student-table-body');
            tableBody.innerHTML = '';
            students.forEach(student => {
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-student-id', student.id);
                newRow.setAttribute('data-class', student.class);
                newRow.setAttribute('data-date-of-birth', student.dateOfBirth);
                newRow.setAttribute('data-gender', student.gender);
                newRow.setAttribute('data-email', student.email);
                newRow.setAttribute('data-city', student.city);
                newRow.setAttribute('data-country', student.country);
                newRow.setAttribute('data-pin-code', student.pinCode);
                newRow.setAttribute('data-parent-email', student.parentEmail);
                newRow.innerHTML = `
                    <td><input type="checkbox"></td>
                    <td>${student.rollNo}</td>
                    <td>${student.fullName}</td>
                    <td>${student.parent}</td>
                    <td class="address-cell" onclick="viewAddress('${student.address}')">${student.address}</td>
                    <td>${student.phone}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" onclick="editStudent(this, ${student.id}, '${student.fullName}')"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" onclick="deleteStudent(this, ${student.id}, '${student.fullName}')"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(newRow);
            });
            filterByClass();
            filterStudents();
        }

        // Function to cancel adding student
        function cancelAddStudent() {
            document.getElementById('add-student-form').style.display = 'none';
            document.getElementById('table-wrapper').style.display = 'block';
            document.getElementById('first-name').value = '';
            document.getElementById('middle-name').value = '';
            document.getElementById('last-name').value = '';
            document.getElementById('date-of-birth').value = '';
            document.querySelectorAll('input[name="gender"]').forEach(radio => radio.checked = false);
            document.getElementById('email').value = '';
            document.getElementById('current-address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('country').value = '';
            document.getElementById('pin-code').value = '';
            document.getElementById('class').value = '';
            document.getElementById('roll-number').value = '';
            document.getElementById('parent-email').value = '';
            document.getElementById('parent-phone').value = '';
            document.getElementById('parent-first-name').value = '';
            document.getElementById('parent-middle-name').value = '';
            document.getElementById('parent-last-name').value = '';
        }

        // Load students on page load
        window.onload = function() {
            loadStudentsFromLocalStorage();
        };
    </script>
</body>
</html>