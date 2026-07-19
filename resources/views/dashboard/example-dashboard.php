<!-- Beautiful Dashboard Example -->
<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;">Welcome back! </h1>
        <p style="font-size: 15px; color: #6b7280;">Here's what's happening with your resource network today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid">
        <div class="stat-card">
            <h4>Total Population</h4>
            <div class="stat-value">245,890</div>
            <div class="stat-change">â†‘ 3.2% from last month</div>
        </div>
        <div class="stat-card" style="border-left-color: #7c3aed;">
            <h4>Barangays</h4>
            <div class="stat-value" style="color: #7c3aed;">18</div>
            <div class="stat-change">All active</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Health Initiatives</h4>
            <div class="stat-value" style="color: #10b981;">42</div>
            <div class="stat-change">â†‘ 8 new this month</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h4>Active Users</h4>
            <div class="stat-value" style="color: #f59e0b;">156</div>
            <div class="stat-change">â†‘ 12 new users</div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="grid" style="grid-template-columns: 2fr 1fr;">
        <!-- Chart Card -->
        <div class="card">
            <div class="card-header">
                <h3> Population Trends</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="populationChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h3> Quick Stats</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: #1f2937;">Health Coverage</span>
                        <span style="color: #2563eb; font-weight: 700;">82%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar success" style="width: 82%;"></div>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: #1f2937;">Data Completeness</span>
                        <span style="color: #2563eb; font-weight: 700;">76%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 76%;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: #1f2937;">User Engagement</span>
                        <span style="color: #2563eb; font-weight: 700;">64%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar warning" style="width: 64%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="card mt-30">
        <div class="card-header">
            <h3> Recent Activity</h3>
        </div>
        <div class="card-body">
            <div class="list-group">
                <div class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; margin-bottom: 4px;">New health initiative added</div>
                        <div style="font-size: 13px; color: #6b7280;">Vaccination Campaign - Barangay San Vicente</div>
                    </div>
                    <span class="badge badge-primary">Today</span>
                </div>

                <div class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; margin-bottom: 4px;">Data import completed</div>
                        <div style="font-size: 13px; color: #6b7280;">Monthly health metrics uploaded</div>
                    </div>
                    <span class="badge badge-success">Yesterday</span>
                </div>

                <div class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; margin-bottom: 4px;">System maintenance scheduled</div>
                        <div style="font-size: 13px; color: #6b7280;">Planned upgrade for improved performance</div>
                    </div>
                    <span class="badge badge-warning">2 days ago</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Example chart
    if (document.getElementById('populationChart')) {
        const ctx = document.getElementById('populationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Population',
                    data: [230000, 235000, 238000, 240000, 243000, 245890],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: { color: '#6b7280' },
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: '#6b7280' },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>

