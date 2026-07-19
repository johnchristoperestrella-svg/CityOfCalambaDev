<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>🔒 Security & Governance</h1>
            <p>System security, user management, and audit logs</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <div class="stat-icon">👥</div>
            <h4>Total Users</h4>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-subtitle">Active accounts</div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">📝</div>
            <h4>Audit Logs</h4>
            <div class="stat-value"><?php echo $totalLogs; ?></div>
            <div class="stat-subtitle">Recent activities</div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-icon">✅</div>
            <h4>System Status</h4>
            <div class="stat-value">Secure</div>
            <div class="stat-subtitle">All systems normal</div>
        </div>
    </div>

    <!-- System Users Section -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3>🔐 System Users</h3>
                <p class="card-subtitle">Manage system user accounts and permissions</p>
            </div>
            <button class="btn btn-primary" id="add-user-btn">+ Add User</button>
        </div>

        <div class="card-toolbar">
            <input type="text" id="user-search" class="search-input" placeholder="🔍 Search users...">
            <select id="role-filter" class="filter-select">
                <option value="">All Roles</option>
                <option value="City Administrator">City Administrator</option>
                <option value="POPDEV Manager">POPDEV Manager</option>
                <option value="Barangay Data Encoder">Barangay Data Encoder</option>
                <option value="Analyst">Analyst</option>
                <option value="Viewer">Viewer</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="users-table-body">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="user-row" data-user-id="<?php echo $user['id']; ?>" data-role="<?php echo htmlspecialchars($user['role'] ?? 'User'); ?>">
                                <td data-label="User">
                                    <div class="user-info">
                                        <div class="user-avatar"><?php echo strtoupper(substr($user['name'][0] ?? 'U', 0, 1)); ?></div>
                                        <strong><?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?></strong>
                                    </div>
                                </td>
                                <td data-label="Email"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                <td data-label="Role">
                                    <span class="role-badge role-<?php echo strtolower(str_replace(' ', '-', $user['role'] ?? 'User')); ?>">
                                        <?php echo htmlspecialchars($user['role'] ?? 'User'); ?>
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge <?php echo ($user['status'] ?? 'inactive') === 'active' ? 'active' : 'inactive'; ?>">
                                        <?php echo htmlspecialchars($user['status'] ?? 'inactive'); ?>
                                    </span>
                                </td>
                                <td data-label="Created" class="text-muted"><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <button class="btn-icon edit-btn" title="Edit user" data-user-id="<?php echo $user['id']; ?>" data-user='<?php echo htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8'); ?>'>✏️</button>
                                        <button class="btn-icon password-btn" title="Reset password" data-user-id="<?php echo $user['id']; ?>">🔑</button>
                                        <?php if ($user['id'] !== auth_id()): ?>
                                            <button class="btn-icon delete-btn" title="Delete user" data-user-id="<?php echo $user['id']; ?>" data-user-name='<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>'>🗑️</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="no-data">No users found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Audit Log Section -->
    <div class="card audit-card">
        <div class="card-header-flex">
            <div>
                <h3>📋 Audit Log</h3>
                <p class="card-subtitle">System activity log (Latest 50 records)</p>
            </div>
        </div>

        <div class="card-toolbar">
            <input type="text" id="audit-search" class="search-input" placeholder="🔍 Search audit logs...">
            <select id="action-filter" class="filter-select">
                <option value="">All Actions</option>
                <option value="LOGIN">Login</option>
                <option value="CREATE_USER">Create User</option>
                <option value="UPDATE_USER">Update User</option>
                <option value="DELETE_USER">Delete User</option>
                <option value="IMPORT_DATA">Import Data</option>
                <option value="EXPORT_DATA">Export Data</option>
            </select>
        </div>

        <div class="table-responsive audit-table-wrapper">
            <table class="data-table audit-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody id="audit-table-body">
                    <?php 
                    $count = 0;
                    if (!empty($auditLogs)): 
                        foreach ($auditLogs as $log): 
                            if ($count >= 50) break;
                            $count++;
                    ?>
                        <tr class="audit-row" data-action="<?php echo htmlspecialchars($log['action'] ?? 'N/A'); ?>">
                            <td class="user-cell" data-label="User"><?php echo htmlspecialchars($log['name'] ?? ($log['email'] ?? 'System')); ?></td>
                            <td data-label="Action">
                                <span class="action-badge action-<?php echo strtolower(str_replace('_', '-', $log['action'] ?? 'UNKNOWN')); ?>">
                                    <?php echo htmlspecialchars($log['action'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td data-label="Details" class="text-muted" title="<?php echo htmlspecialchars($log['details'] ?? ''); ?>">
                                <?php echo htmlspecialchars(strlen($log['details'] ?? '') > 50 ? substr($log['details'], 0, 50) . '...' : $log['details']); ?>
                            </td>
                            <td data-label="IP Address" class="text-muted"><?php echo htmlspecialchars($log['ip_address'] ?? 'N/A'); ?></td>
                            <td data-label="Timestamp" class="text-muted" title="<?php echo htmlspecialchars($log['timestamp'] ?? ''); ?>">
                                <?php echo date('M d, Y h:i A', strtotime($log['timestamp'] ?? 'now')); ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr><td colspan="5" class="no-data">No audit logs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Data Management Section -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3>📥 Manage Import Data</h3>
                <p class="card-subtitle">View and delete imported household and individual data</p>
            </div>
        </div>

        <div class="card-toolbar">
            <input type="text" id="import-search" class="search-input" placeholder="🔍 Search imports...">
            <select id="import-status-filter" class="filter-select">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Barangay</th>
                        <th>Total Records</th>
                        <th>Processed</th>
                        <th>Status</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="imports-table-body">
                    <?php if (!empty($imports)): ?>
                        <?php foreach ($imports as $import): ?>
                            <tr class="import-row" data-import-id="<?php echo $import['id']; ?>" data-status="<?php echo htmlspecialchars($import['status'] ?? 'unknown'); ?>">
                                <td data-label="File Name">
                                    <strong><?php echo htmlspecialchars($import['file_name'] ?? 'N/A'); ?></strong>
                                </td>
                                <td data-label="Barangay"><?php echo htmlspecialchars($import['barangay_name'] ?? 'N/A'); ?></td>
                                <td data-label="Total Records" class="text-center">
                                    <span class="badge badge-info"><?php echo $import['total_records'] ?? '0'; ?></span>
                                </td>
                                <td data-label="Processed" class="text-center">
                                    <span class="badge badge-success"><?php echo $import['processed_records'] ?? '0'; ?></span>
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge <?php echo strtolower($import['status'] ?? 'unknown'); ?>">
                                        <?php echo htmlspecialchars($import['status'] ?? 'Unknown'); ?>
                                    </span>
                                </td>
                                <td data-label="Uploaded By" class="text-muted"><?php echo htmlspecialchars($import['uploader_email'] ?? 'N/A'); ?></td>
                                <td data-label="Date" class="text-muted"><?php echo date('M d, Y', strtotime($import['import_date'] ?? 'now')); ?></td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <button class="btn-icon delete-import-btn" title="Delete import and associated data" data-import-id="<?php echo $import['id']; ?>" data-import-name='<?php echo htmlspecialchars($import['file_name'], ENT_QUOTES, 'UTF-8'); ?>' data-records="<?php echo $import['processed_records'] ?? 0; ?>">🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="no-data">No imports found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Barangay Management Section -->
    <div class="card">
        <div class="card-header-flex">
            <div>
                <h3>🏘️ Barangay Management</h3>
                <p class="card-subtitle">Add and manage barangay information with chairman details</p>
            </div>
            <button class="btn btn-primary" id="add-barangay-btn">+ Add Barangay</button>
        </div>

        <div class="card-toolbar">
            <input type="text" id="barangay-search" class="search-input" placeholder="🔍 Search barangays...">
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Barangay Name</th>
                        <th>Chairman</th>
                        <th>Contact Number</th>
                        <th>Population</th>
                        <th>Area (km²)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="barangays-table-body">
                    <?php if (!empty($barangays)): ?>
                        <?php foreach ($barangays as $barangay): ?>
                            <tr class="barangay-row" data-barangay-id="<?php echo $barangay['id']; ?>">
                                <td data-label="Barangay Name">
                                    <strong><?php echo htmlspecialchars($barangay['name'] ?? 'N/A'); ?></strong>
                                </td>
                                <td data-label="Chairman">
                                    <?php echo htmlspecialchars($barangay['chairman'] ?? 'N/A'); ?>
                                </td>
                                <td data-label="Contact Number">
                                    <a href="tel:<?php echo htmlspecialchars($barangay['contact'] ?? ''); ?>" title="Call">
                                        <?php echo htmlspecialchars($barangay['contact'] ?? 'N/A'); ?>
                                    </a>
                                </td>
                                <td data-label="Population" class="text-center">
                                    <span class="badge badge-info"><?php echo number_format($barangay['population'] ?? 0); ?></span>
                                </td>
                                <td data-label="Area" class="text-center">
                                    <?php echo number_format($barangay['area'] ?? 0, 2); ?>
                                </td>
                                <td data-label="Actions">
                                    <div class="action-buttons">
                                        <button class="btn-icon edit-barangay-btn" title="Edit barangay" data-barangay-id="<?php echo $barangay['id']; ?>" data-barangay='<?php echo htmlspecialchars(json_encode($barangay), ENT_QUOTES, 'UTF-8'); ?>'>✏️</button>
                                        <button class="btn-icon delete-barangay-btn" title="Delete barangay" data-barangay-id="<?php echo $barangay['id']; ?>" data-barangay-name='<?php echo htmlspecialchars($barangay['name'], ENT_QUOTES, 'UTF-8'); ?>'>🗑️</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="no-data">No barangays found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Modal -->
<div id="user-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Add New User</h2>
            <button class="modal-close">&times;</button>
        </div>
        <form id="user-form">
            <div class="form-group">
                <label for="user-name">Full Name *</label>
                <input type="text" id="user-name" name="name" required placeholder="Enter user's full name">
            </div>

            <div class="form-group">
                <label for="user-email">Email Address *</label>
                <input type="email" id="user-email" name="email" required placeholder="user@example.com">
            </div>

            <div class="form-group">
                <label for="user-role">Role *</label>
                <select id="user-role" name="role" required>
                    <option value="">Select a role</option>
                    <option value="City Administrator">City Administrator</option>
                    <option value="POPDEV Manager">POPDEV Manager</option>
                    <option value="Barangay Data Encoder">Barangay Data Encoder</option>
                    <option value="Analyst">Analyst</option>
                    <option value="Viewer">Viewer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="user-status">Status *</label>
                <select id="user-status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div id="password-group" class="form-group">
                <label for="user-password">Password *</label>
                <input type="password" id="user-password" name="password" placeholder="Leave empty to keep existing password">
                <small>Minimum 8 characters, include uppercase, lowercase, and numbers</small>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="modal-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<!-- Password Reset Modal -->
<div id="password-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Reset Password</h2>
            <button class="modal-close">&times;</button>
        </div>
        <form id="password-form">
            <input type="hidden" id="password-user-id" name="user_id">
            <div class="form-group">
                <label for="new-password">New Password *</label>
                <input type="password" id="new-password" name="password" required placeholder="Enter new password">
                <small>Minimum 8 characters, include uppercase, lowercase, and numbers</small>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password *</label>
                <input type="password" id="confirm-password" name="confirm" required placeholder="Confirm new password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="password-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>Confirm Action</h2>
            <button class="modal-close">&times;</button>
        </div>
        <div id="confirm-message" class="confirm-message"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="confirm-cancel">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
        </div>
    </div>
</div>

<!-- Delete Import Confirmation Modal -->
<div id="delete-import-modal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>⚠️ Delete Import Data</h2>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <p id="delete-import-message"></p>
            <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 12px; margin-top: 15px; font-size: 13px;">
                <strong>⚠️ Warning:</strong> This action will permanently delete:
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    <li>The import record</li>
                    <li>All households from this import</li>
                    <li>All individuals in those households</li>
                    <li>The uploaded file</li>
                </ul>
                <p style="margin-top: 8px;"><strong>This action cannot be undone!</strong></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="delete-import-cancel">Cancel</button>
            <button type="button" class="btn btn-danger" id="delete-import-confirm">Yes, Delete All Data</button>
        </div>
    </div>
</div>

<!-- Barangay Modal -->
<div id="barangay-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="barangay-modal-title">Add New Barangay</h2>
            <button class="modal-close">&times;</button>
        </div>
        <form id="barangay-form">
            <input type="hidden" id="barangay-id" name="barangay_id">
            
            <div class="form-group">
                <label for="barangay-name">Barangay Name *</label>
                <input type="text" id="barangay-name" name="name" required placeholder="Enter barangay name">
            </div>

            <div class="form-group">
                <label for="barangay-chairman">Barangay Chairman *</label>
                <input type="text" id="barangay-chairman" name="chairman" required placeholder="Enter chairman's full name">
            </div>

            <div class="form-group">
                <label for="barangay-contact">Contact Number *</label>
                <input type="tel" id="barangay-contact" name="contact" required placeholder="Enter contact number (e.g., 09XX-XXX-XXXX)">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="barangay-population">Population</label>
                    <input type="number" id="barangay-population" name="population" min="0" placeholder="Enter total population">
                </div>

                <div class="form-group">
                    <label for="barangay-area">Area (km²)</label>
                    <input type="number" id="barangay-area" name="area" min="0" step="0.01" placeholder="Enter area in square kilometers">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="barangay-modal-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Barangay</button>
            </div>
        </form>
    </div>
</div>

<!-- Barangay Confirmation Modal -->
<div id="barangay-confirm-modal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header">
            <h2>⚠️ Delete Barangay</h2>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <p id="barangay-confirm-message"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="barangay-confirm-cancel">Cancel</button>
            <button type="button" class="btn btn-danger" id="barangay-confirm-delete">Yes, Delete</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast"></div>

<style>
.page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px;
    color: #1f2937;
}

.page-header p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-top: 4px solid #3b82f6;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.stat-card.stat-blue { border-top-color: #3b82f6; }
.stat-card.stat-green { border-top-color: #10b981; }

.stat-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.stat-card h4 {
    margin: 0 0 8px 0;
    font-size: 13px;
    color: #6b7280;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
}

.stat-subtitle {
    font-size: 12px;
    color: #9ca3af;
}

/* Cards */
.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    margin-bottom: 24px;
    overflow: hidden;
}

.card-header-flex {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.card-header-flex h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.card-subtitle {
    margin: 0;
    font-size: 13px;
    color: #6b7280;
}

.card-toolbar {
    padding: 16px 20px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.search-input,
.filter-select {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    font-family: inherit;
    transition: all 0.2s;
}

.search-input {
    flex: 1;
    min-width: 200px;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
}

/* Tables */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table thead {
    background: #f9fafb;
    position: sticky;
    top: 0;
}

.data-table th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
}

.data-table tbody tr {
    transition: background-color 0.2s;
}

.data-table tbody tr:hover {
    background-color: #f9fafb;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
}

/* Badges */
.role-badge,
.status-badge,
.action-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.role-badge {
    background: #e0e7ff;
    color: #3730a3;
}

.role-city-administrator { background: #fee2e2; color: #991b1b; }
.role-popdev-manager { background: #fef3c7; color: #92400e; }
.role-barangay-data-encoder { background: #d1fae5; color: #065f46; }
.role-analyst { background: #e0e7ff; color: #3730a3; }
.role-viewer { background: #f3e8ff; color: #6b21a8; }

.status-badge {
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.processing {
    background: #dbeafe;
    color: #0c4a6e;
}

.status-badge.completed {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.failed {
    background: #fee2e2;
    color: #991b1b;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-info {
    background: #e0e7ff;
    color: #3730a3;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.text-center {
    text-align: center;
}

.action-badge {
    background: #fef3c7;
    color: #92400e;
}

.action-create-user { background: #d1fae5; color: #065f46; }
.action-update-user { background: #e0e7ff; color: #3730a3; }
.action-delete-user { background: #fee2e2; color: #991b1b; }
.action-login { background: #d1fae5; color: #065f46; }
.action-import-data { background: #e0e7ff; color: #3730a3; }
.action-export-data { background: #fef3c7; color: #92400e; }

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.btn-icon {
    background: none;
    border: 1px solid #d1d5db;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-icon:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

/* Modals */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    animation: slideUp 0.3s;
}

.modal-small {
    max-width: 400px;
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 18px;
    color: #1f2937;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6b7280;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #1f2937;
}

.form-group {
    margin-bottom: 16px;
    padding: 0 20px;
}

.form-group:first-of-type {
    padding-top: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group small {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #6b7280;
}

.modal-footer {
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.confirm-message {
    padding: 20px;
    text-align: center;
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
}

/* Buttons */
.btn {
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

/* Toast */
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 16px 20px;
    background: #1f2937;
    color: white;
    border-radius: 6px;
    display: none;
    z-index: 2000;
    max-width: 400px;
    animation: slideInRight 0.3s;
}

.toast.show {
    display: block;
}

.toast.success {
    background: #10b981;
}

.toast.error {
    background: #ef4444;
}

.toast.warning {
    background: #f59e0b;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.text-muted {
    color: #6b7280;
}

.no-data {
    padding: 40px 16px !important;
    text-align: center;
    color: #6b7280;
}

.audit-table-wrapper {
    max-height: 700px;
}

/* Responsive Design */

/* Extra Small Devices (Mobile) */
@media (max-width: 480px) {
    .page-container {
        padding: 8px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        padding: 12px;
    }

    .page-header h1 {
        font-size: 20px;
    }

    .page-header p {
        font-size: 12px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .stat-card {
        padding: 16px;
        border-radius: 6px;
    }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
    }

    .stat-card h4 {
        font-size: 11px;
    }

    .card {
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .card-header-flex {
        padding: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .card-header-flex h3 {
        font-size: 16px;
    }

    .card-subtitle {
        font-size: 12px;
    }

    .card-toolbar {
        padding: 8px 12px;
        flex-direction: column;
        gap: 8px;
    }

    .search-input,
    .filter-select {
        width: 100%;
        padding: 8px 10px;
        font-size: 12px;
    }

    .search-input {
        min-width: 100%;
    }

    .btn {
        padding: 8px 12px;
        font-size: 12px;
        width: 100%;
    }

    .btn-primary {
        width: 100%;
    }

    .action-buttons {
        flex-direction: row;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .btn-icon {
        padding: 4px 6px;
        font-size: 12px;
        flex: 1;
        min-width: 28px;
    }

    /* Table Mobile Card View */
    .data-table {
        font-size: 12px;
        display: block;
    }

    .data-table thead {
        display: none;
    }

    .data-table tbody {
        display: block;
    }

    .data-table tr {
        display: block;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .data-table td {
        display: block;
        padding: 8px 12px;
        text-align: right;
        border: none;
        border-bottom: 1px solid #e5e7eb;
        font-size: 12px;
    }

    .data-table td:before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        font-size: 11px;
    }

    .data-table td:last-child {
        border-bottom: none;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .modal-content {
        width: calc(100% - 16px);
        margin: 8px;
        max-height: 85vh;
    }

    .modal-header {
        padding: 12px;
    }

    .modal-header h2 {
        font-size: 16px;
    }

    .form-group {
        padding: 0 12px;
        margin-bottom: 12px;
    }

    .form-group label {
        font-size: 12px;
        margin-bottom: 4px;
    }

    .form-group input,
    .form-group select {
        padding: 8px 10px;
        font-size: 12px;
    }

    .modal-footer {
        padding: 12px;
        flex-direction: column-reverse;
    }

    .modal-footer .btn {
        width: 100%;
        padding: 10px;
    }

    .confirm-message {
        padding: 12px;
        font-size: 12px;
    }

    .toast {
        width: calc(100% - 16px);
        right: 8px;
        left: 8px;
        bottom: 12px;
        padding: 12px 14px;
        font-size: 12px;
    }

    .page-header-user {
        flex-direction: column;
        gap: 8px;
    }
}

/* Small Tablets */
@media (max-width: 768px) {
    .page-container {
        padding: 12px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-value {
        font-size: 28px;
    }

    .card-header-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .card-toolbar {
        flex-direction: column;
        gap: 10px;
    }

    .search-input {
        min-width: 100%;
        flex: 1;
    }

    .filter-select {
        width: 100%;
    }

    .action-buttons {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 6px;
    }

    .btn-icon {
        padding: 6px 8px;
        font-size: 13px;
        flex: 0 1 auto;
    }

    .data-table {
        font-size: 13px;
    }

    .data-table th {
        padding: 10px 12px;
        font-size: 11px;
    }

    .data-table td {
        padding: 10px 12px;
    }

    .modal-content {
        width: 95%;
        margin: 20px auto;
        max-height: 85vh;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .toast {
        width: calc(100% - 40px);
        right: 20px;
        left: 20px;
        bottom: 15px;
    }

    .page-header-user {
        flex-direction: column;
    }

    .card {
        margin-bottom: 16px;
    }

    .audit-table-wrapper {
        max-height: 400px;
    }

    /* Better table handling on tablets */
    .table-responsive {
        border-radius: 6px;
        overflow: hidden;
    }
}

/* Medium Devices (Large Tablets) */
@media (max-width: 1024px) and (min-width: 769px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .card-toolbar {
        flex-wrap: wrap;
    }

    .data-table th,
    .data-table td {
        padding: 12px;
    }
}

/* Desktop View */
@media (min-width: 1025px) {
    .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .search-input {
        min-width: 250px;
    }
}
</style>

<script>
// Modal Management
const userModal = document.getElementById('user-modal');
const passwordModal = document.getElementById('password-modal');
const confirmModal = document.getElementById('confirm-modal');
const userForm = document.getElementById('user-form');
const passwordForm = document.getElementById('password-form');

// Show/Hide Modals
function showModal(modal) {
    modal.classList.add('show');
}

function hideModal(modal) {
    modal.classList.remove('show');
}

// Close buttons
document.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', function() {
        hideModal(this.closest('.modal'));
    });
});

document.getElementById('modal-cancel').addEventListener('click', () => hideModal(userModal));
document.getElementById('password-cancel').addEventListener('click', () => hideModal(passwordModal));
document.getElementById('confirm-cancel').addEventListener('click', () => hideModal(confirmModal));

// Add User
document.getElementById('add-user-btn').addEventListener('click', () => {
    document.getElementById('modal-title').textContent = 'Add New User';
    document.getElementById('password-group').style.display = 'block';
    userForm.reset();
    showModal(userModal);
});

// Edit User
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const user = JSON.parse(this.dataset.user);
        document.getElementById('modal-title').textContent = 'Edit User';
        document.getElementById('password-group').style.display = 'none';
        document.getElementById('user-name').value = user.name;
        document.getElementById('user-email').value = user.email;
        document.getElementById('user-role').value = user.role;
        document.getElementById('user-status').value = user.status;
        userForm.dataset.editId = user.id;
        showModal(userModal);
    });
});

// Password Reset
document.querySelectorAll('.password-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('password-user-id').value = this.dataset.userId;
        passwordForm.reset();
        showModal(passwordModal);
    });
});

// Delete User
let deleteUserId = null;
let deleteUserName = null;
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        deleteUserId = this.dataset.userId;
        deleteUserName = this.dataset.userName;
        document.getElementById('confirm-message').innerHTML = `Are you sure you want to delete <strong>${deleteUserName}</strong>? This action cannot be undone.`;
        showModal(confirmModal);
    });
});

document.getElementById('confirm-delete').addEventListener('click', () => {
    if (deleteUserId) {
        fetch(`/api/user/delete/${deleteUserId}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                showToast('User deleted successfully', 'success');
                location.reload();
            } else {
                showToast('Failed to delete user', 'error');
            }
        });
        hideModal(confirmModal);
    }
});

// Search & Filter
document.getElementById('user-search').addEventListener('input', filterUsers);
document.getElementById('role-filter').addEventListener('change', filterUsers);

function filterUsers() {
    const search = document.getElementById('user-search').value.toLowerCase();
    const role = document.getElementById('role-filter').value;
    
    document.querySelectorAll('.user-row').forEach(row => {
        const name = row.textContent.toLowerCase();
        const rowRole = row.dataset.role;
        const match = (name.includes(search)) && (!role || rowRole === role);
        row.style.display = match ? '' : 'none';
    });
}

document.getElementById('audit-search').addEventListener('input', filterAudit);
document.getElementById('action-filter').addEventListener('change', filterAudit);

function filterAudit() {
    const search = document.getElementById('audit-search').value.toLowerCase();
    const action = document.getElementById('action-filter').value;
    
    document.querySelectorAll('.audit-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowAction = row.dataset.action;
        const match = (text.includes(search)) && (!action || rowAction === action);
        row.style.display = match ? '' : 'none';
    });
}

// Import Search & Filter
document.getElementById('import-search').addEventListener('input', filterImports);
document.getElementById('import-status-filter').addEventListener('change', filterImports);

function filterImports() {
    const search = document.getElementById('import-search').value.toLowerCase();
    const status = document.getElementById('import-status-filter').value;
    
    document.querySelectorAll('.import-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.dataset.status;
        const match = (text.includes(search)) && (!status || rowStatus === status);
        row.style.display = match ? '' : 'none';
    });
}

// Delete Import Handlers
let deleteImportId = null;
let deleteImportName = null;
let deleteImportRecords = 0;

document.querySelectorAll('.delete-import-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        deleteImportId = this.dataset.importId;
        deleteImportName = this.dataset.importName;
        deleteImportRecords = this.dataset.records;
        
        const message = `You are about to delete the import "<strong>${deleteImportName}</strong>" which contains <strong>${deleteImportRecords}</strong> household records with their associated individual records.`;
        document.getElementById('delete-import-message').innerHTML = message;
        
        showModal(document.getElementById('delete-import-modal'));
    });
});

document.getElementById('delete-import-confirm').addEventListener('click', () => {
    if (deleteImportId) {
        fetch(`/api/import/delete/${deleteImportId}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                showToast('Import and all associated data deleted successfully', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.error || 'Failed to delete import', 'error');
            }
        }).catch(err => {
            showToast('Error deleting import: ' + err.message, 'error');
        });
        hideModal(document.getElementById('delete-import-modal'));
    }
});

document.getElementById('delete-import-cancel').addEventListener('click', () => {
    hideModal(document.getElementById('delete-import-modal'));
});

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Form Submission
userForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const editId = this.dataset.editId;
    const url = editId ? `/api/user/update/${editId}` : '/api/user/create';
    
    fetch(url, {
        method: editId ? 'PUT' : 'POST',
        body: formData
    }).then(res => res.json()).then(data => {
        if (data.success) {
            showToast(editId ? 'User updated successfully' : 'User created successfully', 'success');
            hideModal(userModal);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.error || 'Error saving user', 'error');
        }
    });
});

passwordForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const password = document.getElementById('new-password').value;
    const confirm = document.getElementById('confirm-password').value;
    
    if (password !== confirm) {
        showToast('Passwords do not match', 'error');
        return;
    }
    
    const userId = document.getElementById('password-user-id').value;
    const formData = new FormData();
    formData.append('password', password);
    
    fetch(`/api/user/update/${userId}`, {
        method: 'PUT',
        body: formData
    }).then(res => res.json()).then(data => {
        if (data.success) {
            showToast('Password reset successfully', 'success');
            hideModal(passwordModal);
        } else {
            showToast(data.error || 'Error resetting password', 'error');
        }
    });
});

// Close modals on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal(this);
        }
    });
});
</script>

<script>
// Modal creation helper
function createModal(title, content, buttons = []) {
    // Remove existing modal if any
    const existingModal = document.querySelector('.custom-modal');
    if (existingModal) existingModal.remove();
    
    const modal = document.createElement('div');
    modal.className = 'custom-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    `;
    
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white;
        padding: 24px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    `;
    
    const titleEl = document.createElement('h2');
    titleEl.textContent = title;
    titleEl.style.cssText = `
        margin: 0 0 16px 0;
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    `;
    
    const bodyEl = document.createElement('div');
    bodyEl.innerHTML = content;
    bodyEl.style.cssText = `
        margin-bottom: 20px;
        color: #4b5563;
        line-height: 1.5;
    `;
    
    const buttonsDiv = document.createElement('div');
    buttonsDiv.style.cssText = `
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    `;
    
    buttons.forEach(btn => {
        const btnEl = document.createElement('button');
        btnEl.textContent = btn.text;
        btnEl.style.cssText = `
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            background: ${btn.bg || '#e5e7eb'};
            color: ${btn.color || '#1f2937'};
        `;
        btnEl.onclick = btn.onClick;
        buttonsDiv.appendChild(btnEl);
    });
    
    modalContent.appendChild(titleEl);
    modalContent.appendChild(bodyEl);
    modalContent.appendChild(buttonsDiv);
    modal.appendChild(modalContent);
    
    // Close on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.remove();
    });
    
    document.body.appendChild(modal);
    return modal;
}

// Edit User
function editUser(user) {
    const form = document.createElement('form');
    form.style.cssText = `
        display: flex;
        flex-direction: column;
        gap: 12px;
    `;
    
    form.innerHTML = `
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Name</label>
            <input type="text" id="edit-name" value="${user.name}" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Email</label>
            <input type="email" id="edit-email" value="${user.email}" readonly style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; background: #f9fafb; color: #6b7280;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Role</label>
            <select id="edit-role" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                <option value="Analyst" ${user.role === 'Analyst' ? 'selected' : ''}>Analyst</option>
                <option value="Admin" ${user.role === 'Admin' ? 'selected' : ''}>Admin</option>
                <option value="Manager" ${user.role === 'Manager' ? 'selected' : ''}>Manager</option>
            </select>
        </div>
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Status</label>
            <select id="edit-status" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
            </select>
        </div>
    `;
    
    createModal('Edit User', form.outerHTML, [
        {
            text: 'Cancel',
            bg: '#e5e7eb',
            color: '#1f2937',
            onClick: () => document.querySelector('.custom-modal').remove()
        },
        {
            text: 'Save',
            bg: '#3b82f6',
            color: 'white',
            onClick: async () => {
                const name = document.getElementById('edit-name').value;
                const role = document.getElementById('edit-role').value;
                const status = document.getElementById('edit-status').value;
                
                const formData = new FormData();
                formData.append('name', name);
                formData.append('role', role);
                formData.append('status', status);
                
                try {
                    const response = await fetch(`/api/user/update/${user.id}`, {
                        method: 'PUT',
                        body: formData
                    });
                    
                    if (response.ok) {
                        alert('User updated successfully!');
                        location.reload();
                    } else {
                        alert('Failed to update user');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating user');
                }
            }
        }
    ]);
}

// View/Change Password
function viewPassword(userId) {
    const form = document.createElement('form');
    form.style.cssText = `
        display: flex;
        flex-direction: column;
        gap: 12px;
    `;
    
    form.innerHTML = `
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">New Password</label>
            <input type="password" id="new-password" placeholder="Enter new password" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Confirm Password</label>
            <input type="password" id="confirm-password" placeholder="Confirm password" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
        </div>
    `;
    
    createModal('Change Password', form.outerHTML, [
        {
            text: 'Cancel',
            bg: '#e5e7eb',
            color: '#1f2937',
            onClick: () => document.querySelector('.custom-modal').remove()
        },
        {
            text: 'Update Password',
            bg: '#10b981',
            color: 'white',
            onClick: async () => {
                const newPass = document.getElementById('new-password').value;
                const confirmPass = document.getElementById('confirm-password').value;
                
                if (!newPass || !confirmPass) {
                    alert('Please fill in all fields');
                    return;
                }
                
                if (newPass !== confirmPass) {
                    alert('Passwords do not match');
                    return;
                }
                
                const formData = new FormData();
                formData.append('password', newPass);
                
                try {
                    const response = await fetch(`/api/user/update/${userId}`, {
                        method: 'PUT',
                        body: formData
                    });
                    
                    if (response.ok) {
                        alert('Password updated successfully!');
                        document.querySelector('.custom-modal').remove();
                    } else {
                        alert('Failed to update password');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating password');
                }
            }
        }
    ]);
}

// Delete User
function deleteUser(userId, userName) {
    createModal('Confirm Delete', `Are you sure you want to delete user <strong>${userName}</strong>? This action cannot be undone.`, [
        {
            text: 'Cancel',
            bg: '#e5e7eb',
            color: '#1f2937',
            onClick: () => document.querySelector('.custom-modal').remove()
        },
        {
            text: 'Delete',
            bg: '#ef4444',
            color: 'white',
            onClick: async () => {
                try {
                    const response = await fetch(`/api/user/delete/${userId}`, {
                        method: 'DELETE'
                    });
                    
                    if (response.ok) {
                        alert('User deleted successfully!');
                        location.reload();
                    } else {
                        alert('Failed to delete user');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error deleting user');
                }
            }
        }
    ]);
}

// Add User
document.addEventListener('DOMContentLoaded', () => {
    const addUserBtn = document.getElementById('add-user-btn');
    
    if (addUserBtn) {
        addUserBtn.addEventListener('click', () => {
            const form = document.createElement('form');
            form.style.cssText = `
                display: flex;
                flex-direction: column;
                gap: 12px;
            `;
            
            form.innerHTML = `
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Name</label>
                    <input type="text" id="new-name" placeholder="Full name" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Email</label>
                    <input type="email" id="new-email" placeholder="user@example.com" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Password</label>
                    <input type="password" id="new-password" placeholder="Leave empty for default" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 4px; font-weight: 500; font-size: 14px; color: #1f2937;">Role</label>
                    <select id="new-role" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px;">
                        <option value="Analyst">Analyst</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
            `;
            
            createModal('Add New User', form.outerHTML, [
                {
                    text: 'Cancel',
                    bg: '#e5e7eb',
                    color: '#1f2937',
                    onClick: () => document.querySelector('.custom-modal').remove()
                },
                {
                    text: 'Create User',
                    bg: '#3b82f6',
                    color: 'white',
                    onClick: async () => {
                        const name = document.getElementById('new-name').value;
                        const email = document.getElementById('new-email').value;
                        const password = document.getElementById('new-password').value;
                        const role = document.getElementById('new-role').value;
                        
                        if (!name || !email) {
                            alert('Please fill in all required fields');
                            return;
                        }
                        
                        const formData = new FormData();
                        formData.append('name', name);
                        formData.append('email', email);
                        formData.append('role', role);
                        if (password) formData.append('password', password);
                        
                        try {
                            const response = await fetch('/api/user/create', {
                                method: 'POST',
                                body: formData
                            });
                            
                            if (response.ok) {
                                alert('User created successfully!');
                                location.reload();
                            } else {
                                const data = await response.json();
                                alert(data.error || 'Failed to create user');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error creating user');
                        }
                    }
                }
            ]);
        });
    }
    
    // Edit button event listeners
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const user = JSON.parse(btn.getAttribute('data-user'));
            editUser(user);
        });
    });
    
    // Password button event listeners
    document.querySelectorAll('.password-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.getAttribute('data-user-id');
            viewPassword(userId);
        });
    });
    
    // Delete button event listeners
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.getAttribute('data-user-id');
            const userName = btn.getAttribute('data-user-name');
            deleteUser(userId, userName);
        });
    });

    // ==================== BARANGAY MANAGEMENT ====================

    // Add Barangay Button
    const addBarangayBtn = document.getElementById('add-barangay-btn');
    if (addBarangayBtn) {
        addBarangayBtn.addEventListener('click', () => {
            document.getElementById('barangay-id').value = '';
            document.getElementById('barangay-modal-title').textContent = 'Add New Barangay';
            document.getElementById('barangay-form').reset();
            const modal = document.getElementById('barangay-modal');
            modal.style.display = 'flex';
            modal.classList.add('active');
        });
    }

    // Edit Barangay Buttons
    document.querySelectorAll('.edit-barangay-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const barangayData = JSON.parse(btn.getAttribute('data-barangay'));
            document.getElementById('barangay-id').value = barangayData.id;
            document.getElementById('barangay-name').value = barangayData.name;
            document.getElementById('barangay-chairman').value = barangayData.chairman;
            document.getElementById('barangay-contact').value = barangayData.contact;
            document.getElementById('barangay-population').value = barangayData.population || '';
            document.getElementById('barangay-area').value = barangayData.area || '';
            document.getElementById('barangay-modal-title').textContent = 'Edit Barangay';
            const modal = document.getElementById('barangay-modal');
            modal.style.display = 'flex';
            modal.classList.add('active');
        });
    });

    // Barangay Form Submit
    const barangayForm = document.getElementById('barangay-form');
    if (barangayForm) {
        console.log('Attaching barangay form submit handler');
        barangayForm.addEventListener('submit', async (e) => {
            console.log('Barangay form submit event triggered');
            e.preventDefault();
            e.stopPropagation();
            
            const barangayId = document.getElementById('barangay-id').value;
            const name = document.getElementById('barangay-name').value;
            const chairman = document.getElementById('barangay-chairman').value;
            const contact = document.getElementById('barangay-contact').value;
            const population = document.getElementById('barangay-population').value;
            const area = document.getElementById('barangay-area').value;
            
            // Validate required fields
            if (!name || !chairman || !contact) {
                showToast('Please fill in all required fields', 'error');
                return;
            }
            
            try {
                let url, method;
                const body = JSON.stringify({
                    name,
                    chairman,
                    contact,
                    population: population ? parseInt(population) : null,
                    area: area ? parseFloat(area) : null
                });
                
                if (barangayId) {
                    url = `/api/barangay/${barangayId}`;
                    method = 'PUT';
                } else {
                    url = '/api/barangay';
                    method = 'POST';
                }
                
                console.log('Sending request to', url, 'with method', method);
                const response = await fetch(url, { 
                    method, 
                    headers: { 'Content-Type': 'application/json' },
                    body 
                });
                
                const data = await response.json();
                console.log('Response:', data, 'Status:', response.status);
                
                if (response.ok) {
                    showToast(`Barangay ${barangayId ? 'updated' : 'added'} successfully!`, 'success');
                    const modal = document.getElementById('barangay-modal');
                    modal.style.display = 'none';
                    modal.classList.remove('active');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.error || 'Failed to save barangay', 'error');
                }
            } catch (error) {
                console.error('Error saving barangay:', error);
                showToast('Error saving barangay: ' + error.message, 'error');
            }
        });
    } else {
        console.error('Barangay form not found!');
    }

    // Delete Barangay Buttons
    document.querySelectorAll('.delete-barangay-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const barangayId = btn.getAttribute('data-barangay-id');
            const barangayName = btn.getAttribute('data-barangay-name');
            
            document.getElementById('barangay-confirm-message').innerHTML = 
                `Are you sure you want to delete <strong>${barangayName}</strong>?`;
            document.getElementById('barangay-confirm-modal').style.display = 'flex';
            
            document.getElementById('barangay-confirm-delete').onclick = async () => {
                try {
                    const response = await fetch(`/api/barangay/${barangayId}`, { method: 'DELETE' });
                    
                    if (response.ok) {
                        showToast('Barangay deleted successfully!', 'success');
                        document.getElementById('barangay-confirm-modal').style.display = 'none';
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        const error = await response.json();
                        showToast(error.error || 'Failed to delete barangay', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error deleting barangay', 'error');
                }
            };
        });
    });

    // Barangay Search
    const barangaySearch = document.getElementById('barangay-search');
    if (barangaySearch) {
        barangaySearch.addEventListener('keyup', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.barangay-row').forEach(row => {
                const name = row.querySelector('td:first-child').textContent.toLowerCase();
                const chairman = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const matches = name.includes(searchTerm) || chairman.includes(searchTerm);
                row.style.display = matches ? '' : 'none';
            });
        });
    }

    // Modal Close Handlers
    document.getElementById('barangay-modal').addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-close') || e.target === e.currentTarget) {
            const modal = e.currentTarget;
            modal.style.display = 'none';
            modal.classList.remove('active');
        }
    });

    document.getElementById('barangay-modal-cancel').addEventListener('click', () => {
        const modal = document.getElementById('barangay-modal');
        modal.style.display = 'none';
        modal.classList.remove('active');
    });

    document.getElementById('barangay-confirm-modal').addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-close') || e.target === e.currentTarget) {
            e.currentTarget.style.display = 'none';
        }
    });

    document.getElementById('barangay-confirm-cancel').addEventListener('click', () => {
        document.getElementById('barangay-confirm-modal').style.display = 'none';
    });
});
</script>


