// Calendar functionality
const calendarTitle = document.getElementById('calendar-title');
const calendarBody = document.getElementById('calendar-body');
const calendarContainer = document.getElementById('calendar');
const weekViewContainer = document.getElementById('week-view-container');
const weekHeader = document.getElementById('week-header');
const weekBody = document.getElementById('week-body');
const dayViewContainer = document.getElementById('day-view-container');
const dayEvents = document.getElementById('day-events');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
const monthViewBtn = document.getElementById('month-view');
const weekViewBtn = document.getElementById('week-view');
const dayViewBtn = document.getElementById('day-view');

// Realistic events data for April 2025
const events = [
    { date: '2025-04-01', type: 'event', title: 'School Reopening Ceremony' },
    { date: '2025-04-04', type: 'holidays', title: 'Good Friday' },
    { date: '2025-04-07', type: 'holidays', title: 'Easter Monday' },
    { date: '2025-04-10', type: 'exam', title: 'Mid-Term Math Exam' },
    { date: '2025-04-11', type: 'exam', title: 'Mid-Term Science Exam' },
    { date: '2025-04-15', type: 'event', title: 'Parent-Teacher Conference' },
    { date: '2025-04-18', type: 'event', title: 'Sports Day' },
    { date: '2025-04-25', type: 'holidays', title: 'School Holiday - Anzac Day' },
    { date: '2025-04-30', type: 'event', title: 'Science Fair' }
];

// Initialize the current date and view
let currentDate = new Date(); // April 1, 2025
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let selectedDate = new Date(currentDate);
let currentView = 'month';

// Function to toggle active view button
function toggleViewButtons(activeBtn) {
    [monthViewBtn, weekViewBtn, dayViewBtn].forEach(btn => btn.classList.remove('active'));
    activeBtn.classList.add('active');
}

// Function to format date as YYYY-MM-DD
function formatDate(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}

// Function to generate the month view
function generateMonthView(month, year) {
    calendarBody.innerHTML = '';
    calendarTitle.textContent = `${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const adjustedFirstDay = (firstDay === 0) ? 6 : firstDay - 1;
    const prevMonthDays = new Date(year, month, 0).getDate();

    let day = 1;
    let prevMonthDay = prevMonthDays - adjustedFirstDay + 1;
    let nextMonthDay = 1;
    const totalCells = adjustedFirstDay + daysInMonth;
    const rows = Math.ceil(totalCells / 7);

    for (let i = 0; i < rows; i++) {
        const row = document.createElement('tr');
        for (let j = 0; j < 7; j++) {
            const cell = document.createElement('td');
            const cellIndex = i * 7 + j;

            if (cellIndex < adjustedFirstDay) {
                cell.textContent = prevMonthDay++;
                cell.classList.add('inactive');
            } else if (day <= daysInMonth) {
                cell.textContent = day;
                const cellDate = new Date(year, month, day);
                if (
                    day === currentDate.getDate() &&
                    month === currentDate.getMonth() &&
                    year === currentDate.getFullYear()
                ) {
                    cell.classList.add('today');
                }
                if (
                    day === selectedDate.getDate() &&
                    month === selectedDate.getMonth() &&
                    year === selectedDate.getFullYear()
                ) {
                    cell.style.backgroundColor = '#e0e0e0';
                }
                // Add event markers
                const dateStr = formatDate(cellDate);
                events.forEach(event => {
                    if (event.date === dateStr) {
                        cell.classList.add(`has-${event.type}`);
                    }
                });
                // Add click event to switch to day view
                cell.style.cursor = 'pointer';
                cell.addEventListener('click', () => {
                    selectedDate = new Date(year, month, day);
                    switchView('day', selectedDate);
                });
                day++;
            } else {
                cell.textContent = nextMonthDay++;
                cell.classList.add('inactive');
            }
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }
}

// Function to generate the week view
function generateWeekView(date) {
    const startOfWeek = new Date(date);
    const dayOfWeek = startOfWeek.getDay();
    const adjustedDayOfWeek = (dayOfWeek === 0) ? 6 : dayOfWeek - 1;
    startOfWeek.setDate(startOfWeek.getDate() - adjustedDayOfWeek);

    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(endOfWeek.getDate() + 6);

    calendarTitle.textContent = `${startOfWeek.toLocaleDateString('default', { month: 'long', day: 'numeric' })} - ${endOfWeek.toLocaleDateString('default', { month: 'long', day: 'numeric', year: 'numeric' })}`;

    // Generate week header
    weekHeader.innerHTML = '';
    const headerRow = document.createElement('tr');
    for (let i = 0; i < 7; i++) {
        const th = document.createElement('th');
        const currentDay = new Date(startOfWeek);
        currentDay.setDate(currentDay.getDate() + i);
        th.textContent = `${currentDay.toLocaleDateString('default', { weekday: 'short' })} ${currentDay.getDate()}`;
        headerRow.appendChild(th);
    }
    weekHeader.appendChild(headerRow);

    // Generate week body (single row)
    weekBody.innerHTML = '';
    const row = document.createElement('tr');
    for (let i = 0; i < 7; i++) {
        const cell = document.createElement('td');
        const currentDay = new Date(startOfWeek);
        currentDay.setDate(currentDay.getDate() + i);
        cell.textContent = currentDay.getDate();
        if (
            currentDay.getDate() === currentDate.getDate() &&
            currentDay.getMonth() === currentDate.getMonth() &&
            currentDay.getFullYear() === currentDate.getFullYear()
        ) {
            cell.classList.add('today');
        }
        if (
            currentDay.getDate() === selectedDate.getDate() &&
            currentDay.getMonth() === selectedDate.getMonth() &&
            currentDay.getFullYear() === selectedDate.getFullYear()
        ) {
            cell.style.backgroundColor = '#e0e0e0';
        }
        // Add event markers
        const dateStr = formatDate(currentDay);
        events.forEach(event => {
            if (event.date === dateStr) {
                cell.classList.add(`has-${event.type}`);
            }
        });
        // Add click event to switch to day view
        cell.style.cursor = 'pointer';
        cell.addEventListener('click', () => {
            selectedDate = new Date(currentDay);
            switchView('day', selectedDate);
        });
        row.appendChild(cell);
    }
    weekBody.appendChild(row);
}

// Function to generate the day view
function generateDayView(date) {
    calendarTitle.textContent = date.toLocaleDateString('default', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });

    dayEvents.innerHTML = '';
    const dateStr = formatDate(date);
    const dayEventsList = events.filter(event => event.date === dateStr);

    if (dayEventsList.length === 0) {
        dayEvents.innerHTML = '<p>No events for this day.</p>';
    } else {
        dayEventsList.forEach(event => {
            const eventDiv = document.createElement('div');
            eventDiv.classList.add('day-event', event.type);
            eventDiv.textContent = event.title;
            dayEvents.appendChild(eventDiv);
        });
    }
}

// Function to switch views
function switchView(view, date) {
    currentView = view;
    toggleViewButtons(document.getElementById(`${view}-view`));

    calendarContainer.style.display = view === 'month' ? 'table' : 'none';
    weekViewContainer.style.display = view === 'week' ? 'block' : 'none';
    dayViewContainer.style.display = view === 'day' ? 'block' : 'none';

    if (view === 'month') {
        generateMonthView(date.getMonth(), date.getFullYear());
    } else if (view === 'week') {
        generateWeekView(date);
    } else if (view === 'day') {
        generateDayView(date);
    }
}

// Initial calendar generation
switchView(currentView, selectedDate);

// Event listeners for navigation
prevBtn.addEventListener('click', () => {
    if (currentView === 'month') {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        // Adjust selectedDate to stay within valid range
        const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        selectedDate.setDate(Math.min(selectedDate.getDate(), daysInNewMonth));
        selectedDate.setMonth(currentMonth);
        selectedDate.setFullYear(currentYear);
    } else if (currentView === 'week') {
        selectedDate.setDate(selectedDate.getDate() - 7);
        currentMonth = selectedDate.getMonth();
        currentYear = selectedDate.getFullYear();
    } else if (currentView === 'day') {
        selectedDate.setDate(selectedDate.getDate() - 1);
        currentMonth = selectedDate.getMonth();
        currentYear = selectedDate.getFullYear();
    }
    switchView(currentView, selectedDate);
});

nextBtn.addEventListener('click', () => {
    if (currentView === 'month') {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        // Adjust selectedDate to stay within valid range
        const daysInNewMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        selectedDate.setDate(Math.min(selectedDate.getDate(), daysInNewMonth));
        selectedDate.setMonth(currentMonth);
        selectedDate.setFullYear(currentYear);
    } else if (currentView === 'week') {
        selectedDate.setDate(selectedDate.getDate() + 7);
        currentMonth = selectedDate.getMonth();
        currentYear = selectedDate.getFullYear();
    } else if (currentView === 'day') {
        selectedDate.setDate(selectedDate.getDate() + 1);
        currentMonth = selectedDate.getMonth();
        currentYear = selectedDate.getFullYear();
    }
    switchView(currentView, selectedDate);
});

monthViewBtn.addEventListener('click', () => {
    currentMonth = selectedDate.getMonth();
    currentYear = selectedDate.getFullYear();
    switchView('month', selectedDate);
});

weekViewBtn.addEventListener('click', () => {
    switchView('week', selectedDate);
});

dayViewBtn.addEventListener('click', () => {
    switchView('day', selectedDate);
});





