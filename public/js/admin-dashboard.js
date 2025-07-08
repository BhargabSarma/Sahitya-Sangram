// Chart.js (https://www.chartjs.org) required

// Total Sales Line Chart
const ctx = document.getElementById('totalSalesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00'],
        datasets: [
            {
                label: 'Profit',
                data: [30, 25, 33, 28, 35, 69, 95],
                borderColor: '#3f3974',
                backgroundColor: 'rgba(63,57,116,0.07)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Sales',
                data: [20, 30, 25, 30, 32, 60, 70],
                borderColor: '#e94560',
                backgroundColor: 'rgba(233,69,96,0.07)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
    }
});

// Sales Pie Chart
const pieCtx = document.getElementById('salesPieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: ['Apple', 'Samsung', 'Vivo', 'Oppo'],
        datasets: [
            {
                data: [35, 30, 20, 15],
                backgroundColor: ['#3a8dde', '#e94560', '#ffd166', '#4ed7b6']
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } }
    }
});

// Average Sales Bar Chart (demo)
const avgCtx = document.getElementById('averageSalesChart').getContext('2d');
new Chart(avgCtx, {
    type: 'bar',
    data: {
        labels: ['W1', 'W2', 'W3', 'W4'],
        datasets: [{
            label: 'Average Sales',
            data: [60, 70, 50, 90],
            backgroundColor: '#3f3974'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
// Responsive Sidebar Toggle
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const mainContent = document.getElementById('mainContent');
sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('sidebar-collapsed');
});

// Active nav item highlight
document.querySelectorAll('.sidebar-nav .nav-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.sidebar-nav .nav-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});