<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> Decision Support System</h1>
        <p style="font-size: 15px; color: #6b7280;">Real-time analytics dashboards, reports, and policy simulation tools.</p>
    </div>

    <!-- Analytics Section -->
    <div class="analytics-section">
        <div class="analytics-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; font-size: 22px; font-weight: 700; color: #1f2937;">Real-Time Analytics</h2>
            <button id="refreshAnalytics" class="refresh-btn" style="padding: 8px 16px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                ↻ Refresh Data
            </button>
        </div>

        <!-- 4 Analytics Charts Grid -->
        <div class="analytics-grid">
            <!-- Chart 1: Records by Barangay -->
            <div class="chart-card">
                <h3>Records by Barangay</h3>
                <canvas id="barangayChart"></canvas>
                <div class="chart-info">📊 Top 10 barangays by population records</div>
            </div>

            <!-- Chart 2: Import Trend -->
            <div class="chart-card">
                <h3>Data Import Trend</h3>
                <canvas id="trendChart"></canvas>
                <div class="chart-info">📈 Monthly import activity over time</div>
            </div>

            <!-- Chart 3: Data Quality Metrics -->
            <div class="chart-card">
                <h3>Data Quality Metrics</h3>
                <canvas id="qualityChart"></canvas>
                <div class="chart-info" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">
                    <div style="padding: 8px; background-color: #f0fdf4; border-radius: 4px; font-size: 12px;">
                        <strong>Health Data:</strong> <span id="healthPercent">0</span>%
                    </div>
                    <div style="padding: 8px; background-color: #f0fdf4; border-radius: 4px; font-size: 12px;">
                        <strong>Education Data:</strong> <span id="educationPercent">0</span>%
                    </div>
                </div>
            </div>

            <!-- Chart 4: Population by Age Group -->
            <div class="chart-card">
                <h3>Population by Age Group</h3>
                <canvas id="ageChart"></canvas>
                <div class="chart-info">👥 Age distribution across all records</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; margin-top: 40px;">
        <div class="stat-card">
            <h4>Available Dashboards</h4>
            <div class="stat-value"><?php echo $totalDashboards; ?></div>
            <div class="stat-change">Ready to view</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Published Reports</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo $totalReports; ?></div>
            <div class="stat-change">Available</div>
        </div>
    </div>

    <!-- Available Dashboards -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3> Available Dashboards</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($dashboards as $dashboard): ?>
                    <div style="padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;">
                        <h4 style="margin: 0 0 10px 0; color: #1f2937; font-weight: 700;"><?php echo htmlspecialchars($dashboard['name']); ?></h4>
                        <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 14px;">
                            Reports: <strong style="color: #3b82f6;"><?php echo $dashboard['reports']; ?></strong>
                        </p>
                        <p style="margin: 0; color: #9ca3af; font-size: 12px;">Last updated: <?php echo htmlspecialchars($dashboard['lastUpdated']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="card">
        <div class="card-header">
            <h3> Published Reports</h3>
        </div>
        <div class="card-body">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Report Title</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Generated Date</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Views</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $report): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px; font-weight: 600;">
                                    <a href="#report-<?php echo $report['title']; ?>" style="color: #3b82f6; text-decoration: none;">
                                        <?php echo htmlspecialchars($report['title']); ?>
                                    </a>
                                </td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($report['generated']); ?></td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; background-color: #f3e8ff; color: #6b21a8;">
                                         <?php echo $report['views']; ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; background-color: #d1fae5; color: #065f46; font-size: 12px;">
                                        <?php echo htmlspecialchars($report['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">No reports available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<style>
.page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.grid {
    display: grid;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 5px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.card-body {
    padding: 20px;
}

.card-body table tbody tr:hover {
    background-color: #f9fafb;
}

/* Analytics Charts Styles */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.chart-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    position: relative;
    min-height: 400px;
}

.chart-card h3 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
}

.chart-card canvas {
    max-height: 300px;
    margin-bottom: 10px;
}

.chart-info {
    font-size: 12px;
    color: #6b7280;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.refresh-btn:hover {
    background-color: #2563eb;
    transform: scale(1.05);
    transition: all 0.2s ease;
}

@media (max-width: 1024px) {
    .analytics-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    
    .chart-card {
        min-height: 350px;
    }
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .chart-card {
        min-height: 300px;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 10px;
    }
    
    .chart-card {
        padding: 15px;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Chart instances for dynamic updates
let charts = {
    barangay: null,
    trend: null,
    quality: null,
    age: null
};

// Color schemes
const chartColors = {
    blue: '#3b82f6',
    green: '#10b981',
    red: '#ef4444',
    purple: '#8b5cf6',
    orange: '#f59e0b',
    pink: '#ec4899',
    cyan: '#06b6d4',
    indigo: '#6366f1'
};

// Color palette for charts
const colorPalette = [
    '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
    '#8b5cf6', '#06b6d4', '#ec4899', '#f97316',
    '#6366f1', '#14b8a6', '#d946ef', '#a16207'
];

// Initialize all charts
function initializeCharts() {
    loadBarangayChart();
    loadTrendChart();
    loadQualityChart();
    loadAgeChart();
}

// Chart 1: Records by Barangay
function loadBarangayChart() {
    fetch('/api/analytics/records-by-barangay')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('API Error:', data.message);
                return;
            }
            
            const barangays = data.data.map(item => item.barangay).slice(0, 10);
            const individuals = data.data.map(item => parseInt(item.individuals)).slice(0, 10);
            
            const ctx = document.getElementById('barangayChart').getContext('2d');
            
            if (charts.barangay) charts.barangay.destroy();
            
            charts.barangay = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: barangays,
                    datasets: [{
                        label: 'Total Individuals',
                        data: individuals,
                        backgroundColor: '#3b82f6',
                        borderColor: '#1e40af',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading barangay chart:', error));
}

// Chart 2: Import Trend
function loadTrendChart() {
    fetch('/api/analytics/import-trend')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('API Error:', data.message);
                return;
            }
            
            const months = data.data.map(item => item.month);
            const records = data.data.map(item => parseInt(item.records || 0));
            
            const ctx = document.getElementById('trendChart').getContext('2d');
            
            if (charts.trend) charts.trend.destroy();
            
            charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Records Imported',
                        data: records,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading trend chart:', error));
}

// Chart 3: Data Quality Metrics
function loadQualityChart() {
    fetch('/api/analytics/data-quality')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('API Error:', data.message);
                return;
            }
            
            const quality = data.data;
            
            // Update percentage displays
            document.getElementById('healthPercent').textContent = quality.health_data_complete.toFixed(1);
            document.getElementById('educationPercent').textContent = quality.education_data_complete.toFixed(1);
            
            const ctx = document.getElementById('qualityChart').getContext('2d');
            
            if (charts.quality) charts.quality.destroy();
            
            charts.quality = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Health Data Complete', 'Education Data Complete', 'Other Data'],
                    datasets: [{
                        data: [
                            quality.health_data_complete,
                            quality.education_data_complete,
                            100 - quality.overall_completeness
                        ],
                        backgroundColor: ['#10b981', '#3b82f6', '#e5e7eb'],
                        borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading quality chart:', error));
}

// Chart 4: Population by Age Group
function loadAgeChart() {
    fetch('/api/analytics/population-by-age')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('API Error:', data.message);
                return;
            }
            
            const ageGroups = data.data.map(item => item.age_group);
            const counts = data.data.map(item => parseInt(item.count));
            
            const ctx = document.getElementById('ageChart').getContext('2d');
            
            if (charts.age) charts.age.destroy();
            
            charts.age = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ageGroups,
                    datasets: [{
                        data: counts,
                        backgroundColor: colorPalette.slice(0, ageGroups.length),
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = counts.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loading age chart:', error));
}

// Refresh button functionality
document.getElementById('refreshAnalytics').addEventListener('click', function() {
    this.style.transform = 'rotate(360deg)';
    this.style.transition = 'transform 0.6s ease';
    
    setTimeout(() => {
        initializeCharts();
        this.style.transform = 'rotate(0deg)';
    }, 300);
});

// Auto-refresh every 30 seconds for real-time updates
setInterval(initializeCharts, 30000);

// Initialize charts when page loads
window.addEventListener('DOMContentLoaded', initializeCharts);
</script>