<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <div class="page-header">
        <h1>📊 Analytics Dashboard</h1>
        <p>View analytics and insights from your Excel data uploads</p>
    </div>

    <?php if (empty($analytics)): ?>
        <div class="card">
            <div class="card-body">
                <p>No analytics available yet. <a href="/data-import/upload">Upload an Excel file</a> to get started.</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat-card">
                <h4>Total Households</h4>
                <div class="stat-value"><?php echo number_format(array_sum(array_column($analytics, 'total_households'))); ?></div>
            </div>
            <div class="stat-card">
                <h4>Total Individuals</h4>
                <div class="stat-value"><?php echo number_format(array_sum(array_column($analytics, 'total_individuals'))); ?></div>
            </div>
            <div class="stat-card">
                <h4>Avg Household Size</h4>
                <div class="stat-value"><?php echo round(array_sum(array_column($analytics, 'average_household_size')) / count($analytics), 2); ?></div>
            </div>
            <div class="stat-card">
                <h4>Total Imports</h4>
                <div class="stat-value"><?php echo count($analytics); ?></div>
            </div>
        </div>

        <!-- Analytics List -->
        <div class="card">
            <div class="card-header">
                <h3>📈 Recent Analytics</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Import File</th>
                                <th>Barangay</th>
                                <th>Households</th>
                                <th>Individuals</th>
                                <th>Avg Size</th>
                                <th>Low Income %</th>
                                <th>At-Risk %</th>
                                <th>Generated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($analytics as $analytic): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($analytic['file_name'] ?? 'N/A'); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($analytic['barangay_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo number_format($analytic['total_households']); ?></td>
                                    <td><?php echo number_format($analytic['total_individuals']); ?></td>
                                    <td><?php echo number_format($analytic['average_household_size'], 2); ?></td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <?php echo number_format($analytic['low_income_percentage'], 2); ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">
                                            <?php echo number_format($analytic['health_at_risk_percentage'], 2); ?>%
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('M d, Y', strtotime($analytic['generated_at'])); ?></small>
                                    </td>
                                    <td>
                                        <a href="/analytics/import/<?php echo $analytic['import_id']; ?>" class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                        <a href="/analytics/export?import_id=<?php echo $analytic['import_id']; ?>&format=json" class="btn btn-sm btn-secondary">
                                            Export
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Key Insights -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="card">
                <div class="card-header">
                    <h3>📌 Key Findings</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($analytics as $analytic): ?>
                            <li class="mb-3">
                                <strong><?php echo htmlspecialchars($analytic['file_name'] ?? 'Import'); ?></strong>
                                <p><?php echo htmlspecialchars($analytic['key_findings']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>💡 Recommendations</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($analytics as $analytic): ?>
                            <li class="mb-3">
                                <strong><?php echo htmlspecialchars($analytic['file_name'] ?? 'Import'); ?></strong>
                                <p><?php echo htmlspecialchars($analytic['recommendations']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
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
    margin: 0 0 1rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
}

.badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-warning {
    background-color: #ffc107;
    color: #333;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

.table th {
    background-color: #f5f5f5;
    font-weight: 600;
}

.table tr:hover {
    background-color: #f9f9f9;
}

.table-responsive {
    overflow-x: auto;
}
</style>
