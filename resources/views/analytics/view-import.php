<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <div class="page-header">
        <a href="/analytics" class="btn btn-sm btn-secondary mb-2">← Back to Analytics</a>
        <h1>📊 Detailed Analytics Report</h1>
        <p>File: <strong><?php echo htmlspecialchars($import['file_name']); ?></strong></p>
        <p>Imported on: <?php echo date('F d, Y h:i A', strtotime($import['import_date'])); ?></p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="stat-card">
            <h4>Total Records</h4>
            <div class="stat-value"><?php echo number_format($analytics['total_records']); ?></div>
        </div>
        <div class="stat-card">
            <h4>Total Households</h4>
            <div class="stat-value"><?php echo number_format($analytics['total_households']); ?></div>
        </div>
        <div class="stat-card">
            <h4>Total Individuals</h4>
            <div class="stat-value"><?php echo number_format($analytics['total_individuals']); ?></div>
        </div>
        <div class="stat-card">
            <h4>Avg Household Size</h4>
            <div class="stat-value"><?php echo number_format($analytics['average_household_size'], 2); ?></div>
        </div>
        <div class="stat-card">
            <h4>Average Age</h4>
            <div class="stat-value"><?php echo number_format($analytics['average_age'], 1); ?></div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Gender Distribution -->
        <div class="card">
            <div class="card-header">
                <h3>👥 Gender Distribution</h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart">
                    <?php foreach ($analytics['gender_distribution'] as $gender => $data): ?>
                        <div class="chart-item">
                            <div class="chart-label">
                                <span><?php echo $gender; ?></span>
                                <span class="chart-value"><?php echo $data['percentage']; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $data['percentage']; ?>%; background-color: <?php echo $this->getColorForGender($gender); ?>;"></div>
                            </div>
                            <div class="chart-count"><?php echo number_format($data['count']); ?> people</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Education Distribution -->
        <div class="card">
            <div class="card-header">
                <h3>🎓 Education Distribution</h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart">
                    <?php foreach ($analytics['education_distribution'] as $education => $data): ?>
                        <div class="chart-item">
                            <div class="chart-label">
                                <span><?php echo $education; ?></span>
                                <span class="chart-value"><?php echo $data['percentage']; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $data['percentage']; ?>%; background-color: #667eea;"></div>
                            </div>
                            <div class="chart-count"><?php echo number_format($data['count']); ?> people</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Health Status Distribution -->
        <div class="card">
            <div class="card-header">
                <h3>⚕️ Health Status Distribution</h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart">
                    <?php foreach ($analytics['health_status_distribution'] as $health => $data): ?>
                        <div class="chart-item">
                            <div class="chart-label">
                                <span><?php echo $health; ?></span>
                                <span class="chart-value"><?php echo $data['percentage']; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $data['percentage']; ?>%; background-color: <?php echo $this->getColorForHealth($health); ?>;"></div>
                            </div>
                            <div class="chart-count"><?php echo number_format($data['count']); ?> people</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Socioeconomic Distribution -->
        <div class="card">
            <div class="card-header">
                <h3>💰 Socioeconomic Status Distribution</h3>
            </div>
            <div class="card-body">
                <div class="analytics-chart">
                    <?php foreach ($analytics['socioeconomic_distribution'] as $status => $data): ?>
                        <div class="chart-item">
                            <div class="chart-label">
                                <span><?php echo $status; ?></span>
                                <span class="chart-value"><?php echo $data['percentage']; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $data['percentage']; ?>%; background-color: <?php echo $this->getColorForStatus($status); ?>;"></div>
                            </div>
                            <div class="chart-count"><?php echo number_format($data['count']); ?> households</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header">
                <h3>📌 Key Findings</h3>
            </div>
            <div class="card-body">
                <div class="findings-list">
                    <?php $findings = explode(' | ', $analytics['key_findings']); ?>
                    <?php foreach ($findings as $finding): ?>
                        <div class="finding-item">
                            <span class="finding-icon">✓</span>
                            <span><?php echo htmlspecialchars($finding); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>💡 Recommendations</h3>
            </div>
            <div class="card-body">
                <div class="recommendations-list">
                    <?php $recommendations = explode(' | ', $analytics['recommendations']); ?>
                    <?php foreach ($recommendations as $recommendation): ?>
                        <div class="recommendation-item">
                            <span class="recommendation-icon">→</span>
                            <span><?php echo htmlspecialchars($recommendation); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="card mt-6">
        <div class="card-header">
            <h3>📥 Export Report</h3>
        </div>
        <div class="card-body">
            <p>Download this analytics report in different formats:</p>
            <div class="button-group">
                <a href="/analytics/export?import_id=<?php echo $analytics['import_id']; ?>&format=json" class="btn btn-primary">
                    ↓ Export as JSON
                </a>
                <a href="/analytics/export?import_id=<?php echo $analytics['import_id']; ?>&format=csv" class="btn btn-secondary">
                    ↓ Export as CSV
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-card h4 {
    margin: 0 0 0.5rem;
    font-size: 0.85rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: bold;
}

.analytics-chart {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.chart-item {
    display: grid;
    gap: 0.5rem;
}

.chart-label {
    display: flex;
    justify-content: space-between;
    font-weight: 500;
    font-size: 0.95rem;
}

.chart-value {
    color: #667eea;
    font-weight: bold;
}

.progress-bar {
    height: 24px;
    background-color: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 8px;
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
}

.chart-count {
    font-size: 0.85rem;
    color: #666;
}

.findings-list,
.recommendations-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.finding-item,
.recommendation-item {
    display: flex;
    gap: 1rem;
    padding: 0.75rem;
    background-color: #f5f5f5;
    border-radius: 4px;
    align-items: flex-start;
}

.finding-icon {
    color: #28a745;
    font-weight: bold;
    flex-shrink: 0;
}

.recommendation-icon {
    color: #667eea;
    font-weight: bold;
    flex-shrink: 0;
}

.button-group {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #667eea;
    color: white;
}

.btn-primary:hover {
    background-color: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}
</style>

<?php
// Helper methods (should be in controller, here for view simplicity)
function getColorForGender($gender) {
    $colors = [
        'Male' => '#667eea',
        'Female' => '#f093fb',
        'Other' => '#4facfe'
    ];
    return $colors[$gender] ?? '#667eea';
}

function getColorForHealth($health) {
    $colors = [
        'Healthy' => '#28a745',
        'At-Risk' => '#ffc107',
        'Chronically Ill' => '#dc3545'
    ];
    return $colors[$health] ?? '#667eea';
}

function getColorForStatus($status) {
    $colors = [
        'Low' => '#dc3545',
        'Lower Middle' => '#fd7e14',
        'Middle' => '#ffc107',
        'Upper Middle' => '#28a745',
        'High' => '#20c997'
    ];
    return $colors[$status] ?? '#667eea';
}
?>
