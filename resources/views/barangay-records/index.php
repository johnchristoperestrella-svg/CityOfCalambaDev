<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> Health Metrics</h1>
        <p style="font-size: 15px; color: #6b7280;">Monitor health indicators and metrics across all barangays.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <h4>Total Barangays</h4>
            <div class="stat-value"><?php echo $totalBarangays; ?></div>
            <div class="stat-change">With health data</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Households</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo number_format(count($households)); ?></div>
            <div class="stat-change">Monitored</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h4>Population</h4>
            <div class="stat-value" style="color: #f59e0b;"><?php echo number_format(count($individuals)); ?></div>
            <div class="stat-change">Under observation</div>
        </div>
    </div>

    <!-- Barangays Grid -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>📍 All Barangays</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php if (!empty($barangays)): ?>
                    <?php foreach ($barangays as $barangay): ?>
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb; transition: all 0.3s ease;"
                             onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                             onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                            <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #1f2937;"><?php echo htmlspecialchars($barangay['name']); ?></h4>
                            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                                <div><strong>Population:</strong> <?php echo number_format($barangay['population']); ?></div>
                                <div><strong>Area:</strong> <?php echo number_format($barangay['area'], 2); ?> sq km</div>
                                <div><strong>Chairman:</strong> <?php echo htmlspecialchars($barangay['chairman']); ?></div>
                                <div><strong>Contact:</strong> <?php echo htmlspecialchars($barangay['contact']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #6b7280;">No barangays found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Population by Barangay Bar Chart -->
        <div class="card">
            <div class="card-header">
                <h3>📊 Population by Barangay</h3>
            </div>
            <div class="card-body">
                <canvas id="populationChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- Health Metrics Distribution Pie Chart -->
        <div class="card">
            <div class="card-header">
                <h3>🥧 Health Coverage Distribution</h3>
            </div>
            <div class="card-body">
                <canvas id="healthCoverageChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Immunization Coverage Chart -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>💉 Immunization Coverage by Barangay</h3>
        </div>
        <div class="card-body">
            <canvas id="immunizationChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Health Metrics by Barangay -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3> Health Metrics by Barangay</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Barangay</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Immunization</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Maternal Mortality</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Infant Mortality</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Under-5 Mortality</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($healthMetrics)): ?>
                        <?php foreach ($healthMetrics as $metric): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px; font-weight: 600;"><?php echo htmlspecialchars($metric['name']); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($metric['immunization_coverage'], 2); ?>%</td>
                                <td style="padding: 12px;"><?php echo number_format($metric['maternal_mortality_rate'], 2); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($metric['infant_mortality_rate'], 2); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($metric['under5_mortality_rate'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="padding: 20px; text-align: center; color: #6b7280;">No health metrics available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Households Grid -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>🏠 Households (Latest 50)</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <?php 
                $count = 0;
                if (!empty($households)): 
                    foreach ($households as $household): 
                        if ($count >= 16) break;
                        $count++;
                ?>
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; background-color: #f9fafb; transition: all 0.3s ease;"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                         onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                        <h4 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 600; color: #1f2937;"><?php echo htmlspecialchars($household['household_head']); ?></h4>
                        <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">
                            <div><strong>Address:</strong> <?php echo htmlspecialchars($household['address']); ?></div>
                            <div><strong>Members:</strong> <?php echo $household['member_count']; ?></div>
                            <div style="margin-top: 8px;">
                                <span style="padding: 4px 8px; border-radius: 4px; background-color: #e0e7ff; color: #3730a3; font-size: 12px;">
                                    <?php echo htmlspecialchars($household['socioeconomic_status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <div style="grid-column: 1 / -1; padding: 20px; text-align: center; color: #6b7280;">No households found</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Population Details -->
    <div class="card">
        <div class="card-header">
            <h3> Population Details (Latest 50)</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Age</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Gender</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Health Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Education Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 0;
                    if (!empty($individuals)): 
                        foreach ($individuals as $individual): 
                            if ($count >= 50) break;
                            $count++;
                    ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($individual['first_name'] . ' ' . $individual['last_name']); ?></td>
                            <td style="padding: 12px;"><?php echo $individual['age']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($individual['gender']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($individual['health_status']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($individual['education_level']); ?></td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr><td colspan="5" style="padding: 20px; text-align: center; color: #6b7280;">No individuals found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from PHP - use the actual barangays and individuals data
    const barangaysData = <?php echo json_encode($barangays); ?>;
    const individualsData = <?php echo json_encode($individuals); ?>;
    const healthMetricsData = <?php echo json_encode($healthMetrics); ?>;
    
    // Calculate population by barangay from individuals data
    const barangayPopulation = {};
    
    // Initialize all barangays with 0
    barangaysData.forEach(barangay => {
        barangayPopulation[barangay['name']] = 0;
    });
    
    // Count individuals per barangay
    individualsData.forEach(individual => {
        // Find the barangay name from barangay_id
        const barangayId = individual['barangay_id'];
        const barangay = barangaysData.find(b => b['id'] == barangayId);
        
        if (barangay) {
            barangayPopulation[barangay['name']] = (barangayPopulation[barangay['name']] || 0) + 1;
        }
    });
    
    // Chart 1: Population by Barangay (Bar Chart)
    const populationCtx = document.getElementById('populationChart');
    if (populationCtx && Object.keys(barangayPopulation).length > 0) {
        new Chart(populationCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(barangayPopulation),
                datasets: [{
                    label: 'Population',
                    data: Object.values(barangayPopulation),
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#06b6d4'
                    ],
                    borderColor: [
                        '#2563eb',
                        '#059669',
                        '#d97706',
                        '#dc2626',
                        '#7c3aed',
                        '#0891b2'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Chart 2: Health Coverage Distribution (Pie Chart)
    // Calculate health status distribution from individuals data
    const healthStatusCounts = {
        'Healthy': 0,
        'At Risk': 0,
        'Unhealthy': 0,
        'Unknown': 0
    };
    
    individualsData.forEach(individual => {
        const status = individual['health_status'] || 'Unknown';
        if (status === 'Healthy') healthStatusCounts['Healthy']++;
        else if (status === 'At Risk') healthStatusCounts['At Risk']++;
        else if (status === 'Unhealthy') healthStatusCounts['Unhealthy']++;
        else healthStatusCounts['Unknown']++;
    });
    
    // Calculate coverage percentages
    const totalIndividuals = individualsData.length;
    const immunizationCoverage = totalIndividuals > 0 ? (healthStatusCounts['Healthy'] / totalIndividuals * 100) : 0;
    const atRiskPercentage = totalIndividuals > 0 ? (healthStatusCounts['At Risk'] / totalIndividuals * 100) : 0;
    const unhealthyPercentage = totalIndividuals > 0 ? (healthStatusCounts['Unhealthy'] / totalIndividuals * 100) : 0;
    
    const healthCoverageCtx = document.getElementById('healthCoverageChart');
    if (healthCoverageCtx) {
        new Chart(healthCoverageCtx, {
            type: 'doughnut',
            data: {
                labels: ['Healthy', 'At Risk', 'Unhealthy'],
                datasets: [{
                    data: [
                        Math.round(immunizationCoverage * 10) / 10,
                        Math.round(atRiskPercentage * 10) / 10,
                        Math.round(unhealthyPercentage * 10) / 10
                    ],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderColor: [
                        '#059669',
                        '#d97706',
                        '#dc2626'
                    ],
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
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Chart 3: Immunization Coverage by Barangay (Line Chart)
    // Calculate immunization coverage per barangay based on healthy individuals
    const immunizationByBarangay = {};
    
    barangaysData.forEach(barangay => {
        immunizationByBarangay[barangay['name']] = {
            healthy: 0,
            total: 0
        };
    });
    
    individualsData.forEach(individual => {
        const barangayId = individual['barangay_id'];
        const barangay = barangaysData.find(b => b['id'] == barangayId);
        
        if (barangay) {
            immunizationByBarangay[barangay['name']].total++;
            if (individual['health_status'] === 'Healthy') {
                immunizationByBarangay[barangay['name']].healthy++;
            }
        }
    });
    
    // Calculate percentages
    const barangayNames = Object.keys(immunizationByBarangay);
    const immunizationCoveragePercentages = barangayNames.map(name => {
        const data = immunizationByBarangay[name];
        return data.total > 0 ? Math.round((data.healthy / data.total * 100) * 10) / 10 : 0;
    });
    
    const immunizationCtx = document.getElementById('immunizationChart');
    if (immunizationCtx) {
        new Chart(immunizationCtx, {
            type: 'line',
            data: {
                labels: barangayNames,
                datasets: [{
                    label: 'Immunization Coverage (%)',
                    data: immunizationCoveragePercentages,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
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
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>



