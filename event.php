<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool Events</title>
    <link rel="stylesheet" href="dashboard.css">
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

        .calendar-section {
            background-color: white;
            padding: 1rem;
            border-radius: 5px;
            max-height: 70vh;
            overflow-y: auto; /* Vertical scroll */
            overflow-x: auto; /* Horizontal scroll */
            width: 100%; /* Ensure it takes full width of parent */
        }

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

        .calendar {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px; /* Ensure table has a minimum width to trigger horizontal scroll if needed */
        }

        .calendar th, .calendar td {
            padding: 0.8rem;
            text-align: center;
            border: 1px solid #ddd;
            position: relative;
            min-width: 80px; /* Minimum width for cells */
            height: 80px;
        }

        .calendar td {
            cursor: pointer;
            vertical-align: top;
        }

        .calendar td.inactive {
            color: #bdc3c7;
        }

        .calendar td .date-number.today {
            color: #3498db;
            font-weight: bold;
        }

        .event-markers {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin-top: 2px;
        }

        .event-marker {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .event-marker.event { background-color: #3498db; }
        .event-marker.exam { background-color: #e74c3c; }
        .event-marker.holidays { background-color: #2ecc71; }

        .event-title {
            font-size: 0.7rem;
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Event Form Popup */
        .event-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .event-form {
            background-color: white;
            width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 1rem;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .event-form label {
            display: block;
            margin: 0.5rem 0 0.2rem;
        }

        .event-form input,
        .event-form select,
        .event-form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .event-form .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 0.5rem;
        }

        .event-form .btn.save {
            background-color: #3498db;
            color: white;
        }

        .event-form .btn.cancel {
            background-color: #ecf0f1;
            color: #2c3e50;
        }

        /* Hover Popup */
        .event-tooltip {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            z-index: 10;
            top: 100%;
            left: 0;
            min-width: 250px;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            box-sizing: border-box;
        }

        .event-tooltip .header {
            background-color: #3498db;
            color: white;
            padding: 0.5rem;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            position: relative;
        }

        .event-tooltip .header .title {
            font-size: 1rem;
            font-weight: bold;
        }

        .event-tooltip .close {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
            font-size: 1rem;
            color: white;
        }

        .event-tooltip .body {
            padding: 0.5rem;
            background-color: #fff;
            color: #333;
        }

        .event-tooltip .body p {
            margin: 0.3rem 0;
            font-size: 0.9rem;
        }

        .event-tooltip .actions {
            padding: 0.5rem;
            text-align: right;
            background-color: #fff;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .event-tooltip .edit,
        .event-tooltip .delete {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            margin-left: 0.5rem;
            cursor: pointer;
            background-color: #3498db;
            color: white;
            border-radius: 3px;
            font-size: 0.8rem;
            text-decoration: none;
        }

        .event-tooltip .delete {
            background-color: #e74c3c;
        }

        .calendar td:has(.event-title) .event-tooltip {
            display: none;
        }

        .calendar td:has(.event-title):hover .event-tooltip {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
    <aside class="sidebar">
            <div class="logo"><h2>MySchool</h2></div>
            <div class="user-info">
                <img src="logo.jpeg" alt="User Avatar" class="avatar">
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
    <li class="active" style="padding: 10px; background-color: transparent;">
        <a href="event.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-calendar-alt"></i> Events
        </a>
    </li>
   <li>
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
                    <span>Home / Events</span>
                </div>
                <div class="user-actions">
                    <i class="fas fa-envelope"></i>
                    <span>admin007</span>
                    <i class="fas fa-sign-out-alt"></i>
                </div>
            </header>

            <h1>Events</h1>
            <div class="calendar-section">
                <div class="calendar-header">
                    <h2 id="calendar-title">April 2025</h2>
                    <div class="calendar-controls">
                        <button id="prev"><i class="fas fa-chevron-left"></i></button>
                        <button id="next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
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
            </div>

            <div class="event-popup" id="eventPopup">
                <div class="event-form">
                    <h3 id="formTitle">Add Event</h3>
                    <form id="eventForm">
                        <input type="hidden" id="eventIndex" name="eventIndex">
                        <label for="eventTitle">Title:</label>
                        <input type="text" id="eventTitle" name="eventTitle" placeholder="Enter event title" required>

                        <label for="eventColor">Color:</label>
                        <select id="eventColor" name="eventColor">
                            <option value="event">Event (Blue)</option>
                            <option value="exam">Exam (Red)</option>
                            <option value="holidays">Holidays (Green)</option>
                        </select>

                        <label for="eventDescription">Description:</label>
                        <textarea id="eventDescription" name="eventDescription" rows="3" placeholder="Enter event description"></textarea>

                        <label for="eventDate">Date:</label>
                        <input type="date" id="eventDate" name="eventDate" required>

                        <label for="startTime">Start Time:</label>
                        <input type="time" id="startTime" name="startTime">

                        <label for="endTime">End Time:</label>
                        <input type="time" id="endTime" name="endTime">

                        <div>
                            <button type="button" class="btn save" onclick="saveEvent()">Save</button>
                            <button type="button" class="btn cancel" onclick="closePopup()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
    let currentDate = getISTDate(); // Initialize with today's IST date
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
        const today = getISTDate(); // Use IST current date for "today"
        
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
                    cell.dataset.date = formatIndianDate(currentDay);
                    cell.innerHTML = `<div class="date-number">${date}</div>`;
                    
                    if (today.getFullYear() === currentDate.getFullYear() &&
                        today.getMonth() === currentDate.getMonth() &&
                        today.getDate() === date) {
                        cell.querySelector('.date-number').classList.add('today');
                    }
                    
                    const markers = document.createElement('div');
                    markers.className = 'event-markers';
                    cell.appendChild(markers);

                    const titleDiv = document.createElement('div');
                    titleDiv.className = 'event-title';
                    cell.appendChild(titleDiv);

                    const tooltip = document.createElement('div');
                    tooltip.className = 'event-tooltip';
                    cell.appendChild(tooltip);
                    
                    cell.onclick = () => openPopup(cell.dataset.date);
                    updateCellEvents(cell);
                    date++;
                }
                row.appendChild(cell);
            }
            calendarBody.appendChild(row);
            if (date > lastDay.getDate()) break;
        }
        
        document.getElementById('calendar-title').textContent = 
            currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
    }

    function updateCellEvents(cell) {
        const date = cell.dataset.date;
        const markers = cell.querySelector('.event-markers');
        const titleDiv = cell.querySelector('.event-title');
        const tooltip = cell.querySelector('.event-tooltip');
        markers.innerHTML = '';
        titleDiv.innerHTML = '';
        tooltip.innerHTML = '';

        if (events[date]) {
            events[date].forEach((event, index) => {
                const marker = document.createElement('div');
                marker.className = `event-marker ${event.type}`;
                markers.appendChild(marker);

                if (!titleDiv.textContent) {
                    titleDiv.textContent = event.title;
                }

                const eventDiv = document.createElement('div');
                eventDiv.innerHTML = `
                    <div class="header">
                        <span class="title">${event.title}</span>
                        <span class="close">×</span>
                    </div>
                    <div class="body">
                        <p>Start: ${event.startTime ? formatIndianDate(new Date(`${date} ${event.startTime}`)) + ' ' + event.startTime : 'N/A'}</p>
                        <p>End: ${event.endTime ? formatIndianDate(new Date(`${date} ${event.endTime}`)) + ' ' + event.endTime : 'N/A'}</p>
                        <p>Description: ${event.description || 'No description'}</p>
                    </div>
                    <div class="actions">
                        <span class="edit" data-date="${date}" data-index="${index}">Edit</span>
                        <span class="delete" data-date="${date}" data-index="${index}">Delete</span>
                    </div>
                `;
                tooltip.appendChild(eventDiv);

                eventDiv.querySelector('.close').onclick = (e) => {
                    e.stopPropagation();
                    tooltip.style.display = 'none';
                };
                eventDiv.querySelector('.edit').onclick = (e) => {
                    e.stopPropagation();
                    editEvent(date, index);
                };
                eventDiv.querySelector('.delete').onclick = (e) => {
                    e.stopPropagation();
                    deleteEvent(date, index);
                };
            });
        }
    }

    function openPopup(date, index = null) {
        const popup = document.getElementById('eventPopup');
        const formTitle = document.getElementById('formTitle');
        popup.style.display = 'block';
        
        const [day, month, year] = date.split('-');
        const dateForInput = `${year}-${month}-${day}`;
        document.getElementById('eventDate').value = dateForInput;

        if (index !== null && events[date] && events[date][index]) {
            formTitle.textContent = 'Edit Event';
            const event = events[date][index];
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventColor').value = event.type;
            document.getElementById('eventDescription').value = event.description || '';
            document.getElementById('startTime').value = event.startTime || '';
            document.getElementById('endTime').value = event.endTime || '';
            document.getElementById('eventIndex').value = index;
        } else {
            formTitle.textContent = 'Add Event';
            document.getElementById('eventForm').reset();
            document.getElementById('eventDate').value = dateForInput;
            document.getElementById('eventIndex').value = '';
        }
    }

    function closePopup() {
        const popup = document.getElementById('eventPopup');
        popup.style.display = 'none';
        document.getElementById('eventForm').reset();
    }

    function saveEvent() {
        const title = document.getElementById('eventTitle').value;
        const type = document.getElementById('eventColor').value;
        const description = document.getElementById('eventDescription').value;
        const date = document.getElementById('eventDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const index = document.getElementById('eventIndex').value;

        if (!title || !date) {
            alert('Title and Date are required');
            return;
        }

        const eventDate = new Date(date);
        const formattedDate = formatIndianDate(eventDate);

        if (!events[formattedDate]) events[formattedDate] = [];

        if (index !== '') {
            events[formattedDate][parseInt(index)] = { title, type, description, startTime, endTime };
        } else {
            events[formattedDate].push({ title, type, description, startTime, endTime });
        }

        saveEventsToStorage(); // Save to localStorage

        const dayCell = document.querySelector(`.calendar td[data-date="${formattedDate}"]`);
        if (dayCell) {
            updateCellEvents(dayCell);
        }

        closePopup();
    }

    function editEvent(date, index) {
        openPopup(date, index);
    }

    function deleteEvent(date, index) {
        if (confirm('Are you sure you want to delete this event?')) {
            events[date].splice(index, 1);
            if (events[date].length === 0) {
                delete events[date];
            }
            saveEventsToStorage(); // Save to localStorage
            const dayCell = document.querySelector(`.calendar td[data-date="${date}"]`);
            if (dayCell) {
                updateCellEvents(dayCell);
            }
        }
    }

    document.getElementById('prev').onclick = () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar();
    };

    document.getElementById('next').onclick = () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar();
    };

    // Optional: Auto-update calendar every day at midnight IST
    function checkDateChange() {
        const now = getISTDate();
        const lastDate = currentDate.getDate();
        if (now.getDate() !== lastDate) {
            currentDate = getISTDate();
            generateCalendar();
        }
    }

    // Check every minute (60000 ms) for a new day
    setInterval(checkDateChange, 60000);

    generateCalendar();
    </script>
</body>
</html>