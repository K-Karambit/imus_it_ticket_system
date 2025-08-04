<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Professional Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            /* Light Mode Colors */
            --body-bg: #f8f9fa;
            --text-color: #212529;
            --header-color: #212529;
            --card-bg: #ffffff;
            --card-header-bg: #e9ecef;
            --card-border: #dee2e6;
            --table-header-bg: #e9ecef;
            --table-border: #dee2e6;
            --table-hover-bg: #f3f4f6;
            --link-color: #007bff;
            --kpi-value-color: #007bff;
            --label-color: #6c757d;
        }

        .dark-mode {
            /* Dark Mode Colors */
            --body-bg: #1c2025;
            --text-color: #f8f9fa;
            --header-color: #f8f9fa;
            --card-bg: #2c323a;
            --card-header-bg: #383f47;
            --card-border: #495057;
            --table-header-bg: #383f47;
            --table-border: #495057;
            --table-hover-bg: #383f47;
            --link-color: #1a73e8;
            --kpi-value-color: #1a73e8;
            --label-color: #adb5bd;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-color);
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }

        .dashboard-header {
            margin-bottom: 2rem;
            color: var(--header-color);
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }

        .card-header {
            background-color: var(--card-header-bg);
            border-bottom: 1px solid var(--card-border);
            color: var(--text-color);
        }

        .kpi-card {
            text-align: center;
        }

        .kpi-card .kpi-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--kpi-value-color);
        }

        .kpi-card .kpi-label {
            color: var(--label-color);
            font-size: 0.9rem;
        }

        .kpi-card .kpi-value.warning {
            color: #f5b72e;
        }

        .kpi-card .kpi-value.critical {
            color: #d9534f;
        }

        .table {
            color: var(--text-color);
        }

        .table thead th {
            background-color: var(--table-header-bg);
            border-bottom: 1px solid var(--table-border);
            color: var(--label-color);
        }

        .table td {
            border-top: 1px solid var(--table-border);
        }

        .table tbody tr:hover {
            background-color: var(--table-hover-bg);
        }

        .list-group-item {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            color: var(--text-color);
        }

        .badge-primary {
            background-color: #1a73e8 !important;
        }

        .badge-warning {
            background-color: #f5b72e !important;
        }

        .badge-success {
            background-color: #3f9c6d !important;
        }

        .badge-danger {
            background-color: #d9534f !important;
        }

        /* Toggle switch styling */
        .theme-switch-wrapper {
            position: absolute;
            top: 2rem;
            right: 2rem;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        .theme-switch {
            display: inline-block;
            height: 34px;
            position: relative;
            width: 60px;
        }

        .theme-switch input {
            display: none;
        }

        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
        }

        .slider:before {
            background-color: #fff;
            bottom: 4px;
            content: "";
            height: 26px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 26px;
        }

        input:checked+.slider {
            background-color: #1a73e8;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4">
        <div class="theme-switch-wrapper">
            <label class="theme-switch" for="checkbox">
                <input type="checkbox" id="checkbox" />
                <div class="slider round"></div>
            </label>
        </div>

        <header class="text-center dashboard-header">
            <h1 class="display-4">Support Team Dashboard</h1>
            <p class="lead text-muted">Last updated: August 1, 2025</p>
        </header>

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card kpi-card p-3">
                    <div class="card-body">
                        <p class="kpi-label mb-1">New Tickets</p>
                        <h2 class="kpi-value">15</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card kpi-card p-3">
                    <div class="card-body">
                        <p class="kpi-label mb-1">Total Tickets</p>
                        <h2 class="kpi-value">524</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card kpi-card p-3">
                    <div class="card-body">
                        <p class="kpi-label mb-1">3 Days Unresolved</p>
                        <h2 class="kpi-value warning">8</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card kpi-card p-3">
                    <div class="card-body">
                        <p class="kpi-label mb-1">1 Week Unresolved</p>
                        <h2 class="kpi-value critical">3</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Top Employees (Resolved Tickets)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Resolved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Jane Doe</td>
                                    <td>45</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Michael Scott</td>
                                    <td>38</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Sarah Johnson</td>
                                    <td>32</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>John Smith</td>
                                    <td>25</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Ticket Status</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            In Progress
                            <span class="badge badge-primary badge-pill">120</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            On Hold
                            <span class="badge badge-warning badge-pill">45</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Resolved
                            <span class="badge badge-success badge-pill">300</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cancelled
                            <span class="badge badge-danger badge-pill">59</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Tickets Aging List</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Assigned To</th>
                                    <th>Days Open</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1001</td>
                                    <td>Michael S.</td>
                                    <td class="text-danger font-weight-bold">5</td>
                                </tr>
                                <tr>
                                    <td>#1002</td>
                                    <td>Sarah J.</td>
                                    <td class="text-warning font-weight-bold">3</td>
                                </tr>
                                <tr>
                                    <td>#1003</td>
                                    <td>Michael S.</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>#1004</td>
                                    <td>Michael S.</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daily Ticket Trends</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ticketChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const ctx = document.getElementById('ticketChart');
        let chartInstance;

        const lightModeOptions = {
            backgroundColor: 'rgba(26, 115, 232, 0.2)',
            borderColor: '#1a73e8',
            gridColor: '#dee2e6',
            textColor: '#212529',
            resolvedColor: '#3f9c6d',
        };

        const darkModeOptions = {
            backgroundColor: 'rgba(26, 115, 232, 0.2)',
            borderColor: '#1a73e8',
            gridColor: '#495057',
            textColor: '#f8f9fa',
            resolvedColor: '#3f9c6d',
        };

        function createChart(mode) {
            const chartColors = mode === 'dark' ? darkModeOptions : lightModeOptions;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jul 25', 'Jul 26', 'Jul 27', 'Jul 28', 'Jul 29', 'Jul 30', 'Jul 31', 'Aug 1'],
                    datasets: [{
                        label: 'New Tickets',
                        data: [12, 19, 3, 5, 2, 10, 15, 12],
                        borderColor: chartColors.borderColor,
                        backgroundColor: chartColors.backgroundColor,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: chartColors.borderColor,
                    }, {
                        label: 'Resolved Tickets',
                        data: [8, 15, 5, 8, 4, 7, 10, 15],
                        borderColor: chartColors.resolvedColor,
                        backgroundColor: 'rgba(63, 156, 109, 0.2)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: chartColors.resolvedColor,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: chartColors.textColor
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: chartColors.textColor
                            },
                            gridLines: {
                                color: chartColors.gridColor
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: chartColors.textColor
                            },
                            gridLines: {
                                color: chartColors.gridColor
                            }
                        }
                    }
                }
            });
        }

        // Theme toggle logic
        const toggleSwitch = document.querySelector('#checkbox');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            document.body.classList.toggle(currentTheme === 'dark' ? 'dark-mode' : '', currentTheme === 'dark');
            toggleSwitch.checked = (currentTheme === 'dark');
        } else {
            // Default to light mode if no preference is set
            document.body.classList.remove('dark-mode');
            toggleSwitch.checked = false;
        }

        createChart(currentTheme);

        toggleSwitch.addEventListener('change', function(event) {
            if (event.target.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                createChart('dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
                createChart('light');
            }
        });
    </script>
</body>

</html>