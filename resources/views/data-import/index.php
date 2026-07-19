<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> Data Import & Management</h1>
        <p style="font-size: 15px; color: #6b7280;">Import Excel files and manage all imported data.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <h4>Total Imports</h4>
            <div class="stat-value"><?php echo $totalImports; ?></div>
            <div class="stat-change">Files uploaded</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Barangays</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo $totalBarangays; ?></div>
            <div class="stat-change">Active</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <h4>Households</h4>
            <div class="stat-value" style="color: #f59e0b;"><?php echo number_format($totalHouseholds); ?></div>
            <div class="stat-change">From imports</div>
        </div>
        <div class="stat-card" style="border-left-color: #8b5cf6;">
            <h4>Individuals</h4>
            <div class="stat-value" style="color: #8b5cf6;"><?php echo number_format($totalIndividuals); ?></div>
            <div class="stat-change">Imported records</div>
        </div>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
        <a href="/api/data-import/template" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px 20px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(59, 130, 246, 0.2)';">
            📥 Download Template
        </a>
        <a href="/data-import/upload" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(16, 185, 129, 0.2)';">
            📤 Upload Data
        </a>
    </div>


    <!-- Import History -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3> Recent Imports</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($imports)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">File Name</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Status</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Records</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($imports as $import): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px;"><?php echo htmlspecialchars($import['file_name'] ?? 'N/A'); ?></td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; background-color: #d1fae5; color: #065f46; font-size: 12px;">
                                        <?php echo htmlspecialchars($import['status'] ?? 'Completed'); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;"><?php echo $import['processed_records'] ?? '0'; ?></td>
                                <td style="padding: 12px; font-size: 13px; color: #6b7280;"><?php echo htmlspecialchars($import['import_date'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #6b7280; padding: 20px;">No imports found. <a href="#data-import" style="color: #3b82f6;">Upload your first file</a></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Barangays Summary -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3> Barangays Summary</h3>
        </div>
        <div class="card-body">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Barangay</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Population</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Area</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Chairman</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($barangays)): ?>
                        <?php foreach ($barangays as $barangay): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px; font-weight: 600;"><?php echo htmlspecialchars($barangay['name']); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($barangay['population']); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($barangay['area'], 2); ?> km2</td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($barangay['chairman']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #6b7280;">No barangays found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Households & Individuals Summary -->
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div class="card">
            <div class="card-header">
                <h3>  Households Overview</h3>
            </div>
            <div class="card-body">
                <p style="margin: 0 0 20px 0; color: #6b7280;">Total Households: <strong style="font-size: 20px; color: #3b82f6;"><?php echo number_format($totalHouseholds); ?></strong></p>
                <p style="margin: 0; color: #9ca3af; font-size: 13px;">Displaying latest 20 records</p>
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; font-size: 12px;">Head</th>
                            <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; font-size: 12px;">Members</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        foreach ($households as $household): 
                            if ($count >= 20) break;
                            $count++;
                        ?>
                            <tr style="border-bottom: 1px solid #e5e7eb; font-size: 12px;">
                                <td style="padding: 8px;"><?php echo htmlspecialchars(substr($household['household_head'], 0, 20)); ?></td>
                                <td style="padding: 8px;"><?php echo $household['member_count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3> Individuals Overview</h3>
            </div>
            <div class="card-body">
                <p style="margin: 0 0 20px 0; color: #6b7280;">Total Individuals: <strong style="font-size: 20px; color: #f59e0b;"><?php echo number_format($totalIndividuals); ?></strong></p>
                <p style="margin: 0; color: #9ca3af; font-size: 13px;">Displaying latest 20 records</p>
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; font-size: 12px;">Name</th>
                            <th style="padding: 8px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; font-size: 12px;">Age</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        foreach ($individuals as $individual): 
                            if ($count >= 20) break;
                            $count++;
                        ?>
                            <tr style="border-bottom: 1px solid #e5e7eb; font-size: 12px;">
                                <td style="padding: 8px;"><?php echo htmlspecialchars(substr($individual['first_name'] . ' ' . $individual['last_name'], 0, 20)); ?></td>
                                <td style="padding: 8px;"><?php echo $individual['age']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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


