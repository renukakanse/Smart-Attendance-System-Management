<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
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

        .badge {
            background-color: #e74c3c;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            margin-left: auto;
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
            grid-template-columns: repeat(4, 1fr);
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
        .card.red { background-color: #e74c3c; }

        .bottom-section {
            display: flex;
            gap: 1rem;
        }

        .activities, .exams {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            flex: 1;
        }

        .legend {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .legend span {
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            color: white;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .legend .event { background-color: #3498db; }
        .legend .exam { background-color: #e74c3c; }
        .legend .holidays { background-color: #2ecc71; }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .calendar-controls button {
            padding: 0.3rem 0.5rem;
            border: none;
            background-color: #ecf0f1;
            cursor: pointer;
            margin-left: 0.3rem;
            border-radius: 3px;
        }

        .calendar-controls button.active {
            background-color: #3498db;
            color: white;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th, .calendar td {
            padding: 0.75rem;
            text-align: center;
            border: 1px solid #ddd;
        }

        .calendar td {
            cursor: pointer;
        }

        .calendar td.today {
            background-color: #3498db;
            color: white;
        }

        .calendar td.inactive {
            color: #bdc3c7;
        }

        .event-markers {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin-top: 4px;
        }

        .event-marker {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .event-marker.event { background-color: #3498db; }
        .event-marker.exam { background-color: #e74c3c; }
        .event-marker.holidays { background-color: #2ecc71; }

        .view-container { width: 100%; }

        #day-events {
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .day-event {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 5px;
            color: white;
            display: flex;
            justify-content: space-between;
        }

        .day-event.event { background-color: #3498db; }
        .day-event.exam { background-color: #e74c3c; }
        .day-event.holidays { background-color: #2ecc71; }

        .exams table {
            width: 100%;
            border-collapse: collapse;
        }

        .exams th, .exams td {
            padding: 0.5rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        .exams th {
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

        .popup-content select,
        .popup-content textarea,
        .popup-content input {
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
                <h2>myschool</h2>
            </div>
            <div class="user-info">
                <img src="logo.jpeg" alt="User Avatar" class="avatar">
                <span>admin007</span>
            </div>
            <nav>
            <ul>
            <li class="active" style="padding: 10px; background-color: transparent;">
            
        <a href="dashboard.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="students.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-users"></i> Students
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="teachers.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-chalkboard-teacher"></i> Teachers
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="parents.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-user-friends"></i> Parents
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="attendance.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clipboard-check"></i> Attendance
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="subjects.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-book-open"></i> Subjects
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="noticeboard.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-file-alt"></i> Digital Notice Board
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="event.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="timetable.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clock"></i> Time Table
        </a>
    </li>
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
                    <span>admin007</span>
                    <i class="fas fa-sign-out-alt"></i>
                </div>
            </header>

            <h1>Dashboard</h1>
            <div class="cards">
                <div class="card blue">
                    <h2>3</h2>
                    <p>Students</p>
                    <a href="#">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card green">
                    <h2>4</h2>
                    <p>Teachers</p>
                    <a href="#">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card orange">
                    <h2>3</h2>
                    <p>Parents</p>
                    <a href="#">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
                <div class="card red">
                    <h2>3</h2>
                    <p>Class</p>
                    <a href="#">MORE INFO <i class="fas fa-plus-circle"></i></a>
                </div>
            </div>

            <div class="bottom-section">
                <div class="activities">
                    <h3>Activities</h3>
                    <div class="legend">
                        <span class="event">Event</span>
                        <span class="exam">Exam</span>
                        <span class="holidays">Holidays</span>
                    </div>
                    <div class="calendar-header">
                        <h2 id="calendar-title">April 2025</h2>
                        <div class="calendar-controls">
                            <button id="prev"><i class="fas fa-chevron-left"></i></button>
                            <button id="next"><i class="fas fa-chevron-right"></i></button>
                            <button id="month-view" class="active">month</button>
                            <button id="week-view">week</button>
                            <button id="day-view">day</button>
                        </div>
                    </div>
                    <div id="calendar-regex-container">
                        <table class="calendar" id="calendar">
                            <thead>
                                <tr>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                    <th>Sun</th>
                                </tr>
                            </thead>
                            <tbody id="calendar-body"></tbody>
                        </table>
                        <div id="week-view-container" class="view-container" style="display: none;"></div>
                        <div id="day-view-container" class="view-container" style="display: none;">
                            <div id="day-events"></div>
                        </div>
                    </div>
                </div>

                <div class="exams">
                    <h3>Exams</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Exam</th>
                            </tr>
                        </thead>
                        <tbody id="exam-table-body"></tbody>
                    </table>
                </div>
            </div>

            <div class="popup" id="eventPopup">
                <div class="popup-content">
                    <h3>Add Event</h3>
                    <form id="eventForm">
                        <label for="eventTitle">Title:</label>
                        <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter event title" required>

                        <label for="eventColor">Color:</label>
                        <select id="eventColor" name="eventColor" onchange="toggleTimeFields()">
                            <option value="event">Event (Blue)</option>
                            <option value="exam">Exam (Red)</option>
                            <option value="holidays">Holidays (Green)</option>
                        </select>

                        <label for="eventDescription">Description:</label>
                        <textarea id="eventDescription" name="eventDescription" rows="3" placeholder="Enter event description"></textarea>

                        <label for="startTime">Start Time:</label>
                        <input type="time" id="startTime" name="startTime">

                        <label for="endTime">End Time:</label>
                        <input type="time" id="endTime" name="endTime">

                        <div class="popup-buttons">
                            <button type="button" class="btn save" onclick="saveEvent()">Save</button>
                            <button type="button" class="btn cancel" onclick="closePopup()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentDate = getISTDate(); // Use IST date
        let selectedDate = null;
        let events = JSON.parse(localStorage.getItem('calendarEvents')) || {};

        function getISTDate() {
            const now = new Date();
            const istOffset = 5.5 * 60 * 60 * 1000; // 5 hours 30 minutes in milliseconds
            const utcTime = now.getTime() + (now.getTimezoneOffset() * 60 * 1000); // Convert to UTC
            return new Date(utcTime + istOffset);
        }

        function formatIndianDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function parseIndianDate(dateStr) {
            const [day, month, year] = dateStr.split('-');
            return new Date(year, month - 1, day);
        }

        function saveEventsToStorage() {
            localStorage.setItem('calendarEvents', JSON.stringify(events));
        }

        function generateCalendar() {
            const calendarBody = document.getElementById('calendar-body');
            calendarBody.innerHTML = '';
            
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const today = getISTDate(); // Use IST for today
            
            const startDay = (firstDay.getDay() + 6) % 7; // Shift to Mon-Sun order
            let date = 1;
            
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');
                
                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');
                    const cellIndex = i * 7 + j;
                    
                    if (cellIndex < startDay || date > lastDay.getDate()) {
                        cell.className = 'inactive';
                        cell.textContent = '';
                    } else {
                        const currentDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), date);
                        cell.textContent = date;
                        cell.dataset.date = formatIndianDate(currentDay);
                        
                        if (today.getFullYear() === currentDate.getFullYear() &&
                            today.getMonth() === currentDate.getMonth() &&
                            today.getDate() === date) {
                            cell.className = 'today';
                        }
                        
                        const markers = document.createElement('div');
                        markers.className = 'event-markers';
                        cell.appendChild(markers);
                        
                        updateCellEvents(cell); // Update markers for this cell
                        cell.onclick = () => openPopup(cell.dataset.date);
                        date++;
                    }
                    row.appendChild(cell);
                }
                calendarBody.appendChild(row);
                if (date > lastDay.getDate()) break;
            }
            
            document.getElementById('calendar-title').textContent = 
                currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
            updateExamTable(); // Update exams table after generating calendar
        }

        function updateCellEvents(cell) {
            const date = cell.dataset.date;
            const markers = cell.querySelector('.event-markers');
            markers.innerHTML = '';
            
            if (events[date]) {
                events[date].forEach(event => {
                    const marker = document.createElement('div');
                    marker.className = `event-marker ${event.type}`;
                    markers.appendChild(marker);
                });
            }
        }

        function updateExamTable() {
            const examTableBody = document.getElementById('exam-table-body');
            examTableBody.innerHTML = '';
            
            for (const date in events) {
                events[date].forEach(event => {
                    if (event.type === 'exam') {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${date}</td>
                            <td>${event.title}</td>
                        `;
                        examTableBody.appendChild(row);
                    }
                });
            }
        }

        function openPopup(date) {
            selectedDate = date;
            document.getElementById('eventPopup').style.display = 'block';
            toggleTimeFields();
        }

        function closePopup() {
            document.getElementById('eventPopup').style.display = 'none';
            document.getElementById('eventForm').reset();
            document.getElementById('startTime').removeAttribute('required');
            document.getElementById('endTime').removeAttribute('required');
            selectedDate = null;
        }

        function toggleTimeFields() {
            const eventType = document.getElementById('eventColor').value;
            const startTime = document.getElementById('startTime');
            const endTime = document.getElementById('endTime');
            
            if (eventType === 'exam') {
                startTime.setAttribute('required', 'required');
                endTime.setAttribute('required', 'required');
            } else {
                startTime.removeAttribute('required');
                endTime.removeAttribute('required');
            }
        }

        function saveEvent() {
            const title = document.getElementById('eventTitle').value;
            const type = document.getElementById('eventColor').value;
            const description = document.getElementById('eventDescription').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;

            if (!title || !selectedDate) return;
            if (type === 'exam' && (!startTime || !endTime)) {
                alert('Start Time and End Time are required for exams');
                return;
            }

            if (!events[selectedDate]) events[selectedDate] = [];
            events[selectedDate].push({ title, type, description, startTime, endTime });

            saveEventsToStorage(); // Save to localStorage

            const dayCell = document.querySelector(`.calendar td[data-date="${selectedDate}"]`);
            if (dayCell) {
                updateCellEvents(dayCell);
            }

            if (type === 'exam') {
                updateExamTable();
            }

            if (document.getElementById('day-view').classList.contains('active')) {
                updateDayView(selectedDate);
            }

            closePopup();
        }

        function updateDayView(date) {
            const dayEvents = document.getElementById('day-events');
            dayEvents.innerHTML = '';
            
            if (events[date]) {
                events[date].forEach(event => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = `day-event ${event.type}`;
                    eventDiv.innerHTML = `
                        <span>${event.title}</span>
                        <span class="event-time">${event.startTime || ''} ${event.endTime ? '-' : ''} ${event.endTime || ''}</span>
                    `;
                    dayEvents.appendChild(eventDiv);
                });
            }
        }

        document.getElementById('month-view').onclick = () => {
            document.querySelectorAll('.calendar-controls button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('month-view').classList.add('active');
            document.getElementById('calendar-regex-container').children[0].style.display = 'table';
            document.getElementById('week-view-container').style.display = 'none';
            document.getElementById('day-view-container').style.display = 'none';
        };

        document.getElementById('day-view').onclick = () => {
            document.querySelectorAll('.calendar-controls button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('day-view').classList.add('active');
            document.getElementById('calendar-regex-container').children[0].style.display = 'none';
            document.getElementById('week-view-container').style.display = 'none';
            document.getElementById('day-view-container').style.display = 'block';
            updateDayView(selectedDate || formatIndianDate(currentDate));
        };

        document.getElementById('week-view').onclick = () => {
            document.querySelectorAll('.calendar-controls button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('week-view').classList.add('active');
            document.getElementById('calendar-regex-container').children[0].style.display = 'none';
            document.getElementById('week-view-container').style.display = 'block';
            document.getElementById('day-view-container').style.display = 'none';
            // Week view logic can be added here if needed
        };

        document.getElementById('prev').onclick = () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar();
        };

        document.getElementById('next').onclick = () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar();
        };

        // Initialize calendar and exam table
        generateCalendar();
        updateExamTable();
    </script>
</body>
</html>