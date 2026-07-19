<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo env('APP_NAME'); ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body data-user-role="<?php echo auth_user()['role'] ?? 'Viewer'; ?>" data-user-id="<?php echo auth_id() ?? 0; ?>" data-user-email="<?php echo auth_user()['email'] ?? ''; ?>" data-user-name="<?php echo auth_user()['name'] ?? 'User'; ?>"><?php echo "\n"; ?>
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="https://calambacity.gov.ph/maincss/assets/img/logocity.webp" alt="Calamba City Logo" class="sidebar-logo">
                <h2>PopDev</h2>
                <p>Resource Network</p>
                <button class="sidebar-hide-btn" id="sidebar-hide" title="Hide Menu">X</button>
            </div>

            <nav class="sidebar-nav">
                <!-- Main Menu -->
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <?php if (is_data_encoder()): ?>
                        <!-- Data Encoder sees minimal menu -->
                        <a class="nav-item" href="/data-import" data-page="data-import">
                            <span>Upload Data</span>
                        </a>
                    <?php else: ?>
                        <!-- Regular users see full menu -->
                        <a class="nav-item" href="/dashboard" data-page="dashboard">
                            <span>Dashboard</span>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!is_data_encoder()): ?>
                <!-- Data Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Data Management</div>
                    <a class="nav-item" href="/data-management" data-page="data-management">
                        <span>Records</span>
                    </a>
                    <a class="nav-item" href="/data-import" data-page="data-import">
                        <span>Excel Import</span>
                    </a>
                </div>

                <!-- Barangay Records -->
                <div class="nav-section">
                    <div class="nav-section-title">Barangay Records</div>
                    <a class="nav-item" href="/barangay-records" data-page="barangay-records">
                        <span>Health Metrics</span>
                    </a>
                </div>

                <!-- Knowledge Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Knowledge Hub</div>
                    <a class="nav-item" href="/knowledge-management" data-page="knowledge-management">
                        <span>Knowledge Base</span>
                    </a>
                </div>

                <!-- ML Analytics -->
                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <a class="nav-item" href="/ml-analytics" data-page="ml-analytics">
                        <span>ML Analytics</span>
                    </a>
                </div>

                <!-- Decision Support -->
                <div class="nav-section">
                    <div class="nav-section-title">Strategy</div>
                    <a class="nav-item" href="/decision-support" data-page="decision-support">
                        <span>Decision Support</span>
                    </a>
                </div>

                <!-- Security & Governance -->
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>
                    <a class="nav-item" href="/security-governance" data-page="security-governance">
                        <span>Security & Governance</span>
                    </a>
                </div>
                <?php endif; ?>

                <!-- User Section -->
                <div class="nav-section" style="margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.15); padding-top: 20px; padding-bottom: 20px;">
                    <div class="nav-item" style="font-size: 12px; opacity: 0.85; cursor: default; padding: 14px 20px;">
                        <div style="display: flex; align-items: center; width: 100%;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; margin-right: 12px; font-weight: 700; overflow: hidden; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; <?php echo !empty(auth_user()['profile_photo']) ? "background-image: url('" . htmlspecialchars(auth_user()['profile_photo']) . "'); color: transparent; font-size: 0;" : "background: rgba(255, 255, 255, 0.2);"; ?>">
                                <?php if (empty(auth_user()['profile_photo'])): ?><?php echo strtoupper(substr(auth_user()['name'] ?? 'U', 0, 1)); ?><?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 13px; white-space: normal;">
                                    <?php echo htmlspecialchars(auth_user()['email'] ?? 'User'); ?>
                                </div>
                                <div style="font-size: 11px; opacity: 0.75; margin-top: 4px;">
                                    <?php echo htmlspecialchars(auth_user()['role'] ?? 'Viewer'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="nav-item" href="/account" data-page="account">
                        <span>Account</span>
                    </a>
                    <button class="nav-item" style="border: none; background: none; cursor: pointer; padding: 14px 20px; text-align: left; width: 100%;" onclick="openSettingsModal()" title="Settings & Information">
                        <span>Settings</span>
                    </button>
                    <a class="nav-item" href="<?php echo url('/api/logout'); ?>" data-page="logout">
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" id="sidebar-toggle" title="Toggle Menu">☰</button>
                    <div class="topbar-title">
                        <h1>Calamba PopDev Resource Network</h1>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="topbar-user">
                        <div class="user-info">
                            <p><?php echo htmlspecialchars(auth_user()['name'] ?? 'User'); ?></p>
                            <p><?php echo htmlspecialchars(auth_user()['role'] ?? 'Role'); ?></p>
                        </div>
                        <div class="user-avatar" title="<?php echo htmlspecialchars(auth_user()['email'] ?? 'User'); ?>" style="cursor: pointer; <?php echo !empty(auth_user()['profile_photo']) ? "background-image: url('" . htmlspecialchars(auth_user()['profile_photo']) . "'); background-size: cover; background-position: center; font-size: 0; color: transparent;" : ""; ?>" onclick="window.location.href='/account'">
                            <?php if (empty(auth_user()['profile_photo'])): ?><?php echo strtoupper(substr(auth_user()['name'] ?? 'U', 0, 1)); ?><?php endif; ?>
                        </div>
                    </div>
                    <button id="cool-btn" class="cool-btn" title="Quick Actions">Actions</button>
                </div>
            </div>

            <!-- Page Content -->
            <div class="content">
    <div class="modal" id="barangay-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2> Add Barangay</h2>
                <button class="modal-close" onclick="app.closeModal(document.getElementById('barangay-modal'))">&times;</button>
            </div>
            <form id="barangay-form">
                <div class="form-group">
                    <label for="barangay-name">Barangay Name</label>
                    <input type="text" id="barangay-name" name="name" required placeholder="Enter barangay name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="barangay-population">Population</label>
                        <input type="number" id="barangay-population" name="population" required placeholder="0">
                    </div>
                    <div class="form-group">
                        <label for="barangay-area">Area (km2)</label>
                        <input type="number" id="barangay-area" step="0.01" name="area" required placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label for="barangay-chairman">Chairman</label>
                    <input type="text" id="barangay-chairman" name="chairman" required placeholder="Chairman name">
                </div>
                <div class="form-group">
                    <label for="barangay-contact">Contact</label>
                    <input type="text" id="barangay-contact" name="contact" required placeholder="Contact number or email">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="app.closeModal(document.getElementById('barangay-modal'))">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Barangay</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Mobile Overlay for Sidebar -->
    <div class="overlay" id="sidebar-overlay"></div>

    <!-- Sidebar reveal button (shows when sidebar is hidden) -->
    <button id="sidebar-show" class="sidebar-show-btn" aria-label="Show menu" style="display: none; font-size: 24px; line-height: 1;">☰</button>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- Main Application Script -->
    <script src="<?php echo asset('js/app.js'); ?>"></script>
    
    <!-- Ensure cool button works if app.js hasn't initialized -->
    <script>
        // Simple theme manager
        class SimpleThemeManager {
            constructor() {
                this.STORAGE_KEY = 'app_theme';
                this.applyStoredTheme();
            }
            
            setTheme(theme) {
                localStorage.setItem(this.STORAGE_KEY, theme);
                if (theme === 'auto') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    this.applyTheme(prefersDark ? 'dark' : 'light');
                } else {
                    this.applyTheme(theme);
                }
            }
            
            applyTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.classList.remove('dark-mode');
                }
                
                // Update theme panel colors if it exists and is visible
                const panel = document.getElementById('cool-panel');
                if (panel && panel.style.display === 'block') {
                    const isDarkMode = theme === 'dark';
                    const bgColor = isDarkMode ? '#1f2937' : '#ffffff';
                    const textColor = isDarkMode ? '#f9fafb' : '#1f2937';
                    const borderColor = isDarkMode ? '#374151' : '#e5e7eb';
                    
                    panel.style.backgroundColor = bgColor;
                    panel.style.color = textColor;
                    panel.style.borderColor = borderColor;
                    
                    // Update all labels and heading
                    const heading = panel.querySelector('h4');
                    if (heading) heading.style.color = textColor;
                    
                    panel.querySelectorAll('label').forEach(label => {
                        label.style.color = textColor;
                    });
                }
            }
            
            applyStoredTheme() {
                const theme = localStorage.getItem(this.STORAGE_KEY) || 'auto';
                this.setTheme(theme);
            }
            
            getTheme() {
                return localStorage.getItem(this.STORAGE_KEY) || 'auto';
            }
        }
        
        const simpleThemeManager = new SimpleThemeManager();
        
        function initializeCoolButton() {
            const coolBtn = document.getElementById('cool-btn');
            if (!coolBtn) return;
            
            // Remove any existing listeners to avoid duplicates
            const newBtn = coolBtn.cloneNode(true);
            coolBtn.parentNode.replaceChild(newBtn, coolBtn);
            
            const btn = document.getElementById('cool-btn');
            
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                let panel = document.getElementById('cool-panel');
                
                if (!panel) {
                    const currentTheme = simpleThemeManager.getTheme();
                    const currentApplied = document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light';
                    const isDarkMode = currentApplied === 'dark';
                    const bgColor = isDarkMode ? '#1f2937' : '#ffffff';
                    const textColor = isDarkMode ? '#f9fafb' : '#1f2937';
                    const borderColor = isDarkMode ? '#374151' : '#e5e7eb';
                    
                    panel = document.createElement('div');
                    panel.id = 'cool-panel';
                    panel.style.cssText = `position: fixed; z-index: 1200; background: ${bgColor}; border: 1px solid ${borderColor}; border-radius: 8px; padding: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); min-width: 200px; color: ${textColor};`;
                    
                    panel.innerHTML = `
                        <h4 style="margin: 0 0 8px 0; font-size: 12px; font-weight: 600; color: ${textColor};">Appearance</h4>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <label style="margin: 0; display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; padding: 4px; border-radius: 4px; transition: background 0.18s ease; color: ${textColor};">
                                <input type="radio" name="theme" value="light" ${currentTheme === 'light' ? 'checked' : ''} style="width: 16px; height: 16px; cursor: pointer; margin: 0;">
                                <span>☀️ Light</span>
                            </label>
                            <label style="margin: 0; display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; padding: 4px; border-radius: 4px; transition: background 0.18s ease; color: ${textColor};">
                                <input type="radio" name="theme" value="dark" ${currentTheme === 'dark' ? 'checked' : ''} style="width: 16px; height: 16px; cursor: pointer; margin: 0;">
                                <span>🌙 Dark</span>
                            </label>
                            <label style="margin: 0; display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; padding: 4px; border-radius: 4px; transition: background 0.18s ease; color: ${textColor};">
                                <input type="radio" name="theme" value="auto" ${currentTheme === 'auto' ? 'checked' : ''} style="width: 16px; height: 16px; cursor: pointer; margin: 0;">
                                <span>🔄 Auto</span>
                            </label>
                        </div>
                    `;
                    document.body.appendChild(panel);
                    
                    // Add theme radio button listeners
                    panel.querySelectorAll('input[name="theme"]').forEach(radio => {
                        radio.addEventListener('change', (e) => {
                            simpleThemeManager.setTheme(e.target.value);
                        });
                    });
                }
                
                if (panel.style.display === 'block') {
                    panel.remove();
                } else {
                    panel.style.display = 'block';
                    const rect = btn.getBoundingClientRect();
                    panel.style.top = (rect.bottom + window.scrollY + 8) + 'px';
                    panel.style.right = '20px';
                }
            });
            
            // Close panel when clicking outside
            document.addEventListener('click', function(e) {
                const panel = document.getElementById('cool-panel');
                const btn = document.getElementById('cool-btn');
                if (panel && !panel.contains(e.target) && e.target !== btn) {
                    panel.style.display = 'none';
                }
            });
        }
        
        // Sidebar toggle functionality
        function initializeSidebarToggle() {
            const sidebarToggleBtn = document.getElementById('sidebar-toggle');
            const sidebarHideBtn = document.getElementById('sidebar-hide');
            const sidebarShowBtn = document.getElementById('sidebar-show');
            const sidebar = document.querySelector('.sidebar');
            const layout = document.querySelector('.layout');
            const overlay = document.getElementById('sidebar-overlay');
            const STORAGE_KEY = 'sidebar_hidden';
            
            // Check if sidebar should be hidden from localStorage
            const isSidebarHidden = localStorage.getItem(STORAGE_KEY) === 'true';
            if (isSidebarHidden) {
                sidebar.classList.add('collapsed');
                layout.classList.add('sidebar-collapsed');
            }
            
            // Menu button (toggle) click - shows/hides sidebar
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (sidebar.classList.contains('collapsed')) {
                        sidebar.classList.remove('collapsed');
                        layout.classList.remove('sidebar-collapsed');
                        layout.classList.add('sidebar-open');
                        localStorage.removeItem(STORAGE_KEY);
                    } else {
                        sidebar.classList.add('collapsed');
                        layout.classList.add('sidebar-collapsed');
                        layout.classList.remove('sidebar-open');
                        localStorage.setItem(STORAGE_KEY, 'true');
                    }
                });
            }
            
            // Hide sidebar button click (X button)
            if (sidebarHideBtn) {
                sidebarHideBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.add('collapsed');
                    layout.classList.add('sidebar-collapsed');
                    layout.classList.remove('sidebar-open');
                    localStorage.setItem(STORAGE_KEY, 'true');
                });
            }
            
            // Show sidebar button click (reveal button)
            if (sidebarShowBtn) {
                sidebarShowBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.remove('collapsed');
                    layout.classList.remove('sidebar-collapsed');
                    layout.classList.add('sidebar-open');
                    localStorage.removeItem(STORAGE_KEY);
                });
            }

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.add('collapsed');
                    layout.classList.add('sidebar-collapsed');
                    layout.classList.remove('sidebar-open');
                    localStorage.setItem(STORAGE_KEY, 'true');
                });
            }
        }
        
        // Set up responsive handler to auto-hide menu button on large screens
        function setupResponsiveHandler() {
            const handleResize = () => {
                if (window.innerWidth > 768) {
                    const layout = document.querySelector('.layout');
                    if (layout?.classList.contains('sidebar-collapsed')) {
                        layout.classList.remove('sidebar-collapsed');
                        document.querySelector('.sidebar')?.classList.remove('collapsed');
                        localStorage.removeItem('sidebar_hidden');
                    }
                }
            };
            window.addEventListener('resize', handleResize);
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initializeSidebarToggle();
                setupResponsiveHandler();
                initializeCoolButton();
            });
        } else {
            initializeSidebarToggle();
            setupResponsiveHandler();
            initializeCoolButton();
        }
    </script>
    
    <!-- Settings Modal -->
    <div id="settings-modal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 900px; display: flex; flex-direction: column; max-height: 85vh;">
            <!-- Header -->
            <div class="modal-header" style="flex-shrink: 0; background: #fff; border-bottom: 1px solid #e5e7eb; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 24px;">⚙️ Settings & Information</h2>
                <button class="modal-close" onclick="closeSettingsModal()" style="font-size: 24px; background: none; border: none; cursor: pointer; padding: 5px 10px; flex-shrink: 0;">&times;</button>
            </div>
            
            <!-- Tabs Navigation (NOT Scrollable) -->
            <div style="flex-shrink: 0; display: flex; gap: 10px; background: white; border-bottom: 2px solid #e5e7eb; padding: 0 30px;">
                <button class="settings-tab active" onclick="switchSettingsTab('agreement')" style="padding: 12px 20px; background: none; border: none; cursor: pointer; font-size: 14px; font-weight: 600; color: #3b82f6; border-bottom: 3px solid #3b82f6; margin-bottom: -2px;">📋 User Agreement</button>
                <button class="settings-tab" onclick="switchSettingsTab('algorithms')" style="padding: 12px 20px; background: none; border: none; cursor: pointer; font-size: 14px; font-weight: 600; color: #6b7280;">🤖 ML Algorithms</button>
            </div>
            
            <!-- Scrollable Body -->
            <div class="modal-body" style="overflow-y: auto; flex: 1; padding: 30px;">
                <!-- User Agreement Tab -->
                <div id="agreement-tab" class="settings-tab-content" style="display: block;">
                    <h3 style="color: #1f2937; font-size: 18px; margin-top: 0; margin-bottom: 16px;">User Agreement & Terms</h3>
                    <div style="background: #f9fafb; padding: 20px; border-radius: 8px; line-height: 1.8; color: #374151; font-size: 14px;">
                        <h4 style="color: #1f2937; margin-bottom: 10px;">1. Data Privacy & Protection</h4>
                        <p style="margin-bottom: 16px;">All household and individual data collected through this system is protected under data privacy laws. Information is used solely for policy-making, service delivery, and improving community welfare programs. Data is never shared with unauthorized third parties.</p>
                        
                        <h4 style="color: #1f2937; margin-bottom: 10px;">2. User Responsibilities</h4>
                        <p style="margin-bottom: 16px;">Users agree to:
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li>Maintain confidentiality of login credentials</li>
                            <li>Use the system only for authorized government functions</li>
                            <li>Report data discrepancies immediately to administrators</li>
                            <li>Comply with all data quality standards</li>
                            <li>Not attempt to access unauthorized data or functions</li>
                        </ul>
                        </p>
                        
                        <h4 style="color: #1f2937; margin-bottom: 10px;">3. Data Accuracy</h4>
                        <p style="margin-bottom: 16px;">Users are responsible for ensuring data accuracy at point of entry. The system maintains an audit trail of all data modifications and is monitored for quality assurance.</p>
                        
                        <h4 style="color: #1f2937; margin-bottom: 10px;">4. Machine Learning Analytics</h4>
                        <p style="margin-bottom: 16px;">ML analytics results are predictive in nature and should be used as guidance for decision-making, not as absolute determinants. All predictions should be reviewed by qualified personnel before implementation.</p>
                        
                        <h4 style="color: #1f2937; margin-bottom: 10px;">5. System Monitoring</h4>
                        <p style="margin-bottom: 16px;">All user activities are logged for security and accountability purposes. Unauthorized access attempts are recorded and may result in disciplinary action.</p>
                        
                        <h4 style="color: #1f2937; margin-bottom: 10px;">6. Support & Inquiries</h4>
                        <p style="margin-bottom: 0;">For technical support or questions about data handling, contact the City Administrator or IT department.</p>
                    </div>
                </div>
                
                <!-- ML Algorithms Tab -->
                <div id="algorithms-tab" class="settings-tab-content" style="display: none;">
                    <h3 style="color: #1f2937; font-size: 18px; margin-top: 0; margin-bottom: 20px;">Machine Learning Algorithms in This System</h3>
                    
                    <!-- Decision Tree -->
                    <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #f59e0b;">
                        <h4 style="color: #92400e; margin-top: 0; margin-bottom: 10px;">🌳 Decision Tree</h4>
                        <p style="color: #b45309; margin: 0; font-size: 13px; line-height: 1.6;"><strong>What it is:</strong> A tree-like model that makes decisions by asking yes/no questions about features in the data. It branches like a flowchart until reaching a conclusion.</p>
                        <p style="color: #b45309; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>How it works:</strong> Splits data based on feature values that best separate households into risk categories. Each split creates a branch, and the path from root to leaf represents a decision rule.</p>
                        <p style="color: #b45309; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>In this system:</strong> Predicts household risk levels (high/moderate/low) for receiving government assistance. Considers: Income level, family size, education, health access, employment status.</p>
                        <p style="color: #b45309; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>Example:</strong> "IF income < 10,000 AND family_size > 4 THEN high_risk" → suggests job programs and food assistance.</p>
                        <p style="color: #b45309; margin: 0; font-size: 13px; line-height: 1.6;"><strong>Accuracy:</strong> Good for interpretable, rule-based decisions (70-85%)</p>
                    </div>
                    
                    <!-- K-Means Clustering -->
                    <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #3b82f6;">
                        <h4 style="color: #1e40af; margin-top: 0; margin-bottom: 10px;">🎯 K-Means Clustering</h4>
                        <p style="color: #1e3a8a; margin: 0; font-size: 13px; line-height: 1.6;"><strong>What it is:</strong> Groups similar households together by finding natural clusters in the data. Treats each household as a point in multi-dimensional space.</p>
                        <p style="color: #1e3a8a; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>How it works:</strong> Creates K groups (e.g., 3-5 clusters) and assigns each household to the nearest cluster center based on similarities. Iteratively adjusts clusters until stable.</p>
                        <p style="color: #1e3a8a; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>In this system:</strong> Identifies natural groupings of households with similar characteristics. Helps target programs to specific community segments with common needs.</p>
                        <p style="color: #1e3a8a; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>Example:</strong> Creates clusters like "Urban Poor", "Rural Agricultural", "Middle Class" based on income, occupation, location. Each cluster gets tailored interventions.</p>
                        <p style="color: #1e3a8a; margin: 0; font-size: 13px; line-height: 1.6;"><strong>Use case:</strong> Customized program design, targeted resource allocation, community profiling</p>
                    </div>
                    
                    <!-- Random Forest -->
                    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #10b981;">
                        <h4 style="color: #065f46; margin-top: 0; margin-bottom: 10px;">🌲 Random Forest</h4>
                        <p style="color: #166534; margin: 0; font-size: 13px; line-height: 1.6;"><strong>What it is:</strong> Combines many decision trees (a "forest") and aggregates their predictions to make better, more accurate decisions.</p>
                        <p style="color: #166534; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>How it works:</strong> Creates 100+ random decision trees, each trained on different data samples. Final prediction is the majority vote among all trees, reducing errors.</p>
                        <p style="color: #166534; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>In this system:</strong> Provides highly accurate risk predictions by combining insights from multiple independent trees. Handles complex interactions between household features.</p>
                        <p style="color: #166534; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>Example:</strong> Instead of one tree's decision, uses consensus from 100 trees to predict "needs emergency assistance" with 85%+ confidence.</p>
                        <p style="color: #166534; margin: 0; font-size: 13px; line-height: 1.6;"><strong>Advantage:</strong> More robust, resistant to overfitting, handles non-linear patterns (80-90% accuracy)</p>
                    </div>
                    
                    <!-- Regression Analysis -->
                    <div style="background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); padding: 20px; border-radius: 8px; border-left: 5px solid #a855f7;">
                        <h4 style="color: #6b21a8; margin-top: 0; margin-bottom: 10px;">📈 Regression Analysis</h4>
                        <p style="color: #7e22ce; margin: 0; font-size: 13px; line-height: 1.6;"><strong>What it is:</strong> Predicts continuous numerical values by finding the mathematical relationship between input features and output. Creates trend lines or equations.</p>
                        <p style="color: #7e22ce; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>How it works:</strong> Fits a line or curve through data points to establish patterns. For example: "Higher income → fewer health issues" or "Larger family → more assistance needed".</p>
                        <p style="color: #7e22ce; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>In this system:</strong> Forecasts population trends (12-month predictions), estimates resource needs, projects program impact, predicts intervention costs.</p>
                        <p style="color: #7e22ce; margin: 10px 0; font-size: 13px; line-height: 1.6;"><strong>Example:</strong> "Based on birth rates and migration, population will grow from 236 to 260 households in 12 months → budget 45 more assistance packages".</p>
                        <p style="color: #7e22ce; margin: 0; font-size: 13px; line-height: 1.6;"><strong>Use for:</strong> 12-month forecasts, resource planning, trend analysis, impact projection</p>
                    </div>
                </div>
                
                <!-- Comparison Table -->
                <div id="comparison-section" style="margin-top: 30px;">
                    <h4 style="color: #1f2937; margin-bottom: 15px;">📊 Algorithm Comparison</h4>
                    <div style="overflow-x: auto; background: white; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 600; border-right: 1px solid #e5e7eb;">Algorithm</th>
                                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 600; border-right: 1px solid #e5e7eb;">Best For</th>
                                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 600; border-right: 1px solid #e5e7eb;">Accuracy</th>
                                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 600;">Speed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;"><strong>🌳 Decision Tree</strong></td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">Rule-based decisions, interpretability</td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">70-85%</td>
                                    <td style="padding: 12px;">Very Fast</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;"><strong>🌲 Random Forest</strong></td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">High accuracy predictions, complex patterns</td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">80-90%</td>
                                    <td style="padding: 12px;">Fast</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;"><strong>🎯 K-Means Clustering</strong></td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">Grouping, segmentation, program design</td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">Qualitative</td>
                                    <td style="padding: 12px;">Medium</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;"><strong>📈 Regression</strong></td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">Forecasting, trends, relationships</td>
                                    <td style="padding: 12px; border-right: 1px solid #e5e7eb;">75-88%</td>
                                    <td style="padding: 12px;">Very Fast</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Settings Modal JavaScript -->
    <script>
        function openSettingsModal() {
            const modal = document.getElementById('settings-modal');
            if (modal) {
                modal.style.display = 'flex';
                modal.style.justifyContent = 'center';
                modal.style.alignItems = 'center';
                modal.style.paddingTop = '0';
                // Reset to first tab
                switchSettingsTab('agreement');
            }
        }
        
        function closeSettingsModal() {
            const modal = document.getElementById('settings-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
        
        function switchSettingsTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.settings-tab-content');
            tabs.forEach(tab => tab.style.display = 'none');
            
            // Remove active state from all buttons
            const buttons = document.querySelectorAll('.settings-tab');
            buttons.forEach(btn => {
                btn.style.color = '#6b7280';
                btn.style.borderBottom = 'none';
                btn.style.marginBottom = '-2px';
            });
            
            // Show selected tab
            const selectedTab = document.getElementById(tabName + '-tab');
            if (selectedTab) {
                selectedTab.style.display = 'block';
            }
            
            // Mark button as active
            const activeBtn = event.target;
            if (activeBtn && activeBtn.classList.contains('settings-tab')) {
                activeBtn.style.color = '#3b82f6';
                activeBtn.style.borderBottom = '3px solid #3b82f6';
                activeBtn.style.marginBottom = '-2px';
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('settings-modal');
            if (modal && event.target === modal) {
                closeSettingsModal();
            }
        });
        
        // Close with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSettingsModal();
            }
        });
    </script>
</body>
</html>

