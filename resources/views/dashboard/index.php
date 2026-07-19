<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;">Welcome back! </h1>
        <p style="font-size: 15px; color: #6b7280;">Here's the complete overview of your city data.</p>
    </div>

    <!-- Main Modules Grid -->
    <style>
        .module-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .module-card {
            padding: 28px;
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.8));
            border-radius: 14px;
            color: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        
        .module-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            border-radius: 14px;
            opacity: 1;
            transition: opacity 0.3s ease;
            z-index: 0;
        }
        
        .module-card > * {
            position: relative;
            z-index: 1;
        }
        
        .module-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.8);
        }
        
        .module-card:hover::before {
            opacity: 0.15;
        }
        
        .module-card img {
            width: 70px;
            height: 70px;
            margin-bottom: 16px;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }
        
        .module-card:hover img {
            transform: scale(1.1) rotate(5deg);
        }
        
        .module-card h3 {
            margin: 0 0 8px 0;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        
        .module-card p {
            margin: 0;
            font-size: 13px;
            opacity: 0.85;
        }
        
        /* Color variants */
        .module-card.records {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .module-card.records::before { background: radial-gradient(circle, rgba(255,255,255,0.4), transparent); }
        
        .module-card.health {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .module-card.health::before { background: radial-gradient(circle, rgba(255,255,255,0.4), transparent); }
        
        .module-card.knowledge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .module-card.knowledge::before { background: radial-gradient(circle, rgba(255,255,255,0.3), transparent); }
        
        .module-card.analytics {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        .module-card.analytics::before { background: radial-gradient(circle, rgba(255,255,255,0.3), transparent); }
        
        .module-card.decision {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        }
        .module-card.decision::before { background: radial-gradient(circle, rgba(255,255,255,0.3), transparent); }
        
        @media (max-width: 1400px) {
            .module-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        @media (max-width: 1024px) {
            .module-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 768px) {
            .module-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            .module-card {
                padding: 24px;
            }
            .module-card img {
                width: 60px;
                height: 60px;
            }
        }
        @media (max-width: 480px) {
            .module-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .module-card {
                padding: 20px;
            }
        }
    </style>
    <div class="module-grid">
        <!-- Records Module -->
        <div class="module-card records" onclick="window.location.href='/data-management';">
            <img src="/images/icon-records.svg" alt="Records">
            <h3>Records</h3>
            <p>Manage barangay, household, and individual records</p>
        </div>

        <!-- Health Metrics Module -->
        <div class="module-card health" onclick="window.location.href='/barangay-records';">
            <img src="/images/icon-health.svg" alt="Health">
            <h3>Health Metrics</h3>
            <p>Monitor health indicators and wellness data</p>
        </div>

        <!-- Knowledge Base Module -->
        <div class="module-card knowledge" onclick="window.location.href='/knowledge-management';">
            <img src="/images/icon-knowledge.svg" alt="Knowledge">
            <h3>Knowledge Base</h3>
            <p>Access documents and best practices</p>
        </div>

        <!-- ML Analytics Module -->
        <div class="module-card analytics" onclick="window.location.href='/ml-analytics';">
            <img src="/images/icon-analytics.svg" alt="Analytics">
            <h3>ML Analytics</h3>
            <p>Advanced analytics and predictions</p>
        </div>

        <!-- Decision Support Module -->
        <div class="module-card decision" onclick="window.location.href='/decision-support';">
            <img src="/images/icon-decision.svg" alt="Decision Support">
            <h3>Decision Support</h3>
            <p>Policy simulation and dashboards</p>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <h4>Total Population</h4>
            <div class="stat-value"><?php echo number_format($totalPopulation); ?></div>
            <div class="stat-change">Individuals recorded</div>
        </div>
        <div class="stat-card" style="border-left-color: #7c3aed;">
            <h4>Barangays</h4>
            <div class="stat-value" style="color: #7c3aed;"><?php echo $totalBarangays; ?></div>
            <div class="stat-change">All active</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Households</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo number_format($totalHouseholds); ?></div>
            <div class="stat-change">Registered families</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h4>Active Users</h4>
            <div class="stat-value" style="color: #f59e0b;"><?php echo $totalUsers; ?></div>
            <div class="stat-change">System users</div>
        </div>
    </div>

    <!-- 4-Column Data Tables Grid -->
    <style>
        .data-tables-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .data-tables-grid .card {
            margin-bottom: 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .data-tables-grid .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .data-tables-grid .card-header {
            cursor: pointer;
        }
        .data-tables-grid .card-body {
            overflow-y: auto;
            max-height: 500px;
            padding: 15px;
        }
        .data-tables-grid table th,
        .data-tables-grid table td {
            padding: 10px 8px !important;
            font-size: 13px !important;
        }
        .data-tables-grid table th {
            font-weight: 600 !important;
        }
        @media (max-width: 1400px) {
            .data-tables-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .data-tables-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Modal Styles */
        .table-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .table-modal.active {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 90vw;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            color: #1f2937;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.2s ease;
        }

        .modal-close:hover {
            color: #1f2937;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .modal-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            background-color: #f9fafb;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
        }

        .modal-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }

        .modal-table tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>

    <div class="data-tables-grid">
        <!-- Barangays Overview -->
        <div class="card" data-table="barangays">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; cursor: pointer;">📍 All Barangays</h3>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Barangay Name</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Population</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Area</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Chairman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($barangays)): ?>
                            <?php foreach (array_slice($barangays, 0, 4) as $barangay): ?>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 10px 8px;"><?php echo htmlspecialchars(substr($barangay['name'], 0, 12)); ?></td>
                                    <td style="padding: 10px 8px;"><?php echo number_format($barangay['population']); ?></td>
                                    <td style="padding: 10px 8px;"><?php echo number_format($barangay['area'], 1); ?></td>
                                    <td style="padding: 10px 8px;"><?php echo htmlspecialchars(substr($barangay['chairman'], 0, 10)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="padding: 20px; text-align: center; color: #6b7280; font-size: 12px;">No barangays</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Health Metrics Overview -->
        <?php if (!empty($healthMetrics)): ?>
        <div class="card" data-table="health-metrics">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; cursor: pointer;">❤️ Health Metrics</h3>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Barangay</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Immun. %</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Maternal</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Infant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($healthMetrics, 0, 4) as $metric): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 8px; font-weight: 600; font-size: 12px;"><?php echo htmlspecialchars(substr($metric['name'], 0, 10)); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo number_format($metric['immunization_coverage'], 1); ?>%</td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo number_format($metric['maternal_mortality_rate'], 1); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo number_format($metric['infant_mortality_rate'], 1); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Socioeconomic Distribution -->
        <?php if (!empty($socioeconomicData)): ?>
        <div class="card" data-table="socioeconomic">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; cursor: pointer;">📊 Socioeconomic</h3>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Status</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Count</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalHouseholdsCount = array_sum(array_column($socioeconomicData, 'count'));
                        foreach (array_slice($socioeconomicData, 0, 4) as $data): 
                            $percentage = ($totalHouseholdsCount > 0) ? ($data['count'] / $totalHouseholdsCount) * 100 : 0;
                        ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 8px; font-weight: 600; font-size: 12px;"><?php echo htmlspecialchars(substr($data['socioeconomic_status'], 0, 10)); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo number_format($data['count']); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo number_format($percentage, 1); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Users Overview -->
        <?php if (!empty($users)): ?>
        <div class="card" data-table="system-users">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; cursor: pointer;">👥 System Users</h3>
            </div>
            <div class="card-body">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Username</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Email</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Role</th>
                            <th style="padding: 10px 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($users, 0, 4) as $user): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo htmlspecialchars(substr($user['username'] ?? 'N/A', 0, 10)); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo htmlspecialchars(substr($user['email'] ?? 'N/A', 0, 12)); ?></td>
                                <td style="padding: 10px 8px; font-size: 12px;"><?php echo htmlspecialchars($user['role'] ?? 'User'); ?></td>
                                <td style="padding: 10px 8px; font-size: 11px;"><span style="padding: 2px 6px; border-radius: 3px; background-color: #d1fae5; color: #065f46;">Active</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Individuals/Population Data with Pagination & Slide Animation -->
    <?php if (!empty($individuals)): ?>
    <div class="card" style="margin-bottom: 30px;">
        <style>
            .population-pagination {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 15px;
                padding: 15px;
                background-color: #f9fafb;
                border-radius: 8px;
                gap: 10px;
            }
            
            .pagination-info {
                font-size: 13px;
                color: #6b7280;
                font-weight: 500;
                flex: 1;
                text-align: center;
            }
            
            .pagination-controls {
                display: flex;
                gap: 8px;
                align-items: center;
            }
            
            .pagination-btn {
                padding: 8px 16px;
                background-color: white;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                cursor: pointer;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                transition: all 0.3s ease;
            }
            
            .pagination-btn:hover:not(:disabled) {
                background-color: #f3f4f6;
                border-color: #9ca3af;
                transform: translateY(-2px);
            }
            
            .pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            
            .pagination-btn.active {
                background-color: #3b82f6;
                color: white;
                border-color: #3b82f6;
            }
            
            .table-wrapper {
                position: relative;
                overflow: hidden;
                border-radius: 0 0 8px 8px;
            }
            
            .table-slide {
                overflow-x: auto;
                animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .table-slide.slide-out-left {
                animation: slideOutLeft 0.3s ease forwards;
            }
            
            @keyframes slideOutLeft {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }
                to {
                    opacity: 0;
                    transform: translateX(-30px);
                }
            }
            
            .page-dots {
                display: flex;
                gap: 6px;
                justify-content: center;
            }
            
            .page-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #d1d5db;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .page-dot.active {
                background-color: #3b82f6;
                transform: scale(1.3);
            }
            
            .page-dot:hover {
                background-color: #9ca3af;
            }
        </style>
        
        <div class="card-header">
            <h3 style="display: flex; align-items: center; gap: 8px; margin: 0;">
                👤 Population Details
                <span style="font-size: 13px; color: #9ca3af; font-weight: 400;">(All Records)</span>
            </h3>
        </div>
        
        <div class="table-wrapper">
            <div id="population-table-container" class="table-slide" style="overflow-x: auto;">
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
                    <tbody id="population-tbody">
                        <?php 
                        $displayCount = 0;
                        foreach ($individuals as $individual): 
                            if ($displayCount >= 50) break;
                            $displayCount++;
                        ?>
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f0f9ff';" onmouseout="this.style.backgroundColor='transparent';">
                                <td style="padding: 12px;"><?php echo htmlspecialchars($individual['first_name'] . ' ' . $individual['last_name']); ?></td>
                                <td style="padding: 12px;"><?php echo $individual['age']; ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($individual['gender']); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($individual['health_status']); ?></td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($individual['education_level']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-body" style="padding: 0; border-top: 1px solid #e5e7eb;">
            <div class="population-pagination">
                <button class="pagination-btn" onclick="populationPrevPage()" id="prev-btn">← Previous</button>
                <div class="pagination-info">
                    <span id="current-page-info">Page 1 of <span id="total-pages">1</span> | Showing <span id="showing-count">10</span> of <span id="total-records">50</span></span>
                </div>
                <button class="pagination-btn" onclick="populationNextPage()" id="next-btn">Next →</button>
            </div>
            <div class="page-dots" id="page-dots" style="padding: 10px 15px;"></div>
        </div>
    </div>
    
    <script>
        const ITEMS_PER_PAGE = 10;
        let populationCurrentPage = 1;
        let populationAllData = [];
        
        // Get all individual data from the table
        function initPopulationPagination() {
            const tbody = document.getElementById('population-tbody');
            const rows = tbody.querySelectorAll('tr');
            populationAllData = Array.from(rows).map(row => {
                const cells = row.querySelectorAll('td');
                return {
                    name: cells[0]?.innerText || '',
                    age: cells[1]?.innerText || '',
                    gender: cells[2]?.innerText || '',
                    health: cells[3]?.innerText || '',
                    education: cells[4]?.innerText || ''
                };
            });
            
            const totalPages = Math.ceil(populationAllData.length / ITEMS_PER_PAGE);
            document.getElementById('total-pages').innerText = totalPages;
            document.getElementById('total-records').innerText = populationAllData.length;
            
            renderPopulationPage(1);
            renderPageDots(totalPages);
        }
        
        function renderPopulationPage(page) {
            const start = (page - 1) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            const pageData = populationAllData.slice(start, end);
            
            const tbody = document.getElementById('population-tbody');
            tbody.innerHTML = pageData.map(item => `
                <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s ease;" 
                    onmouseover="this.style.backgroundColor='#f0f9ff';" 
                    onmouseout="this.style.backgroundColor='transparent';">
                    <td style="padding: 12px;">${item.name}</td>
                    <td style="padding: 12px;">${item.age}</td>
                    <td style="padding: 12px;">${item.gender}</td>
                    <td style="padding: 12px;">${item.health}</td>
                    <td style="padding: 12px;">${item.education}</td>
                </tr>
            `).join('');
            
            document.getElementById('current-page-info').innerText = 
                `Page ${page} of ${Math.ceil(populationAllData.length / ITEMS_PER_PAGE)} | Showing ${pageData.length} of ${populationAllData.length}`;
            
            document.getElementById('prev-btn').disabled = page === 1;
            document.getElementById('next-btn').disabled = page === Math.ceil(populationAllData.length / ITEMS_PER_PAGE);
            
            updatePageDots(page);
            populationCurrentPage = page;
        }
        
        function populationNextPage() {
            const totalPages = Math.ceil(populationAllData.length / ITEMS_PER_PAGE);
            if (populationCurrentPage < totalPages) {
                renderPopulationPage(populationCurrentPage + 1);
            }
        }
        
        function populationPrevPage() {
            if (populationCurrentPage > 1) {
                renderPopulationPage(populationCurrentPage - 1);
            }
        }
        
        function renderPageDots(totalPages) {
            const container = document.getElementById('page-dots');
            container.innerHTML = Array.from({length: totalPages}, (_, i) => i + 1)
                .map(page => `<div class="page-dot ${page === 1 ? 'active' : ''}" onclick="renderPopulationPage(${page})"></div>`)
                .join('');
        }
        
        function updatePageDots(currentPage) {
            document.querySelectorAll('.page-dot').forEach((dot, idx) => {
                dot.classList.toggle('active', idx + 1 === currentPage);
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initPopulationPagination();
        });
    </script>
    <?php endif; ?>

    <!-- Audit Log -->
    <?php if (!empty($auditLogs)): ?>
    <div class="card">
        <div class="card-header">
            <h3> Recent Activity Log (Latest 20)</h3>
        </div>
        <div class="card-body">
            <div style="max-height: 400px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="width: 12%; padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; border-right: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 10; background-color: #f9fafb;">User</th>
                            <th style="width: 15%; padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; border-right: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 10; background-color: #f9fafb;">Action</th>
                            <th style="width: 50%; padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; border-right: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 10; background-color: #f9fafb;">Details</th>
                            <th style="width: 23%; padding: 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #e5e7eb; position: sticky; top: 0; z-index: 10; background-color: #f9fafb;">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $displayCount = 0;
                        foreach ($auditLogs as $log): 
                            if ($displayCount >= 20) break;
                            $displayCount++;
                        ?>
                            <tr style="border-bottom: 1px solid #e5e7eb; background-color: <?php echo $displayCount % 2 === 0 ? '#ffffff' : '#f9fafb'; ?>; transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#eef2ff'; this.style.boxShadow='inset 0 0 0 1px #3b82f6';" onmouseout="this.style.backgroundColor='<?php echo $displayCount % 2 === 0 ? '#ffffff' : '#f9fafb'; ?>'; this.style.boxShadow='none';">
                                <td style="width: 12%; padding: 12px; word-break: break-word; border-right: 1px solid #e5e7eb;"><?php echo htmlspecialchars($log['name'] ?? ($log['email'] ?? 'System')); ?></td>
                                <td style="width: 15%; padding: 12px; word-break: break-word; border-right: 1px solid #e5e7eb;"><?php echo htmlspecialchars($log['action'] ?? 'N/A'); ?></td>
                                <td style="width: 50%; padding: 12px; word-break: break-word; border-right: 1px solid #e5e7eb;"><?php echo htmlspecialchars(substr($log['details'] ?? '', 0, 80)); ?></td>
                                <td style="width: 23%; padding: 12px; font-size: 13px; color: #6b7280; word-break: break-word;"><?php echo htmlspecialchars($log['timestamp'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- MODALS FOR EXPANDED VIEWS -->

<!-- Barangays Modal -->
<div id="modal-barangays" class="table-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>📍 All Barangays</h2>
            <button class="modal-close" onclick="closeModal('modal-barangays')">&times;</button>
        </div>
        <table class="modal-table">
            <thead>
                <tr>
                    <th>Barangay Name</th>
                    <th>Population</th>
                    <th>Area (sq km)</th>
                    <th>Chairman</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($barangays)): ?>
                    <?php foreach ($barangays as $barangay): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($barangay['name']); ?></td>
                            <td><?php echo number_format($barangay['population']); ?></td>
                            <td><?php echo number_format($barangay['area'], 2); ?></td>
                            <td><?php echo htmlspecialchars($barangay['chairman']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">No barangays available</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Health Metrics Modal -->
<div id="modal-health-metrics" class="table-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>❤️ Health Metrics</h2>
            <button class="modal-close" onclick="closeModal('modal-health-metrics')">&times;</button>
        </div>
        <table class="modal-table">
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Immunization Coverage (%)</th>
                    <th>Maternal Mortality Rate</th>
                    <th>Infant Mortality Rate</th>
                    <th>Under-5 Mortality Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($healthMetrics)): ?>
                    <?php foreach ($healthMetrics as $metric): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($metric['name']); ?></td>
                            <td><?php echo number_format($metric['immunization_coverage'], 2); ?>%</td>
                            <td><?php echo number_format($metric['maternal_mortality_rate'], 2); ?></td>
                            <td><?php echo number_format($metric['infant_mortality_rate'], 2); ?></td>
                            <td><?php echo number_format($metric['under5_mortality_rate'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">No health metrics available</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Socioeconomic Modal -->
<div id="modal-socioeconomic" class="table-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>📊 Socioeconomic Status Distribution</h2>
            <button class="modal-close" onclick="closeModal('modal-socioeconomic')">&times;</button>
        </div>
        <table class="modal-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($socioeconomicData)): ?>
                    <?php 
                    $totalSocioCount = array_sum(array_column($socioeconomicData, 'count'));
                    foreach ($socioeconomicData as $data): 
                        $percentage = ($totalSocioCount > 0) ? ($data['count'] / $totalSocioCount) * 100 : 0;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data['socioeconomic_status']); ?></td>
                            <td><?php echo number_format($data['count']); ?></td>
                            <td><?php echo number_format($percentage, 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center; padding: 20px; color: #6b7280;">No socioeconomic data available</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- System Users Modal -->
<div id="modal-system-users" class="table-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>👥 System Users</h2>
            <button class="modal-close" onclick="closeModal('modal-system-users')">&times;</button>
        </div>
        <table class="modal-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                            <td><span style="padding: 4px 8px; border-radius: 4px; background-color: #dbeafe; color: #1e40af;"><?php echo htmlspecialchars($user['role'] ?? 'User'); ?></span></td>
                            <td><span style="padding: 4px 8px; border-radius: 4px; background-color: #d1fae5; color: #065f46;">Active</span></td>
                            <td><?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">No users available</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Modal Management
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside the content
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('table-modal')) {
        event.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const activeModals = document.querySelectorAll('.table-modal.active');
        activeModals.forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = 'auto';
    }
});

// Attach click handlers to table cards
document.addEventListener('DOMContentLoaded', function() {
    const tableCards = document.querySelectorAll('[data-table]');
    
    tableCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const tableType = this.getAttribute('data-table');
            const modalId = 'modal-' + tableType;
            openModal(modalId);
        });
    });
});
</script>

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

.stat-card h4 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: #6b7280;
    font-weight: 600;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 5px;
}

.stat-change {
    font-size: 12px;
    color: #9ca3af;
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

.card-body table thead {
    font-size: 13px;
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

<?php require_once base_path('resources/views/layouts/footer.php'); ?>

