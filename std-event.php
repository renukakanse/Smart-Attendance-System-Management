<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySchool - Student Events</title>
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
            overflow-y: auto;
            overflow-x: auto;
            width: 100%;
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
            min-width: 700px;
        }

        .calendar th, .calendar td {
            padding: 0.8rem;
            text-align: center;
            border: 1px solid #ddd;
            position: relative;
            min-width: 80px;
            height: 80px;
        }

        .calendar td {
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

        .calendar td:has(.event-title) .event-tooltip {
            display: none;
        }

        .calendar td:has(.event-title):hover .event-tooltip {
            display: block;
        }

        /* Print Styles */
        @media print {
            .sidebar, .calendar-controls, header { display: none; }
            .main-content { margin-left: 0; padding: 0; }
            .calendar-section { max-height: none; overflow: visible; }
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
                <img src="std.jpeg" alt="User Avatar" class="avatar">
                <span>Student</span>
            </div>
            <nav>
                <ul>
                <ul style="list-style: none; padding: 0; margin: 0; background-color: transparent; font-family: Arial, sans-serif;">
    <li style="padding: 10px;">
        <a href="dashboard.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-tachometer-alt" style="margin-right: 10px;"></i> Dashboard
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="students.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-users" style="margin-right: 10px;"></i> Students
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="teachers.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-chalkboard-teacher" style="margin-right: 10px;"></i> Teachers
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="parents.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-user-friends" style="margin-right: 10px;"></i> Parents
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="attendance.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clipboard-check" style="margin-right: 10px;"></i> Attendance
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="subjects.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-book-open" style="margin-right: 10px;"></i> Subjects
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="noticeboard.html" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-file-alt" style="margin-right: 10px;"></i> Digital Notice Board
        </a>
    </li>
    <li class="active" style="padding: 10px; background-color: transparent; border-left: 4px solid #00bfff;">
        <a href="event.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-calendar-alt" style="margin-right: 10px;"></i> Events
        </a>
    </li>
    <li style="padding: 10px;">
        <a href="std-timetable.php" style="color: white; text-decoration: none; display: block;">
            <i class="fas fa-clock" style="margin-right: 10px;"></i> Time Table
        </a>
    </li>
</ul>

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
                    <span>Student123</span>
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
        </main>
    </div>

    <script>
    let currentDate = getISTDate();
    let events = JSON.parse(localStorage.getItem('calendarEvents')) || {};

    function getISTDate() {
        const now = new Date();
        const istOffset = 5.5 * 60 * 60 * 1000;
        const utcTime = now.getTime() + (now.getTimezoneOffset() * 60 * 1000);
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

    function generateCalendar() {
        const calendarBody = document.getElementById('calendar-body');
        calendarBody.innerHTML = '';
        
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        const today = getISTDate();
        
        const startDay = (firstDay.getDay() + 6) % 7;
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
            events[date].forEach((event) => {
                const marker = document.createElement('div');
                marker.className = `event-marker ${event.type}`;
                markers.appendChild(marker);

                if (!titleDiv.innerHTML) {
                    titleDiv.innerHTML = `${event.title}`;
                }

                const eventDiv = document.createElement('div');
                let bodyContent = '';

                if (event.startTime) {
                    bodyContent += `<p>Start: ${formatIndianDate(new Date(`${date} ${event.startTime}`))} ${event.startTime}</p>`;
                }
                if (event.endTime) {
                    bodyContent += `<p>End: ${formatIndianDate(new Date(`${date} ${event.endTime}`))} ${event.endTime}</p>`;
                }
                if (event.description) {
                    bodyContent += `<p>Description: ${event.description}</p>`;
                }

                eventDiv.innerHTML = `
                    <div class="header">
                        <span class="title">${event.title}</span>
                        <span class="close">×</span>
                    </div>
                    <div class="body">
                        ${bodyContent}
                    </div>
                `;
                tooltip.appendChild(eventDiv);

                eventDiv.querySelector('.close').onclick = (e) => {
                    e.stopPropagation();
                    tooltip.style.display = 'none';
                };
            });
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

    function checkDateChange() {
        const now = getISTDate();
        const lastDate = currentDate.getDate();
        if (now.getDate() !== lastDate) {
            currentDate = getISTDate();
            generateCalendar();
        }
    }

    setInterval(checkDateChange, 60000);

    generateCalendar();
    </script>
</body>
</html>