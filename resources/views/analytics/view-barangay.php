<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <div class="page-header">
        <a href="/analytics" class="btn btn-sm btn-secondary mb-2">← Back to Analytics</a>
        <h1>📊 Barangay Analytics</h1>
        <p>Analytics for: <strong><?php echo htmlspecialchars($barangay['name']); ?></strong></p>
    </div>

    <?php if (empty($analytics)): ?>
        <div class="card">
            <div class="card-body">
                <p>No analytics available for this barangay yet.</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="stat-card">
                <h4>Total Imports</h4>
                <div class="stat-value"><?php echo count($analytics); ?></div>
            </div>
            <div class="stat-card">
                <h4>Total Households</h4>
                <div class="stat-value"><?php echo number_format(array_sum(array_column($analytics, 'total_households'))); ?></div>
            </div>
            <div class="stat-card">
                <h4>Total Individuals</h4>
                <div class="stat-value"><?php echo number_format(array_sum(array_column($analytics, 'total_individuals'))); ?></div>
            </div>
        </div>

        <!-- Timeline of Imports -->
        <div class="card mb-6">
            <div class="card-header">
                <h3>📈 Import Timeline</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($analytics as $index => $analytic): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h4><?php echo htmlspecialchars($analytic['file_name']); ?></h4>
                                    <span class="timeline-date"><?php echo date('F d, Y', strtotime($analytic['generated_at'])); ?></span>
                                </div>
                                <div class="timeline-stats">
                                    <span class="timeline-stat">
                                        <strong><?php echo number_format($analytic['total_households']); ?></strong> Households
                                    </span>
                                    <span class="timeline-stat">
                                        <strong><?php echo number_format($analytic['total_individuals']); ?></strong> Individuals
                                    </span>
                                    <span class="timeline-stat">
                                        <strong><?php echo number_format($analytic['low_income_percentage'], 1); ?>%</strong> Low Income
                                    </span>
                                </div>
                                <a href="/analytics/import/<?php echo $analytic['import_id']; ?>" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Comparison of Latest Two Imports -->
        <?php if (count($analytics) >= 2): ?>
            <div class="card">
                <div class="card-header">
                    <h3>📊 Latest Imports Comparison</h3>
                </div>
                <div class="card-body">
                    <div class="comparison-grid">
                        <?php
                        $latest = $analytics[0];
                        $previous = $analytics[1];
                        $householdDiff = $latest['total_households'] - $previous['total_households'];
                        $individualDiff = $latest['total_individuals'] - $previous['total_individuals'];
                        $incomeChange = $latest['low_income_percentage'] - $previous['low_income_percentage'];
                        ?>
                        <div class="comparison-item">
                            <h4>Households</h4>
                            <div class="comparison-values">
                                <span class="value-box"><?php echo number_format($previous['total_households']); ?></span>
                                <span class="arrow">→</span>
                                <span class="value-box"><?php echo number_format($latest['total_households']); ?></span>
                            </div>
                            <p class="comparison-change <?php echo $householdDiff >= 0 ? 'increase' : 'decrease'; ?>">
                                <?php echo $householdDiff >= 0 ? '↑' : '↓'; ?>
                                <?php echo abs($householdDiff); ?> households
                            </p>
                        </div>

                        <div class="comparison-item">
                            <h4>Individuals</h4>
                            <div class="comparison-values">
                                <span class="value-box"><?php echo number_format($previous['total_individuals']); ?></span>
                                <span class="arrow">→</span>
                                <span class="value-box"><?php echo number_format($latest['total_individuals']); ?></span>
                            </div>
                            <p class="comparison-change <?php echo $individualDiff >= 0 ? 'increase' : 'decrease'; ?>">
                                <?php echo $individualDiff >= 0 ? '↑' : '↓'; ?>
                                <?php echo abs($individualDiff); ?> individuals
                            </p>
                        </div>

                        <div class="comparison-item">
                            <h4>Low Income %</h4>
                            <div class="comparison-values">
                                <span class="value-box"><?php echo number_format($previous['low_income_percentage'], 1); ?>%</span>
                                <span class="arrow">→</span>
                                <span class="value-box"><?php echo number_format($latest['low_income_percentage'], 1); ?>%</span>
                            </div>
                            <p class="comparison-change <?php echo $incomeChange >= 0 ? 'increase' : 'decrease'; ?>">
                                <?php echo $incomeChange >= 0 ? '↑' : '↓'; ?>
                                <?php echo abs(number_format($incomeChange, 1)); ?>%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
    margin: 0 0 0.5rem;
    font-size: 0.85rem;
    opacity: 0.9;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-marker {
    width: 16px;
    height: 16px;
    background-color: #667eea;
    border-radius: 50%;
    margin-top: 0.5rem;
    margin-right: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 7px;
    top: 32px;
    width: 2px;
    height: 1.5rem;
    background-color: #e0e0e0;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-content {
    flex: 1;
    background-color: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e0e0e0;
}

.timeline-header h4 {
    margin: 0;
}

.timeline-date {
    color: #666;
    font-size: 0.85rem;
}

.timeline-stats {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.timeline-stat {
    font-size: 0.9rem;
    color: #555;
}

.timeline-stat strong {
    color: #667eea;
    font-weight: 600;
}

.comparison-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.comparison-item {
    text-align: center;
}

.comparison-item h4 {
    color: #333;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.comparison-values {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.value-box {
    background-color: #f5f5f5;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    font-weight: bold;
    color: #667eea;
    min-width: 80px;
}

.arrow {
    color: #999;
    font-weight: bold;
}

.comparison-change {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 500;
}

.comparison-change.increase {
    color: #dc3545;
}

.comparison-change.decrease {
    color: #28a745;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

.btn-primary {
    background-color: #667eea;
    color: white;
}

.btn-primary:hover {
    background-color: #5568d3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}
</style>
