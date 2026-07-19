/**
 * Calamba PopDev Resource Network - Main Application Script
 */

const API_BASE = '';

// Utility: safe fetch that returns parsed JSON or a default value and shows errors
async function safeFetchJson(url, defaultValue = null) {
    try {
        const res = await fetch(url);
        if (!res.ok) {
            const text = await res.text().catch(() => '');
            showError(`Request failed ${res.status}: ${url}`);
            return defaultValue;
        }
        const json = await res.json().catch(() => null);
        return json === null ? defaultValue : json;
    } catch (err) {
        console.error('Fetch error', err);
        showError(`Network error fetching ${url}`);
        return defaultValue;
    }
}

function openEditBarangayModal(barangay) {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Barangay</h2>
                <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Barangay Name</label>
                    <input id="edit-barangay-name" type="text" value="${barangay.name || ''}">
                </div>
                <div class="form-group">
                    <label>Population</label>
                    <input id="edit-barangay-population" type="number" value="${barangay.population || ''}">
                </div>
                <div class="form-group">
                    <label>Area (km²)</label>
                    <input id="edit-barangay-area" type="text" value="${barangay.area || ''}">
                </div>
                <div class="form-group">
                    <label>Chairman</label>
                    <input id="edit-barangay-chairman" type="text" value="${barangay.chairman || ''}">
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input id="edit-barangay-contact" type="text" value="${barangay.contact || ''}">
                </div>
            </div>
            <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end; padding:15px;">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" id="save-edit-barangay-btn">Save</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('#save-edit-barangay-btn').addEventListener('click', async () => {
        const name = modal.querySelector('#edit-barangay-name').value.trim();
        const population = modal.querySelector('#edit-barangay-population').value;
        const area = modal.querySelector('#edit-barangay-area').value.trim();
        const chairman = modal.querySelector('#edit-barangay-chairman').value.trim();
        const contact = modal.querySelector('#edit-barangay-contact').value.trim();

        if (!name) {
            alert('Please enter a barangay name');
            return;
        }

        try {
            const res = await fetch(API_BASE + '/api/barangay/update/' + barangay.id, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, population, area, chairman, contact })
            });
            const json = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({})));
            if (res.ok) {
                alert('Barangay updated');
                modal.remove();
                if (typeof app.loadDataManagement === 'function') app.loadDataManagement();
            } else {
                showError(json.message || 'Failed to update barangay');
            }
        } catch (err) {
            console.error('Update error', err);
            showError('Error updating barangay');
        }
    });
}

function ensureArray(value) {
    if (Array.isArray(value)) return value;
    if (!value) return [];
    if (value.data && Array.isArray(value.data)) return value.data;
    return [];
}

function showError(msg, timeout = 5000) {
    try {
        const id = 'ui-error-toast';
        let el = document.getElementById(id);
        if (!el) {
            el = document.createElement('div');
            el.id = id;
            el.style.position = 'fixed';
            el.style.right = '16px';
            el.style.top = '16px';
            el.style.zIndex = 9999;
            document.body.appendChild(el);
        }
        const item = document.createElement('div');
        item.className = 'toast-error';
        item.style.background = '#fee2e2';
        item.style.color = '#b91c1c';
        item.style.padding = '10px 14px';
        item.style.marginTop = '8px';
        item.style.borderRadius = '6px';
        item.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
        item.textContent = msg;
        el.appendChild(item);
        setTimeout(() => item.remove(), timeout);
    } catch (e) { console.error('showError failed', e); }
}

// Theme Manager
class ThemeManager {
    constructor() {
        this.STORAGE_KEY = 'calamba-theme-preference';
        this.init();
    }

    init() {
        const savedTheme = localStorage.getItem(this.STORAGE_KEY) || 'auto';
        this.setTheme(savedTheme);
        
        // Listen for system theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (localStorage.getItem(this.STORAGE_KEY) === 'auto') {
                    this.applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }

    setTheme(theme) {
        localStorage.setItem(this.STORAGE_KEY, theme);
        
        if (theme === 'auto') {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
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
            const textColor = isDarkMode ? '#f9fafb' : '#1f2937';
            const bgColor = isDarkMode ? '#1f2937' : '#ffffff';
            const borderColor = isDarkMode ? '#374151' : '#e5e7eb';
            
            panel.style.backgroundColor = bgColor;
            panel.style.color = textColor;
            panel.style.border = `1px solid ${borderColor}`;
            
            // Update all label colors
            panel.querySelectorAll('label').forEach(label => {
                label.style.color = textColor;
            });
            
            // Update heading color
            const heading = panel.querySelector('h4');
            if (heading) {
                heading.style.color = textColor;
            }
        }
    }

    getTheme() {
        return localStorage.getItem(this.STORAGE_KEY) || 'auto';
    }

    getCurrentApplied() {
        return document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light';
    }
}

const themeManager = new ThemeManager();

class App {
    constructor() {
        this.currentPage = 'dashboard';
        this.init();
    }

    init() {
        this.attachEventListeners();
        this.setupResponsiveHandler();
        this.loadPage(this.currentPage);
    }

    setupResponsiveHandler() {
        const sidebar = document.querySelector('.sidebar');
        const layout = document.querySelector('.layout');
        
        // Initialize: ensure sidebar is expanded on desktop
        if (window.innerWidth > 768) {
            sidebar.classList.remove('collapsed');
            layout.classList.remove('sidebar-open');
            layout.classList.remove('sidebar-collapsed');
        }
        
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('collapsed');
                layout.classList.remove('sidebar-open');
                layout.classList.remove('sidebar-collapsed');
            }
        });
    }

    attachEventListeners() {
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarHide = document.getElementById('sidebar-hide');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const layout = document.querySelector('.layout');
        const sidebarShow = document.getElementById('sidebar-show');
        const coolBtn = document.getElementById('cool-btn');

        // Helper to show/hide and position reveal button on left when sidebar collapsed
        const setRevealVisible = (visible) => {
            if (!sidebarShow) return;
            if (visible) {
                sidebarShow.style.display = 'flex';
                // position on the left edge
                sidebarShow.style.left = '12px';
                sidebarShow.style.right = '';
                // on small screens adjust top
                if (window.innerWidth <= 768) {
                    sidebarShow.style.top = '12px';
                } else {
                    sidebarShow.style.top = '72px';
                }
            } else {
                sidebarShow.style.display = 'none';
            }
        };

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const isOpen = layout.classList.contains('sidebar-open');
                if (isOpen) {
                    layout.classList.remove('sidebar-open');
                    sidebar.classList.add('collapsed');
                    layout.classList.add('sidebar-collapsed');
                    document.body.classList.remove('sidebar-menu-open');
                    document.documentElement.classList.remove('sidebar-menu-open');
                } else {
                    layout.classList.add('sidebar-open');
                    sidebar.classList.remove('collapsed');
                    layout.classList.remove('sidebar-collapsed');
                    document.body.classList.add('sidebar-menu-open');
                    document.documentElement.classList.add('sidebar-menu-open');
                }
                setRevealVisible(sidebar.classList.contains('collapsed'));
            });
        }

        if (sidebarHide) {
            sidebarHide.addEventListener('click', () => {
                layout.classList.remove('sidebar-open');
                sidebar.classList.add('collapsed');
                layout.classList.add('sidebar-collapsed');
                if (overlay) overlay.style.display = 'none';
                document.body.classList.remove('sidebar-menu-open');
                document.documentElement.classList.remove('sidebar-menu-open');
                setRevealVisible(true);
            });
        }

        if (sidebarShow) {
            sidebarShow.addEventListener('click', () => {
                layout.classList.add('sidebar-open');
                sidebar.classList.remove('collapsed');
                layout.classList.remove('sidebar-collapsed');
                document.body.classList.add('sidebar-menu-open');
                document.documentElement.classList.add('sidebar-menu-open');
                setRevealVisible(false);
            });
        }

        if (overlay) {
            overlay.addEventListener('click', () => {
                layout.classList.remove('sidebar-open');
                sidebar.classList.add('collapsed');
                layout.classList.add('sidebar-collapsed');
                document.body.classList.remove('sidebar-menu-open');
                document.documentElement.classList.remove('sidebar-menu-open');
                setRevealVisible(true);
            });
        }

        // Cool button opens/closes a small panel
        if (coolBtn) {
            coolBtn.addEventListener('click', (e) => {
                let panel = document.getElementById('cool-panel');
                if (!panel) {
                        panel = document.createElement('div');
                        panel.id = 'cool-panel';
                        panel.style.position = 'absolute';
                        panel.style.display = 'none';
                        
                        const currentTheme = themeManager.getTheme();
                        const currentApplied = themeManager.getCurrentApplied();
                        
                        // Dynamic colors based on current theme
                        const isDarkMode = currentApplied === 'dark';
                        const textColor = isDarkMode ? '#f9fafb' : '#1f2937';     // Light text in dark mode, dark text in light mode
                        const bgColor = isDarkMode ? '#1f2937' : '#ffffff';       // Dark background in dark mode, white in light mode
                        const borderColor = isDarkMode ? '#374151' : '#e5e7eb';   // Dark border in dark mode
                        const hoverColor = isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
                        
                        panel.style.backgroundColor = bgColor;
                        panel.style.color = textColor;
                        panel.style.border = `1px solid ${borderColor}`;
                        panel.style.borderRadius = '10px';
                        panel.style.padding = '16px';
                        panel.style.zIndex = '1200';
                        panel.style.minWidth = '300px';
                        panel.style.boxShadow = '0 8px 24px rgba(0,0,0,0.15)';
                        
                        // Clear any existing content  
                        panel.innerHTML = `
                            <h4 style="color: ${textColor}; margin: 0 0 12px 0; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;">Theme</h4>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px; color: ${textColor}; font-size: 16px; font-weight: 500;">
                                    <input type="radio" name="theme" value="light" title="Light Theme" style="width: 18px; height: 18px; cursor: pointer;" ${currentTheme === 'light' ? 'checked' : ''}>
                                    ☀️ Light
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px; color: ${textColor}; font-size: 16px; font-weight: 500;">
                                    <input type="radio" name="theme" value="dark" title="Dark Theme" style="width: 18px; height: 18px; cursor: pointer;" ${currentTheme === 'dark' ? 'checked' : ''}>
                                    🌙 Dark
                                </label>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px; border-radius: 6px; color: ${textColor}; font-size: 16px; font-weight: 500;">
                                    <input type="radio" name="theme" value="auto" title="Auto (Follow System)" style="width: 18px; height: 18px; cursor: pointer;" ${currentTheme === 'auto' ? 'checked' : ''}>
                                    🔄 Auto
                                </label>
                            </div>
                        `;
                        
                        // Add event listeners
                        panel.querySelectorAll('input[name="theme"]').forEach(radio => {
                            radio.addEventListener('change', (e) => {
                                themeManager.setTheme(e.target.value);
                            });
                            // Also allow clicking the label to select
                            radio.parentElement.addEventListener('click', (e) => {
                                if (e.target !== radio) {
                                    radio.checked = true;
                                    themeManager.setTheme(radio.value);
                                }
                            });
                        });
                        document.body.appendChild(panel);

                        // Add theme radio button listeners
                        panel.querySelectorAll('input[name="theme"]').forEach(radio => {
                            radio.addEventListener('change', (e) => {
                                themeManager.setTheme(e.target.value);
                            });
                        });
                }

                if (panel.style.display === 'block') {
                    panel.remove(); // Remove panel so it gets recreated with correct theme colors next time
                } else {
                    // Update theme radio buttons before showing
                    const currentTheme = themeManager.getTheme();
                    const currentApplied = themeManager.getCurrentApplied();
                    
                    // Update panel colors based on current theme
                    const isDarkMode = currentApplied === 'dark';
                    const textColor = isDarkMode ? '#f9fafb' : '#1f2937';
                    const bgColor = isDarkMode ? '#1f2937' : '#ffffff';
                    const borderColor = isDarkMode ? '#374151' : '#e5e7eb';
                    
                    panel.style.backgroundColor = bgColor;
                    panel.style.color = textColor;
                    panel.style.border = `1px solid ${borderColor}`;
                    
                    // Update all label colors
                    panel.querySelectorAll('label').forEach(label => {
                        label.style.color = textColor;
                    });
                    
                    // Update heading color
                    const heading = panel.querySelector('h4');
                    if (heading) {
                        heading.style.color = textColor;
                    }
                    
                    panel.querySelectorAll('input[name="theme"]').forEach(radio => {
                        radio.checked = radio.value === currentTheme;
                    });
                    
                    // Show and position panel next to the button
                    panel.style.display = 'block';
                    // Give browser a moment to compute sizes
                    requestAnimationFrame(() => {
                        const rect = coolBtn.getBoundingClientRect();
                        const panelRect = panel.getBoundingClientRect();
                        let left = rect.right + window.scrollX - panelRect.width;
                        let top = rect.bottom + window.scrollY + 8;

                        // Ensure panel doesn't go off-screen horizontally
                        if (left + panelRect.width > window.scrollX + window.innerWidth - 8) {
                            left = window.scrollX + window.innerWidth - panelRect.width - 8;
                        }
                        if (left < window.scrollX + 8) {
                            left = window.scrollX + 8;
                        }

                        panel.style.left = left + 'px';
                        panel.style.top = top + 'px';
                    });
                }
            });

            // close panel when clicking outside
            document.addEventListener('click', (e) => {
                const panel = document.getElementById('cool-panel');
                if (!panel) return;
                const target = e.target;
                if (!panel.contains(target) && target.id !== 'cool-btn') {
                    panel.style.display = 'none';
                }
            });
        }

        // Close sidebar on overlay click
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.add('collapsed');
                layout.classList.remove('sidebar-open');
                setRevealVisible(true);
            });
        }

        if (sidebarShow) {
            sidebarShow.addEventListener('click', () => {
                sidebar.classList.remove('collapsed');
                layout.classList.remove('sidebar-open');
                layout.classList.remove('sidebar-collapsed');
                setRevealVisible(false);
            });
        }

        // Close sidebar when clicking nav item on mobile & update active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                // Update active state
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                item.classList.add('active');

                // Close sidebar on mobile
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('collapsed');
                    layout.classList.remove('sidebar-open');
                    layout.classList.add('sidebar-collapsed');
                    setRevealVisible(true);
                }
                const page = item.dataset.page;
                if (page) {
                    e.preventDefault();
                    this.navigateTo(page);
                }
            });
        });

        // Modal close buttons
        document.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.closeModal(e.target.closest('.modal'));
            });
        });

        // Modal background click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });
    }

    async navigateTo(page) {
        this.currentPage = page;
        
        // Update active nav item
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.page === page) {
                item.classList.add('active');
            }
        });

        // Load page content
        await this.loadPage(page);
    }

    async loadPage(page) {
        const content = document.querySelector('.content');
        if (!content) return;

        switch(page) {
            case 'dashboard':
                await this.loadDashboard();
                break;
            case 'data-management':
                await this.loadDataManagement();
                break;
            case 'data-import':
                await this.loadDataImport();
                break;
            case 'barangay-records':
                await this.loadBarangayRecords();
                break;
            case 'knowledge-management':
                await this.loadKnowledgeManagement();
                break;
            case 'ml-analytics':
                await this.loadMLAnalytics();
                break;
            case 'decision-support':
                await this.loadDecisionSupport();
                break;
            case 'security-governance':
                await this.loadSecurityGovernance();
                break;
            case 'account':
                await this.loadAccount();
                break;
        }
    }

    async loadDashboard() {
        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>📊 Dashboard Overview</h3>
                </div>
                <div class="card-body">
                    <div class="mt-20 mb-40">
                        <h4 style="color: #333; margin-bottom: 20px; font-size: 16px; font-weight: 600;">🎯 Quick Access Modules</h4>
                        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px;">
                            <div class="module-card" onclick="app.navigateTo('data-management')" style="cursor: pointer; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div style="font-size: 32px; margin-bottom: 10px;">📋</div>
                                <div style="font-weight: 600; font-size: 14px;">Data<br>Management</div>
                            </div>
                            <div class="module-card" onclick="app.navigateTo('barangay-records')" style="cursor: pointer; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; color: white; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div style="font-size: 32px; margin-bottom: 10px;">🏘️</div>
                                <div style="font-weight: 600; font-size: 14px;">Barangay<br>Records</div>
                            </div>
                            <div class="module-card" onclick="app.navigateTo('knowledge-management')" style="cursor: pointer; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; color: white; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div style="font-size: 32px; margin-bottom: 10px;">📚</div>
                                <div style="font-weight: 600; font-size: 14px;">Knowledge<br>Management</div>
                            </div>
                            <div class="module-card" onclick="app.navigateTo('ml-analytics')" style="cursor: pointer; padding: 20px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 12px; color: white; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div style="font-size: 32px; margin-bottom: 10px;">🤖</div>
                                <div style="font-weight: 600; font-size: 14px;">ML<br>Analytics</div>
                            </div>
                            <div class="module-card" onclick="app.navigateTo('decision-support')" style="cursor: pointer; padding: 20px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; color: white; text-align: center; transition: all 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div style="font-size: 32px; margin-bottom: 10px;">🎯</div>
                                <div style="font-weight: 600; font-size: 14px;">Decision<br>Support</div>
                            </div>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

                    <div id="dashboard-stats"></div>
                    <div class="grid mt-30">
                        <div class="chart-container">
                            <canvas id="population-by-barangay"></canvas>
                        </div>
                        <div class="chart-container">
                            <canvas id="health-coverage-chart"></canvas>
                        </div>
                    </div>

                    <div class="grid mt-30">
                        <div class="chart-container">
                            <canvas id="education-level-chart"></canvas>
                        </div>
                        <div class="chart-container">
                            <canvas id="socioeconomic-chart"></canvas>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>📋 Recent Data Updates</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="recent-updates">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Updated By</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>🎯 Key Performance Indicators</h3>
                        </div>
                        <div class="card-body" id="dashboard-kpi"></div>
                    </div>
                </div>
            </div>
        `;

        // Add hover effect to module cards
        document.querySelectorAll('.module-card').forEach(card => {
            card.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            });
            card.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
            });
        });

        await this.loadDashboardData();
    }

    async loadDashboardData() {
        try {
            // Fetch all dashboard data in parallel
            const [barangaysRes, householdsRes, individualsRes, healthRes] = await Promise.all([
                fetch(API_BASE + '/api/barangays'),
                fetch(API_BASE + '/api/households'),
                fetch(API_BASE + '/api/individuals'),
                fetch(API_BASE + '/api/audit-logs')
            ]);

            const barangaysJson = await barangaysRes.json().catch(() => null);
            const householdsJson = await householdsRes.json().catch(() => null);
            const individualsJson = await individualsRes.json().catch(() => null);
            const auditLogsJson = await healthRes.json().catch(() => null);

            const barangays = ensureArray(barangaysJson);
            const households = ensureArray(householdsJson);
            const individuals = ensureArray(individualsJson);
            const auditLogs = ensureArray(auditLogsJson);

            // Update dashboard stats
            const statsDiv = document.getElementById('dashboard-stats');
            statsDiv.innerHTML = `
                <div class="grid">
                    <div class="stat-card">
                        <h4>Total Barangays</h4>
                        <div class="stat-value">${barangays.length || 0}</div>
                        <div class="stat-change">Tracked in system</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #10b981;">
                        <h4>Total Households</h4>
                        <div class="stat-value">${households.length || 0}</div>
                        <div class="stat-change">↑ 12% from last month</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #7c3aed;">
                        <h4>Total Individuals</h4>
                        <div class="stat-value">${individuals.length || 0}</div>
                        <div class="stat-change">↑ 8% from last month</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #f59e0b;">
                        <h4>Data Completeness</h4>
                        <div class="stat-value">94.2%</div>
                        <div class="stat-change">High quality data</div>
                    </div>
                </div>
            `;

            // Render charts
            this.renderDashboardCharts(barangays, households, individuals);

            // Update recent activity
            const tbody = document.querySelector('#recent-updates tbody');
            tbody.innerHTML = auditLogs.slice(0, 10).map(log => `
                <tr>
                    <td>${new Date(log.timestamp).toLocaleDateString()}</td>
                    <td><span class="badge badge-primary">${log.action || 'Update'}</span></td>
                    <td>${log.details || 'Data updated'}</td>
                    <td>${log.email || 'System'}</td>
                </tr>
            `).join('');

            // Update KPI
            const kpiDiv = document.getElementById('dashboard-kpi');
            kpiDiv.innerHTML = `
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div>
                        <h4 style="margin-bottom: 10px;">Data Quality</h4>
                        <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 24px;">
                            <div style="background: #10b981; width: 94.2%; height: 100%;"></div>
                        </div>
                        <p style="margin-top: 8px; color: #6b7280; font-size: 13px;">94.2% - Excellent</p>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 10px;">System Health</h4>
                        <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 24px;">
                            <div style="background: #10b981; width: 99%; height: 100%;"></div>
                        </div>
                        <p style="margin-top: 8px; color: #6b7280; font-size: 13px;">99% - Optimal</p>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    renderDashboardCharts(barangays, households, individuals) {
        // Population by Barangay Chart
        const populationCtx = document.getElementById('population-by-barangay');
        if (populationCtx && barangays.length > 0) {
            new Chart(populationCtx, {
                type: 'bar',
                data: {
                    labels: barangays.slice(0, 8).map(b => b.name),
                    datasets: [{
                        label: 'Population',
                        data: barangays.slice(0, 8).map(b => b.population || 0),
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { title: { display: true, text: 'Top 8 Barangays by Population' } }
                }
            });
        }

        // Health Coverage Chart
        const healthCtx = document.getElementById('health-coverage-chart');
        if (healthCtx) {
            new Chart(healthCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Fully Vaccinated', 'Partially Vaccinated', 'Not Vaccinated'],
                    datasets: [{
                        data: [72, 18, 10],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Education Level Chart
        const educationCtx = document.getElementById('education-level-chart');
        if (educationCtx) {
            new Chart(educationCtx, {
                type: 'pie',
                data: {
                    labels: ['College', 'HS', 'Elementary', 'None'],
                    datasets: [{
                        data: [28, 45, 22, 5],
                        backgroundColor: ['#2563eb', '#7c3aed', '#10b981', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Socioeconomic Status Chart
        const socioCtx = document.getElementById('socioeconomic-chart');
        if (socioCtx) {
            new Chart(socioCtx, {
                type: 'bar',
                data: {
                    labels: ['Very Poor', 'Poor', 'Low-Income', 'Middle', 'Upper-Middle'],
                    datasets: [{
                        label: 'Household Count',
                        data: [120, 340, 450, 280, 95],
                        backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#10b981', '#2563eb']
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    async loadDataManagement() {
        const content = document.querySelector('.content');
        
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>Data Management</h3>
                </div>
                <div class="card-body">
                    <div class="grid">
                        <div class="stat-card">
                            <h4>Total Barangays</h4>
                            <div class="stat-value" id="barangay-count">0</div>
                            <div class="stat-change">↑ 2% from last month</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #7c3aed;">
                            <h4>Total Households</h4>
                            <div class="stat-value" id="household-count">0</div>
                            <div class="stat-change">↑ 5% from last month</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #10b981;">
                            <h4>Total Individuals</h4>
                            <div class="stat-value" id="individual-count">0</div>
                            <div class="stat-change">↑ 3% from last month</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #f59e0b;">
                            <h4>Data Quality Score</h4>
                            <div class="stat-value" id="quality-score">95.5%</div>
                            <div class="stat-change">↑ 2% from last month</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Barangay Records</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary mb-20" onclick="openAddBarangayModal()">Add Barangay</button>
                            <table class="table" id="barangay-table">
                                <thead>
                                    <tr>
                                        <th>Barangay Name</th>
                                        <th>Population</th>
                                        <th>Area (km²)</th>
                                        <th>Chairman</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Households by Barangay</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="household-table">
                                <thead>
                                    <tr>
                                        <th>Household ID</th>
                                        <th>Head of Household</th>
                                        <th>Address</th>
                                        <th>Members</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Individual Records</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="individual-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Health Status</th>
                                        <th>Education Level</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Load data
        await this.fetchBarangays();
        await this.fetchHouseholds();
        await this.fetchIndividuals();
    }

    async loadDataImport() {
        const content = document.querySelector('.content');
        
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>📤 Data Import - Bulk Upload</h3>
                </div>
                <div class="card-body">
                    <div id="import-stats"></div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Upload Excel/CSV File</h3>
                        </div>
                        <div class="card-body">
                            <div id="upload-message"></div>

                            <div class="form-group">
                                <label for="barangay-select">Select Barangay *</label>
                                <select id="barangay-select">
                                    <option value="">-- Select Barangay --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="excel-file">Select Excel/CSV File *</label>
                                <input type="file" id="excel-file" accept=".xlsx,.xls,.csv" required>
                                <small style="color: #6b7280;">Supported formats: Excel (.xlsx, .xls), CSV</small>
                            </div>

                            <div id="file-preview"></div>
                            <div id="csv-preview"></div>

                            <div class="form-group mt-20">
                                <button class="btn btn-primary" id="upload-btn">Upload & Process Data</button>
                                <button class="btn btn-outline" id="download-template" style="margin-left: 10px;">Download Template</button>
                            </div>

                            <div class="alert alert-info mt-20">
                                <strong>Required Columns in Excel:</strong>
                                <ul style="margin: 10px 0 0 0;">
                                    <li><strong>name</strong> - Full name of household head</li>
                                    <li><strong>weight</strong> - Weight in kg</li>
                                    <li><strong>barangay</strong> - Barangay name</li>
                                    <li><strong>address</strong> - Full address</li>
                                    <li><strong>salary</strong> - Family monthly salary</li>
                                    <li><strong>family_members</strong> - Number of family members</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Import History</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="import-history">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Barangay</th>
                                        <th>Total Records</th>
                                        <th>Processed</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Load barangays for select (defensive)
        const rawBarangays = await safeFetchJson(API_BASE + '/api/barangays', []);
        const barangays = ensureArray(rawBarangays);
        const select = document.getElementById('barangay-select');
        select.innerHTML = '<option value="">-- Select Barangay --</option>' + 
            barangays.map(b => `<option value="${b.id}">${b.name}</option>`).join('');

        // Initialize data import module
        const script = document.createElement('script');
        script.src = API_BASE + '/js/modules/dataImport.js';
        document.body.appendChild(script);
    }

    async fetchBarangays() {
        try {
            const rawBarangays = await safeFetchJson(API_BASE + '/api/barangays', []);
            const barangays = ensureArray(rawBarangays);

            document.getElementById('barangay-count').textContent = barangays.length || 0;

            const tbody = document.querySelector('#barangay-table tbody');
            tbody.innerHTML = barangays.map(b => `
                <tr>
                    <td>${b.name || ''}</td>
                    <td>${(b.population && Number(b.population)).toLocaleString ? Number(b.population).toLocaleString() : (b.population || 0)}</td>
                    <td>${b.area || ''}</td>
                    <td>${b.chairman || ''}</td>
                    <td>${b.contact || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline edit-barangay" data-id="${b.id}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-barangay" data-id="${b.id}">Delete</button>
                    </td>
                </tr>
            `).join('');

            // Attach handlers for edit/delete
            tbody.querySelectorAll('.edit-barangay').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = btn.dataset.id;
                    const barangay = barangays.find(x => String(x.id) === String(id));
                    if (!barangay) return showError('Barangay not found');
                    if (typeof openEditBarangayModal === 'function') {
                        openEditBarangayModal(barangay);
                    }
                });
            });

            tbody.querySelectorAll('.delete-barangay').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const id = btn.dataset.id;
                    if (!confirm('Are you sure you want to delete this barangay?')) return;
                    try {
                        const res = await fetch(API_BASE + '/api/barangay/delete/' + id, { method: 'DELETE' });
                        if (res.ok) {
                            alert('Barangay deleted');
                            if (typeof app.loadDataManagement === 'function') app.loadDataManagement();
                        } else {
                            const json = await res.json().catch(()=>({}));
                            showError(json.message || 'Failed to delete barangay');
                        }
                    } catch (err) {
                        console.error('Delete error', err);
                        showError('Error deleting barangay');
                    }
                });
            });
        } catch (error) {
            console.error('Error fetching barangays:', error);
            showError('Unable to load barangays');
        }
    }

    async fetchHouseholds() {
        try {
            const rawHouseholds = await safeFetchJson(API_BASE + '/api/households', []);
            const households = ensureArray(rawHouseholds);

            document.getElementById('household-count').textContent = households.length || 0;

            const tbody = document.querySelector('#household-table tbody');
            tbody.innerHTML = households.map(h => `
                <tr>
                    <td>${h.id || ''}</td>
                    <td>${h.household_head || ''}</td>
                    <td>${h.address || ''}</td>
                    <td>${h.member_count || 0}</td>
                    <td><span class="badge badge-success">${h.socioeconomic_status || ''}</span></td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error fetching households:', error);
            showError('Unable to load households');
        }
    }

    async fetchIndividuals() {
        try {
            const rawIndividuals = await safeFetchJson(API_BASE + '/api/individuals', []);
            const individuals = ensureArray(rawIndividuals);

            document.getElementById('individual-count').textContent = individuals.length || 0;

            const tbody = document.querySelector('#individual-table tbody');
            tbody.innerHTML = individuals.map(i => `
                <tr>
                    <td>${i.id || ''}</td>
                    <td>${(i.first_name || '') + ' ' + (i.last_name || '')}</td>
                    <td>${i.age || ''}</td>
                    <td>${i.gender || ''}</td>
                    <td><span class="badge badge-${(i.health_status === 'Healthy') ? 'success' : 'warning'}">${i.health_status || ''}</span></td>
                    <td>${i.education_level || ''}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error fetching individuals:', error);
            showError('Unable to load individuals');
        }
    }

    async loadBarangayRecords() {
        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>Barangay Health Records</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Select Barangay:</label>
                        <select id="barangay-select" onchange="app.updateHealthMetrics(this.value)">
                            <option value="">-- Select a Barangay --</option>
                        </select>
                    </div>

                    <div class="grid mt-30">
                        <div class="chart-container">
                            <canvas id="immunization-chart"></canvas>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Maternal Mortality Metrics</h3>
                        </div>
                        <div class="carousel">
                            <div class="carousel-slide active">
                                <h3>Maternal Mortality Rate</h3>
                                <div class="metric-value" id="mmr-value">--</div>
                                <p>Per 100,000 live births</p>
                            </div>
                            <div class="carousel-slide">
                                <h3>Infant Mortality Rate</h3>
                                <div class="metric-value" id="imr-value">--</div>
                                <p>Per 1,000 live births</p>
                            </div>
                            <div class="carousel-slide">
                                <h3>Under-5 Mortality Rate</h3>
                                <div class="metric-value" id="u5mr-value">--</div>
                                <p>Per 1,000 live births</p>
                            </div>
                            <div class="carousel-controls">
                                <button class="carousel-btn" onclick="app.prevSlide()">❮</button>
                                <button class="carousel-btn" onclick="app.nextSlide()">❯</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid mt-30">
                        <div class="chart-container">
                            <canvas id="malnutrition-chart"></canvas>
                        </div>
                        <div class="chart-container">
                            <canvas id="water-sanitation-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.loadBarangaySelect();
    }

    async loadBarangaySelect() {
        try {
            const rawBarangays = await safeFetchJson(API_BASE + '/api/barangays', []);
            const barangays = ensureArray(rawBarangays);
            const select = document.getElementById('barangay-select');
            select.innerHTML = '<option value="">-- Select a Barangay --</option>' + 
                barangays.map(b => `<option value="${b.id}">${b.name}</option>`).join('');
        } catch (error) {
            console.error('Error loading barangays:', error);
            showError('Unable to load barangays');
        }
    }

    async updateHealthMetrics(barangayId) {
        if (!barangayId) return;

        try {
            const [healthRes, malRes, waterRes] = await Promise.all([
                fetch(`${API_BASE}/api/health-metrics/${barangayId}`),
                fetch(`${API_BASE}/api/malnutrition-data/${barangayId}`),
                fetch(`${API_BASE}/api/water-sanitation/${barangayId}`)
            ]);

            const health = await healthRes.json();
            const malnutrition = await malRes.json();
            const water = await waterRes.json();

            // Update carousel values
            document.getElementById('mmr-value').textContent = (health.maternal_mortality_rate || 0).toFixed(1);
            document.getElementById('imr-value').textContent = (health.infant_mortality_rate || 0).toFixed(1);
            document.getElementById('u5mr-value').textContent = (health.under5_mortality_rate || 0).toFixed(1);

            // Update charts
            this.updateCharts(health, malnutrition, water);
        } catch (error) {
            console.error('Error updating health metrics:', error);
        }
    }

    updateCharts(health, malnutrition, water) {
        // Update immunization chart
        const immunizationCtx = document.getElementById('immunization-chart');
        if (immunizationCtx) {
            new Chart(immunizationCtx, {
                type: 'bar',
                data: {
                    labels: ['Immunization Coverage'],
                    datasets: [{
                        label: 'Coverage %',
                        data: [health.immunization_coverage || 0],
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100 }
                    }
                }
            });
        }

        // Update malnutrition chart
        const malnutritionCtx = document.getElementById('malnutrition-chart');
        if (malnutritionCtx) {
            new Chart(malnutritionCtx, {
                type: 'bar',
                data: {
                    labels: ['Wasting', 'Stunting', 'Underweight'],
                    datasets: [{
                        label: 'Prevalence %',
                        data: [
                            malnutrition.wasting || 0,
                            malnutrition.stunting || 0,
                            malnutrition.underweight || 0
                        ],
                        backgroundColor: ['#ef4444', '#f59e0b', '#eab308']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // Update water & sanitation chart
        const waterCtx = document.getElementById('water-sanitation-chart');
        if (waterCtx) {
            new Chart(waterCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Water Access', 'No Water Access'],
                    datasets: [{
                        data: [
                            water.water_access_percent || 0,
                            100 - (water.water_access_percent || 0)
                        ],
                        backgroundColor: ['#10b981', '#e5e7eb']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    async loadKnowledgeManagement() {
        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>Knowledge Management Module</h3>
                </div>
                <div class="card-body">
                    <div class="grid mb-30">
                        <div class="stat-card">
                            <h4>Total Documents</h4>
                            <div class="stat-value" id="doc-count">1,247</div>
                            <div class="stat-change">↑ 43 this month</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #10b981;">
                            <h4>Best Practices</h4>
                            <div class="stat-value">156</div>
                            <div class="stat-change">From 54 barangays</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #7c3aed;">
                            <h4>Contributors</h4>
                            <div class="stat-value">234</div>
                            <div class="stat-change">Active contributors</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #f59e0b;">
                            <h4>Monthly Views</h4>
                            <div class="stat-value">8,956</div>
                            <div class="stat-change">↑ 12% from last month</div>
                        </div>
                    </div>

                    <button class="btn btn-primary mb-20" onclick="openDocumentUploadModal()">Upload Document</button>

                    <div class="grid mt-30" id="categories-grid"></div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>Recent Documents</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="documents-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Uploaded By</th>
                                        <th>Date</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.loadDocuments();
    }

    async loadDocuments() {
        try {
            const rawDocuments = await safeFetchJson(API_BASE + '/api/documents', []);
            const rawCategories = await safeFetchJson(API_BASE + '/api/categories', []);

            const documents = ensureArray(rawDocuments);
            const categories = ensureArray(rawCategories);

            // Load categories
            const grid = document.getElementById('categories-grid');
            grid.innerHTML = categories.map(cat => `
                <div class="stat-card">
                    <h4>${cat.category}</h4>
                    <div class="stat-value">${cat.count}</div>
                    <div class="stat-change">Documents</div>
                </div>
            `).join('');

            // Load documents
            const tbody = document.querySelector('#documents-table tbody');
            tbody.innerHTML = documents.slice(0, 10).map(doc => `
                <tr>
                    <td>${doc.title}</td>
                    <td><span class="badge badge-primary">${doc.category}</span></td>
                    <td>${doc.uploaded_by}</td>
                    <td>${new Date(doc.created_at).toLocaleDateString()}</td>
                    <td>${doc.views}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error loading documents:', error);
            showError('Unable to load documents');
        }
    }

    async loadMLAnalytics() {
        console.log('=== loadMLAnalytics STARTED ===');
        const content = document.querySelector('.content');
        console.log('Content element found:', content);
        
        // FIRST: Show yellow banner to confirm page is loading
        if (content) {
            content.innerHTML = '<div style="padding: 20px; background: #ffff00; color: #000; font-weight: bold; margin-bottom: 20px; border-radius: 8px; text-align: center; font-size: 18px; border: 3px solid red;">✓ ML ANALYTICS PAGE LOADED - BUTTONS SHOULD APPEAR BELOW AND AT TOP</div>';
        }
        
        // REMOVE any existing top button
        const existingBtn = document.getElementById('top-train-button');
        if (existingBtn) existingBtn.remove();
        
        try {
            // ADD NEW FIXED BUTTON AT TOP
            const topBtn = document.createElement('button');
            topBtn.id = 'top-train-button';
            topBtn.innerHTML = '🚀 TRAIN MODEL - CLICK ME';
            topBtn.style.position = 'fixed';
            topBtn.style.top = '70px';
            topBtn.style.left = '50%';
            topBtn.style.transform = 'translateX(-50%)';
            topBtn.style.padding = '20px 50px';
            topBtn.style.background = '#ff6b35';
            topBtn.style.color = 'white';
            topBtn.style.border = '3px solid yellow';
            topBtn.style.borderRadius = '10px';
            topBtn.style.fontWeight = '900';
            topBtn.style.cursor = 'pointer';
            topBtn.style.fontSize = '18px';
            topBtn.style.zIndex = '10000';
            topBtn.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.8)';
            topBtn.style.transition = 'all 0.3s ease';
            
            document.body.appendChild(topBtn);
            console.log('✓ Top button added to page');
            
            topBtn.addEventListener('click', () => {
                console.log('Top button clicked!');
                this.trainMLModel();
            });
            
            topBtn.addEventListener('mouseover', function() {
                this.style.background = '#ff5c3f';
                this.style.transform = 'translateX(-50%) scale(1.1)';
                this.style.boxShadow = '0 0 30px rgba(255, 107, 53, 1)';
            });
            
            topBtn.addEventListener('mouseout', function() {
                this.style.background = '#ff6b35';
                this.style.transform = 'translateX(-50%) scale(1)';
                this.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.8)';
            });
        } catch (err) {
            console.error('✗ Error creating top button:', err);
        }
        
        content.innerHTML = `
            <div style="padding: 20px; background: #ffff00; color: #000; font-weight: bold; margin-bottom: 20px; border-radius: 8px; text-align: center; font-size: 18px; border: 3px solid red;">✓ ML ANALYTICS PAGE LOADED - BUTTONS BELOW</div>
            
            <div style="background: #ff0000; padding: 40px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
                <h2 style="color: white; margin: 0 0 20px 0; font-size: 28px;">🚀 TRAIN MODEL</h2>
                <button onclick="alert('Button clicked!'); app.trainMLModel();" style="padding: 20px 40px; background: white; color: #ff0000; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 18px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);">
                    ✨ CLICK HERE TO TRAIN MODEL ✨
                </button>
            </div>
            
            <!-- Model Training Section -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <h2 style="color: white; margin: 0 0 25px 0; font-size: 22px; font-weight: 700;">🧠 Train ML Model by Barangay</h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="color: white; display: block; margin-bottom: 10px; font-weight: 600; font-size: 14px;">Select Barangay</label>
                        <select id="ml-barangay-select" style="width: 100%; padding: 12px; border-radius: 6px; border: none; font-size: 14px;">
                            <option value="">-- Choose a Barangay --</option>
                        </select>
                    </div>
                    
                    <div>
                        <label style="color: white; display: block; margin-bottom: 10px; font-weight: 600; font-size: 14px;">Select Algorithm</label>
                        <select id="ml-algorithm-select" style="width: 100%; padding: 12px; border-radius: 6px; border: none; font-size: 14px;">
                            <option value="regression">Regression Analysis</option>
                            <option value="clustering">K-Means Clustering</option>
                            <option value="random-forest">Random Forest</option>
                            <option value="decision-tree">Decision Tree</option>
                        </select>
                    </div>
                    
                    <div style="display: flex; align-items: flex-end;">
                        <button id="train-button" style="width: 100%; padding: 12px 24px; background: white; color: #333; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 16px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); transition: all 0.3s ease;">
                            🚀 Train
                        </button>
                    </div>
                </div>

                <!-- Training Status -->
                <div id="ml-training-status" style="margin-top: 20px; display: none;">
                    <div style="padding: 15px; background: rgba(255,255,255,0.1); border-radius: 6px; border-left: 4px solid white;">
                        <p style="margin: 0; font-weight: 600; color: white;">Training in progress...</p>
                        <div style="margin-top: 10px; width: 100%; background: rgba(255,255,255,0.2); height: 6px; border-radius: 3px; overflow: hidden;">
                            <div id="ml-training-progress" style="width: 0%; height: 100%; background: white; transition: width 0.3s;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Overview Card -->
            <div class="card mb-30">
                <div class="card-header">
                    <h3>🤖 ML Analytics Dashboard</h3>
                </div>
                <div class="card-body">

                    <!-- Training Results -->
                    <div id="ml-training-results" style="display: none;">
                        <div class="card mb-30">
                            <div class="card-header">
                                <h3>📊 Training Results</h3>
                            </div>
                            <div class="card-body">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
                                    <div class="stat-card">
                                        <h4>Model Accuracy</h4>
                                        <div class="stat-value" id="ml-accuracy">0%</div>
                                        <div id="ml-accuracy-status" class="stat-change" style="color: #ef4444;">--</div>
                                    </div>
                                    <div class="stat-card">
                                        <h4>Precision Score</h4>
                                        <div class="stat-value" id="ml-precision">0%</div>
                                        <div class="stat-change">Prediction reliability</div>
                                    </div>
                                    <div class="stat-card">
                                        <h4>F1 Score</h4>
                                        <div class="stat-value" id="ml-f1-score">0</div>
                                        <div class="stat-change">Model balance</div>
                                    </div>
                                    <div class="stat-card">
                                        <h4>Training Samples</h4>
                                        <div class="stat-value" id="ml-samples">0</div>
                                        <div class="stat-change">Data points used</div>
                                    </div>
                                </div>

                                <div class="grid">
                                    <div class="chart-container">
                                        <canvas id="ml-training-progress-chart"></canvas>
                                    </div>
                                    <div class="chart-container">
                                        <canvas id="ml-confusion-matrix"></canvas>
                                    </div>
                                </div>

                                <div class="grid mt-30">
                                    <div class="chart-container large">
                                        <canvas id="ml-feature-correlation"></canvas>
                                    </div>
                                </div>

                                <div class="card mt-30">
                                    <div class="card-header">
                                        <h3>Model Metrics Details</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table" id="ml-metrics-table">
                                            <thead>
                                                <tr>
                                                    <th>Metric</th>
                                                    <th>Value</th>
                                                    <th>Status</th>
                                                    <th>Interpretation</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Analytics -->
                    <div class="card">
                        <div class="card-header">
                            <h3>📈 Overall Analytics</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid mt-30">
                                <div class="chart-container large">
                                    <canvas id="risk-predictions-chart"></canvas>
                                </div>
                                <div class="chart-container large">
                                    <canvas id="population-forecast-chart"></canvas>
                                </div>
                            </div>

                            <div class="grid mt-30">
                                <div class="chart-container large">
                                    <canvas id="clustering-chart"></canvas>
                                </div>
                                <div class="chart-container large">
                                    <canvas id="feature-importance-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        await this.loadBarangaysForML();
        
        // Attach button click handler
        const trainBtn = document.getElementById('train-button');
        if (trainBtn) {
            trainBtn.addEventListener('click', () => this.trainMLModel());
            trainBtn.addEventListener('mouseover', function() {
                this.style.background = '#f0f0f0';
                this.style.boxShadow = '0 6px 16px rgba(0, 0, 0, 0.2)';
                this.style.transform = 'translateY(-2px)';
            });
            trainBtn.addEventListener('mouseout', function() {
                this.style.background = 'white';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
                this.style.transform = 'translateY(0)';
            });
        }
        
        this.loadMLData();
    }

    async loadBarangaysForML() {
        try {
            const rawBarangays = await safeFetchJson(API_BASE + '/api/barangays', []);
            const barangays = ensureArray(rawBarangays);

            const select = document.getElementById('ml-barangay-select');
            if (select) {
                barangays.forEach(b => {
                    const option = document.createElement('option');
                    option.value = b.id;
                    option.textContent = b.name || '';
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading barangays for ML:', error);
            showError('Unable to load barangays for ML');
        }
    }

    async trainMLModel() {
        const barangaySelect = document.getElementById('ml-barangay-select');
        const algorithmSelect = document.getElementById('ml-algorithm-select');
        const barangayId = barangaySelect.value;
        const algorithm = algorithmSelect.value;
        const barangayName = barangaySelect.options[barangaySelect.selectedIndex].text;

        if (!barangayId) {
            alert('Please select a barangay');
            return;
        }

        // Show training status
        const statusDiv = document.getElementById('ml-training-status');
        const progressBar = document.getElementById('ml-training-progress');
        statusDiv.style.display = 'block';
        // Start lightweight progress indicator while waiting for server
        let progress = 10;
        progressBar.style.width = progress + '%';
        const progressInterval = setInterval(() => {
            progress = Math.min(90, progress + Math.random() * 12);
            progressBar.style.width = Math.round(progress) + '%';
        }, 600);

        try {
            const resp = await fetch(API_BASE + '/api/ml/train', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ barangay_id: barangayId, algorithm: algorithm })
            });

            const json = await resp.json();
            clearInterval(progressInterval);

            if (!resp.ok) {
                progressBar.style.width = '0%';
                statusDiv.style.display = 'none';
                alert('Training error: ' + (json.error || 'Unknown error'));
                return;
            }

            // Complete progress and show results
            progressBar.style.width = '100%';
            await new Promise(r => setTimeout(r, 350));
            statusDiv.style.display = 'none';

            // Backend returns fields: accuracy, precision, f1_score, samples, training_time, recommendations
            const results = {
                accuracy: (json.accuracy || 0).toFixed ? (json.accuracy).toFixed(2) : json.accuracy,
                precision: (json.precision || 0).toFixed ? (json.precision).toFixed(2) : json.precision,
                f1_score: json.f1_score || json.f1 || 0,
                samples: json.samples || 0,
                algorithm: json.algorithm || algorithm,
                training_time: json.training_time || 0,
                recommendations: json.recommendations || [],
                barangayId: barangayId,
                barangayName: barangayName
            };

            this.displayMLResults(results);
            
            // Show smart recommendations popup
            await new Promise(r => setTimeout(r, 500));
            this.showRecommendationsModal(results);
        } catch (error) {
            clearInterval(progressInterval);
            console.error('Error training model:', error);
            statusDiv.style.display = 'none';
            alert('Error training model: ' + (error.message || 'Unknown'));
        }
    }

    generateSmartRecommendations(results) {
        const accuracy = parseFloat(results.accuracy);
        const precision = parseFloat(results.precision);
        const f1 = parseFloat(results.f1_score);
        const algorithm = results.algorithm.toLowerCase();
        const barangay = results.barangayName;

        const recommendations = [];

        // Algorithm-specific insights
        if (algorithm === 'regression') {
            recommendations.push({
                type: 'algorithm',
                title: '📈 Regression Analysis',
                description: 'Great for predicting continuous values like population growth, health metrics trends, or income forecasts.',
                details: algorithm === 'regression' ? `This algorithm works best for ${barangay} to forecast numerical outcomes based on historical patterns.` : ''
            });

            if (accuracy > 80) {
                recommendations.push({
                    type: 'action',
                    title: '✅ Model Performance Excellent',
                    description: `Your regression model achieved ${accuracy}% accuracy. This is excellent for predictive forecasting.`,
                    details: 'You can confidently use this model for strategic planning and resource allocation decisions.'
                });
            } else if (accuracy > 65) {
                recommendations.push({
                    type: 'action',
                    title: '⚠️ Moderate Model Performance',
                    description: `Model accuracy is ${accuracy}%. Consider collecting more data or adjusting features.`,
                    details: 'Add more historical data points or include additional demographic/health variables to improve predictions.'
                });
            }
        } else if (algorithm === 'clustering') {
            recommendations.push({
                type: 'algorithm',
                title: '🎯 K-Means Clustering',
                description: 'Perfect for grouping similar households, identifying community segments, or detecting vulnerable populations.',
                details: `Clustering ${barangay} data will help identify groups with similar characteristics for targeted interventions.`
            });

            recommendations.push({
                type: 'action',
                title: '💡 Community Segmentation',
                description: `${accuracy}% confidence in identified clusters.`,
                details: 'Use these segments to tailor health programs, education initiatives, and welfare services to specific community needs.'
            });
        } else if (algorithm === 'random-forest') {
            recommendations.push({
                type: 'algorithm',
                title: '🌳 Random Forest Classification',
                description: 'Excellent for complex decision-making like identifying at-risk households or health status predictions.',
                details: `Random Forest provides robust predictions for ${barangay} with ${accuracy}% accuracy across multiple features.`
            });

            recommendations.push({
                type: 'action',
                title: '🎯 Multi-Factor Analysis',
                description: 'This model considers multiple factors simultaneously for nuanced predictions.',
                details: 'Use for identifying priority households for interventions or predicting program outcomes.'
            });
        } else if (algorithm === 'decision-tree') {
            recommendations.push({
                type: 'algorithm',
                title: '🌲 Decision Tree Analysis',
                description: 'Great for creating interpretable decision rules and understanding key factors affecting outcomes.',
                details: `Decision Tree model for ${barangay} achieves ${accuracy}% accuracy with clear decision pathways.`
            });

            recommendations.push({
                type: 'action',
                title: '📋 Interpretable Rules',
                description: 'Decision trees provide transparent, easy-to-understand rules.',
                details: 'Share these decision rules with program implementers and community leaders for consistent decision-making.'
            });
        }

        // Performance-based recommendations
        if (accuracy > 85 && precision > 85) {
            recommendations.push({
                type: 'performance',
                title: '🏆 Outstanding Model Quality',
                description: `Both accuracy (${accuracy}%) and precision (${precision}%) are excellent.`,
                details: 'Deploy this model for decision support. Update it quarterly with new data to maintain performance.'
            });
        } else if (accuracy < 65) {
            recommendations.push({
                type: 'improvement',
                title: '🔧 Improve Model Performance',
                description: `Current accuracy is ${accuracy}%. Consider these improvements:`,
                details: '1) Collect more training data (currently using ' + results.samples + ' samples)\n2) Feature engineering - add more relevant variables\n3) Try different algorithm parameters\n4) Remove outliers or data quality issues'
            });
        }

        // Data quantity recommendations
        if (results.samples < 100) {
            recommendations.push({
                type: 'data',
                title: '📊 Limited Training Data',
                description: `Using only ${results.samples} samples. Larger datasets improve model reliability.`,
                details: 'Aim to collect at least 300+ records per barangay for robust models. Continue data collection activities.'
            });
        } else if (results.samples > 1000) {
            recommendations.push({
                type: 'data',
                title: '✅ Excellent Data Volume',
                description: `Strong dataset with ${results.samples} records. This supports model reliability.`,
                details: 'Your data collection efforts are paying off. The model has sufficient examples to learn patterns.'
            });
        }

        // Next steps
        recommendations.push({
            type: 'next',
            title: '📋 Recommended Next Steps',
            description: '1. Review the generated metrics and visualizations\n2. Share findings with barangay officials\n3. Implement targeted interventions based on insights\n4. Track outcomes and re-train model in 3 months',
            details: 'Regular model updates ensure predictions stay relevant as community conditions change.'
        });

        return recommendations;
    }

    showRecommendationsModal(results) {
        const recommendations = this.generateSmartRecommendations(results);

        const modal = document.createElement('div');
        modal.className = 'modal active';
        modal.id = 'recommendations-modal';
        modal.style.zIndex = '2000';

        let recommendationsHtml = recommendations.map((rec, idx) => {
            let bgColor = '#ffffff';
            let iconBg = '#f3f4f6';
            
            if (rec.type === 'algorithm') {
                bgColor = '#f0f9ff';
                iconBg = '#e0f2fe';
            } else if (rec.type === 'action') {
                bgColor = '#f0fdf4';
                iconBg = '#dcfce7';
            } else if (rec.type === 'performance') {
                bgColor = '#fef3c7';
                iconBg = '#fef08a';
            } else if (rec.type === 'improvement') {
                bgColor = '#fef2f2';
                iconBg = '#fee2e2';
            } else if (rec.type === 'data') {
                bgColor = '#f5f3ff';
                iconBg = '#ede9fe';
            } else if (rec.type === 'next') {
                bgColor = '#f3f4f6';
                iconBg = '#e5e7eb';
            }

            const descriptionLines = rec.description.split('\n').map(line => `<p style="margin: 8px 0; font-size: 14px;">${line}</p>`).join('');
            const detailsLines = rec.details.split('\n').map(line => `<p style="margin: 5px 0; font-size: 13px; color: #6b7280; line-height: 1.5;">${line}</p>`).join('');

            return `
                <div style="background: ${bgColor}; border-left: 4px solid ${rec.type === 'performance' ? '#f59e0b' : rec.type === 'improvement' ? '#ef4444' : rec.type === 'data' ? '#7c3aed' : rec.type === 'next' ? '#6b7280' : '#2563eb'}; padding: 18px; border-radius: 8px; margin-bottom: 15px;">
                    <h4 style="margin: 0 0 10px 0; color: #1f2937; font-size: 16px;">${rec.title}</h4>
                    ${descriptionLines}
                    ${detailsLines ? `<div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,0.08);">${detailsLines}</div>` : ''}
                </div>
            `;
        }).join('');

        const algorithmNames = {
            'regression': 'Regression Analysis',
            'clustering': 'K-Means Clustering',
            'random-forest': 'Random Forest',
            'decision-tree': 'Decision Tree'
        };

        modal.innerHTML = `
            <div class="modal-content" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h2 style="margin: 0; font-size: 22px;">💡 Smart Recommendations</h2>
                    <button class="modal-close" onclick="document.getElementById('recommendations-modal').remove()" style="color: white; font-size: 28px; background: rgba(255,255,255,0.2);">&times;</button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div style="background: #f0f9ff; border-left: 4px solid #06b6d4; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p style="margin: 0; font-weight: 600; color: #0369a1;">📍 Barangay: <strong>${results.barangayName}</strong></p>
                        <p style="margin: 8px 0 0 0; color: #0891b2;">Algorithm: ${algorithmNames[results.algorithm] || results.algorithm}</p>
                        <p style="margin: 4px 0 0 0; color: #0891b2;">Accuracy: <strong>${results.accuracy}%</strong> | Precision: <strong>${results.precision}%</strong></p>
                    </div>

                    <div style="background: white; padding: 20px; border-radius: 8px;">
                        <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 18px;">📊 Key Insights & Recommendations</h3>
                        ${recommendationsHtml}
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; gap: 10px; justify-content: flex-end;">
                    <button class="btn btn-outline" onclick="document.getElementById('recommendations-modal').remove()">Close</button>
                    <button class="btn btn-primary" onclick="app.exportRecommendations('${results.barangayName}', '${results.algorithm}', ${results.accuracy}, ${results.precision})">📥 Export Report</button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    exportRecommendations(barangayName, algorithm, accuracy, precision) {
        const timestamp = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        const content = `
ML ANALYTICS TRAINING REPORT
Generated: ${timestamp}

BARANGAY: ${barangayName}
ALGORITHM: ${algorithm}
ACCURACY: ${accuracy}%
PRECISION: ${precision}%

SUMMARY:
This report contains the results of machine learning model training on ${barangayName} data using the ${algorithm} algorithm.

RECOMMENDATIONS:
1. Review model performance metrics
2. Share findings with stakeholders
3. Implement data-driven interventions
4. Track outcomes and re-evaluate quarterly

For detailed analysis, please refer to the ML Analytics dashboard.
        `;

        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `ml-report-${barangayName}-${new Date().toISOString().split('T')[0]}.txt`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    }

    displayMLResults(results) {
        const resultsDiv = document.getElementById('ml-training-results');
        resultsDiv.style.display = 'block';

        // Update metrics
        document.getElementById('ml-accuracy').textContent = results.accuracy + '%';
        document.getElementById('ml-precision').textContent = results.precision + '%';
        document.getElementById('ml-f1-score').textContent = results.f1_score;
        document.getElementById('ml-samples').textContent = results.samples;

        // Update accuracy status
        const accuracyStatus = document.getElementById('ml-accuracy-status');
        let statusText = '';
        let statusColor = '';
        if (results.accuracy > 85) {
            statusText = '✅ Excellent Model Performance';
            statusColor = '#10b981';
        } else if (results.accuracy > 75) {
            statusText = '⚠️ Good Model Performance';
            statusColor = '#f59e0b';
        } else if (results.accuracy > 65) {
            statusText = '⚡ Fair Model Performance';
            statusColor = '#f97316';
        } else {
            statusText = '❌ Poor Model Performance';
            statusColor = '#ef4444';
        }
        accuracyStatus.textContent = statusText;
        accuracyStatus.style.color = statusColor;

        // Render charts
        this.renderMLResultsCharts(results);

        // Update metrics table
        this.updateMLMetricsTable(results);

        // Scroll to results
        resultsDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    renderMLResultsCharts(results) {
        // Training Progress Over Epochs
        const progressCtx = document.getElementById('ml-training-progress-chart');
        if (progressCtx) {
            // Generate simulated training progress data
            const epochs = Array.from({length: 50}, (_, i) => i + 1);
            const losses = epochs.map((e, i) => {
                const baseDecay = Math.exp(-i * 0.05);
                return 1 - (results.accuracy / 100) + (0.5 * baseDecay) + Math.random() * 0.05;
            });

            new Chart(progressCtx, {
                type: 'line',
                data: {
                    labels: epochs,
                    datasets: [{
                        label: 'Training Loss',
                        data: losses,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: { display: true, text: 'Training Loss Over Epochs' },
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1,
                            title: { display: true, text: 'Loss Value' }
                        },
                        x: {
                            title: { display: true, text: 'Epoch' }
                        }
                    }
                }
            });
        }

        // Confusion Matrix
        const confusionCtx = document.getElementById('ml-confusion-matrix');
        if (confusionCtx) {
            const accuracy = parseFloat(results.accuracy);
            const truePositives = Math.round((accuracy / 100) * 500);
            const trueNegatives = Math.round((accuracy / 100) * 500);
            const falsePositives = Math.round((100 - accuracy) / 100 * 250);
            const falseNegatives = Math.round((100 - accuracy) / 100 * 250);

            new Chart(confusionCtx, {
                type: 'bubble',
                data: {
                    datasets: [
                        {
                            label: 'True Positives',
                            data: [{x: 0.25, y: 0.75, r: Math.sqrt(truePositives)}],
                            backgroundColor: '#10b981'
                        },
                        {
                            label: 'False Positives',
                            data: [{x: 0.75, y: 0.75, r: Math.sqrt(falsePositives)}],
                            backgroundColor: '#ef4444'
                        },
                        {
                            label: 'True Negatives',
                            data: [{x: 0.25, y: 0.25, r: Math.sqrt(trueNegatives)}],
                            backgroundColor: '#10b981'
                        },
                        {
                            label: 'False Negatives',
                            data: [{x: 0.75, y: 0.25, r: Math.sqrt(falseNegatives)}],
                            backgroundColor: '#ef4444'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: { display: true, text: 'Confusion Matrix' },
                        legend: { display: true, position: 'bottom' }
                    },
                    scales: {
                        x: { max: 1, min: 0, display: false },
                        y: { max: 1, min: 0, display: false }
                    }
                }
            });
        }

        // Feature Correlation Heatmap
        const correlationCtx = document.getElementById('ml-feature-correlation');
        if (correlationCtx) {
            const features = ['Population', 'Health Coverage', 'Education Level', 'Income Level', 'Infrastructure'];
            const correlationData = features.map((f1, i) =>
                features.map((f2, j) => {
                    if (i === j) return 1;
                    return (0.3 + Math.random() * 0.4).toFixed(2);
                })
            );

            new Chart(correlationCtx, {
                type: 'bubble',
                data: {
                    labels: features,
                    datasets: features.flatMap((f1, i) =>
                        features.map((f2, j) => ({
                            label: `${f1} vs ${f2}`,
                            data: [{x: i, y: j, r: Math.abs(correlationData[i][j]) * 5}],
                            backgroundColor: correlationData[i][j] > 0.5 ? '#10b981' : correlationData[i][j] > 0 ? '#f59e0b' : '#ef4444'
                        }))
                    )
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: { display: true, text: 'Feature Correlation Matrix' },
                        legend: { display: false }
                    }
                }
            });
        }
    }

    updateMLMetricsTable(results) {
        const tbody = document.querySelector('#ml-metrics-table tbody');
        const accuracy = parseFloat(results.accuracy);
        const precision = parseFloat(results.precision);
        const f1 = parseFloat(results.f1_score);

        const getStatus = (value, goodThreshold = 75, fairThreshold = 65) => {
            if (value > goodThreshold) return '<span class="badge badge-success">✅ Good</span>';
            if (value > fairThreshold) return '<span class="badge badge-warning">⚠️ Fair</span>';
            return '<span class="badge badge-danger">❌ Poor</span>';
        };

        const metrics = [
            {
                metric: 'Accuracy',
                value: accuracy.toFixed(2) + '%',
                status: getStatus(accuracy, 85, 70),
                interpretation: 'Percentage of correct predictions on all data'
            },
            {
                metric: 'Precision',
                value: precision.toFixed(2) + '%',
                status: getStatus(precision, 85, 70),
                interpretation: 'Accuracy of positive predictions'
            },
            {
                metric: 'F1 Score',
                value: f1.toFixed(3),
                status: getStatus(f1 * 100, 85, 70),
                interpretation: 'Harmonic mean of precision and recall'
            },
            {
                metric: 'Training Time',
                value: results.training_time + 's',
                status: '<span class="badge badge-primary">ℹ️ Info</span>',
                interpretation: 'Time taken to train the model'
            },
            {
                metric: 'Samples Used',
                value: results.samples,
                status: '<span class="badge badge-primary">ℹ️ Info</span>',
                interpretation: 'Number of data points used in training'
            },
            {
                metric: 'Algorithm',
                value: results.algorithm.charAt(0).toUpperCase() + results.algorithm.slice(1),
                status: '<span class="badge badge-info">📊 ML</span>',
                interpretation: 'Machine learning algorithm used'
            }
        ];

        tbody.innerHTML = metrics.map(m => `
            <tr>
                <td><strong>${m.metric}</strong></td>
                <td>${m.value}</td>
                <td>${m.status}</td>
                <td style="font-size: 13px; color: #6b7280;">${m.interpretation}</td>
            </tr>
        `).join('');
    }

    async loadMLData() {
        try {
            const rawRisk = await safeFetchJson(API_BASE + '/api/risk-predictions', []);
            const rawForecast = await safeFetchJson(API_BASE + '/api/population-forecast', []);
            const rawClusters = await safeFetchJson(API_BASE + '/api/clustering-results', []);
            const rawFeatures = await safeFetchJson(API_BASE + '/api/feature-importance', {});

            const risk = ensureArray(rawRisk);
            const forecast = ensureArray(rawForecast);
            const clusters = ensureArray(rawClusters);
            const features = rawFeatures || {};

            this.renderMLCharts(risk, forecast, clusters, features);
        } catch (error) {
            console.error('Error loading ML data:', error);
            showError('Unable to load ML data');
        }
    }

    renderMLCharts(risk, forecast, clusters, features) {
        // Risk Predictions Chart
        const riskCtx = document.getElementById('risk-predictions-chart');
        if (riskCtx) {
            new Chart(riskCtx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Risk Score Distribution',
                        data: risk.map((r, i) => ({ x: i, y: r.risk_score })),
                        backgroundColor: risk.map(r => r.risk_score > 0.6 ? '#ef4444' : '#10b981')
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: { display: true, text: 'Household Risk Predictions' }
                }
            });
        }

        // Feature Importance Chart
        const featureCtx = document.getElementById('feature-importance-chart');
        if (featureCtx && features && Array.isArray(features.features)) {
            new Chart(featureCtx, {
                type: 'bar',
                data: {
                    labels: features.features.map(f => f.name),
                    datasets: [{
                        label: 'Importance Score',
                        data: features.features.map(f => f.importance),
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    async loadDecisionSupport() {
        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>Decision Support & Visualization Module</h3>
                </div>
                <div class="card-body">
                    <div class="grid mb-30">
                        <div class="stat-card">
                            <h4>Active Dashboards</h4>
                            <div class="stat-value">12</div>
                            <div class="stat-change">City & Barangay level</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #10b981;">
                            <h4>Reports Generated</h4>
                            <div class="stat-value">847</div>
                            <div class="stat-change">↑ 23 this month</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #7c3aed;">
                            <h4>Policy Simulations</h4>
                            <div class="stat-value">18</div>
                            <div class="stat-change">Active scenarios</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #f59e0b;">
                            <h4>Decision Makers</h4>
                            <div class="stat-value">34</div>
                            <div class="stat-change">Active users</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Key Performance Indicators</h3>
                        </div>
                        <div class="card-body">
                            <div class="mt-20">
                                <h4>Population Growth</h4>
                                <p>Current: 548,200 | Target: 555,000</p>
                                <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 20px;">
                                    <div style="background: #10b981; width: 98.8%; height: 100%;"></div>
                                </div>
                                <p style="margin-top: 5px; color: #6b7280;">98.8% of target</p>
                            </div>

                            <div class="mt-20">
                                <h4>Poverty Rate</h4>
                                <p>Current: 18.5% | Target: 15%</p>
                                <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 20px;">
                                    <div style="background: #3b82f6; width: 76.7%; height: 100%;"></div>
                                </div>
                                <p style="margin-top: 5px; color: #6b7280;">76.7% of target</p>
                            </div>

                            <div class="mt-20">
                                <h4>Health Coverage</h4>
                                <p>Current: 87.3 | Target: 95</p>
                                <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 20px;">
                                    <div style="background: #10b981; width: 91.9%; height: 100%;"></div>
                                </div>
                                <p style="margin-top: 5px; color: #6b7280;">91.9% of target</p>
                            </div>

                            <div class="mt-20">
                                <h4>Education Access</h4>
                                <p>Current: 92.1 | Target: 98</p>
                                <div style="background: #e5e7eb; border-radius: 8px; overflow: hidden; height: 20px;">
                                    <div style="background: #10b981; width: 94.0%; height: 100%;"></div>
                                </div>
                                <p style="margin-top: 5px; color: #6b7280;">94.0% of target</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    async loadSecurityGovernance() {
        if (!this.isSuperAdmin()) {
            document.querySelector('.content').innerHTML = '<div class="alert alert-danger">You do not have access to this module.</div>';
            return;
        }

        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>🔐 Security & Governance Module</h3>
                </div>
                <div class="card-body">
                    <div id="security-metrics-grid"></div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>System Security Overview</h3>
                        </div>
                        <div class="card-body">
                            <div id="security-overview"></div>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>👥 User Management</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary mb-20" onclick="openAddUserModal()">Add New User</button>
                            <table class="table" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>🎯 Role-Based Access Control</h3>
                        </div>
                        <div class="card-body">
                            <div id="roles-grid"></div>
                        </div>
                    </div>

                    <div class="card mt-30">
                        <div class="card-header">
                            <h3>📊 Access Activity Log</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" id="audit-logs-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.loadSecurityData();
    }

    async loadSecurityData() {
        try {
            const [usersRes, logsRes, metricsRes, rolesRes] = await Promise.all([
                fetch(API_BASE + '/api/users'),
                fetch(API_BASE + '/api/audit-logs'),
                fetch(API_BASE + '/api/security-metrics'),
                fetch(API_BASE + '/api/role-permissions')
            ]);

            const usersJson = await usersRes.json();
            const logsJson = await logsRes.json();
            const metricsJson = await metricsRes.json();
            const rolesJson = await rolesRes.json();

            const users = Array.isArray(usersJson) ? usersJson : (usersJson.data || []);
            const logs = Array.isArray(logsJson) ? logsJson : (logsJson.data || []);
            const metrics = (metricsJson && !Array.isArray(metricsJson)) ? metricsJson : (metricsJson && metricsJson[0]) || {};
            const roles = Array.isArray(rolesJson) ? rolesJson : (rolesJson.data || []);

            // Load security metrics
            const metricsGrid = document.getElementById('security-metrics-grid');
            metricsGrid.innerHTML = `
                <div class="grid">
                    <div class="stat-card">
                        <h4>Active Users</h4>
                        <div class="stat-value">${metrics.active_sessions}</div>
                        <div class="stat-change">Out of ${metrics.total_users} total</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #10b981;">
                        <h4>Failed Logins (24h)</h4>
                        <div class="stat-value">${metrics.failed_logins_24h}</div>
                        <div class="stat-change">Successful: ${metrics.successful_logins_24h}</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #7c3aed;">
                        <h4>Encryption Coverage</h4>
                        <div class="stat-value">${metrics.encryption_coverage}%</div>
                        <div class="stat-change">All data encrypted</div>
                    </div>
                    <div class="stat-card" style="border-left-color: #f59e0b;">
                        <h4>Compliance Score</h4>
                        <div class="stat-value">${metrics.compliance_score}%</div>
                        <div class="stat-change">Above target</div>
                    </div>
                </div>
            `;

            // Load security overview
            const overview = document.getElementById('security-overview');
            overview.innerHTML = `
                <table class="table">
                    <tr>
                        <td><strong>System Status:</strong></td>
                        <td><span class="badge badge-success">SECURE</span></td>
                    </tr>
                    <tr>
                        <td><strong>TLS/SSL Enabled:</strong></td>
                        <td><span class="badge badge-${metrics.tls_enabled ? 'success' : 'danger'}">
                            ${metrics.tls_enabled ? 'Yes' : 'No'}
                        </span></td>
                    </tr>
                    <tr>
                        <td><strong>Multi-Factor Auth:</strong></td>
                        <td><span class="badge badge-${metrics.mfa_enabled ? 'success' : 'warning'}">
                            ${metrics.mfa_enabled ? 'Enabled' : 'Disabled'}
                        </span></td>
                    </tr>
                    <tr>
                        <td><strong>API Rate Limiting:</strong></td>
                        <td><span class="badge badge-${metrics.api_rate_limiting ? 'success' : 'danger'}">
                            ${metrics.api_rate_limiting ? 'Enabled' : 'Disabled'}
                        </span></td>
                    </tr>
                    <tr>
                        <td><strong>Data Backups Completed:</strong></td>
                        <td>${metrics.data_backups_completed}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Security Audit:</strong></td>
                        <td>${new Date(metrics.last_security_audit).toLocaleDateString()}</td>
                    </tr>
                    <tr>
                        <td><strong>Security Incidents (7 days):</strong></td>
                        <td><span class="badge badge-${metrics.security_incidents_7d === 0 ? 'success' : 'danger'}">
                            ${metrics.security_incidents_7d}
                        </span></td>
                    </tr>
                </table>
            `;

            // Load users
            const usersTbody = document.querySelector('#users-table tbody');
            usersTbody.innerHTML = users.map(u => `
                <tr>
                    <td>${u.email}</td>
                    <td>${u.name}</td>
                    <td><span class="badge badge-primary">${u.role}</span></td>
                    <td><span class="badge badge-success">${u.status}</span></td>
                    <td>${new Date(u.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn btn-sm btn-outline" onclick="app.editUserPermissions(${u.id}, '${u.name}')">Permissions</button>
                        <button class="btn btn-sm btn-danger" onclick="app.deleteUser(${u.id})">Delete</button>
                    </td>
                </tr>
            `).join('');

            // Load roles
            const rolesGrid = document.getElementById('roles-grid');
            rolesGrid.innerHTML = roles.map(role => `
                <div class="card">
                    <div class="card-header">
                        <h4>${role.role}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Active Users:</strong> <span class="badge badge-primary">${role.users}</span></p>
                        <p><strong>Permissions:</strong></p>
                        <ul style="margin: 10px 0;">
                            ${(Array.isArray(role.permissions) ? role.permissions : []).map(perm => `<li>✓ ${perm.replace(/_/g, ' ')}</li>`).join('')}
                        </ul>
                        <button class="btn btn-sm btn-outline" onclick="app.editRolePermissions('${role.role}')">Edit</button>
                    </div>
                </div>
            `).join('');

            // Load audit logs
            const logsTbody = document.querySelector('#audit-logs-table tbody');
            logsTbody.innerHTML = logs.slice(0, 15).map(log => `
                <tr>
                    <td>${log.email || 'System'}</td>
                    <td><span class="badge badge-primary">${log.action}</span></td>
                    <td>${log.details}</td>
                    <td>${log.ip_address}</td>
                    <td>${new Date(log.timestamp).toLocaleString()}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error loading security data:', error);
        }
    }

    editUserPermissions(userId, userName) {
        const modal = document.createElement('div');
        modal.className = 'modal active';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit User Permissions</h2>
                    <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>User:</strong> ${userName}</p>
                    <div class="form-group">
                        <label>Assign Barangay</label>
                        <select id="user-barangay">
                            <option value="">-- All Barangays --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
                        <div style="display: grid; gap: 10px;">
                            <label><input type="checkbox" name="view_all"> View All Data</label>
                            <label><input type="checkbox" name="edit_data"> Edit Data</label>
                            <label><input type="checkbox" name="delete_data"> Delete Data</label>
                            <label><input type="checkbox" name="upload_excel"> Upload Excel</label>
                            <label><input type="checkbox" name="manage_documents"> Manage Documents</label>
                            <label><input type="checkbox" name="manage_users"> Manage Users</label>
                            <label><input type="checkbox" name="view_audit_logs"> View Audit Logs</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end; padding: 15px;">
                    <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                    <button class="btn btn-primary" onclick="app.saveUserPermissions(event, ${userId}, '${userName}')">Save</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Load barangays for the select (defensive)
        (async () => {
            try {
                const rawBarangays = await safeFetchJson(API_BASE + '/api/barangays', []);
                const barangays = ensureArray(rawBarangays);
                const select = modal.querySelector('#user-barangay');
                if (select) {
                    barangays.forEach(b => {
                        const option = document.createElement('option');
                        option.value = b.id;
                        option.textContent = b.name || '';
                        select.appendChild(option);
                    });
                }
            } catch (err) {
                console.error('Error loading barangays for user permissions:', err);
            }
        })();
    }

    saveUserPermissions(evt, userId, userName) {
        const modal = evt.target.closest('.modal');
        const barangayId = modal.querySelector('#user-barangay').value;
        const permissions = [];
        
        modal.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
            permissions.push(cb.name);
        });

        try {
            const res = await fetch(`${API_BASE}/api/user/${userId}/permissions`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    barangay_id: barangayId || null,
                    permissions: permissions
                })
            });
            const data = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
            if (res.ok) {
                alert('Permissions updated successfully');
                modal.remove();
                this.loadSecurityGovernance();
            } else {
                showError('Failed to update permissions');
            }
        } catch (err) {
            console.error('Error saving permissions:', err);
            showError('Error updating permissions');
        }
    }

    deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            try {
                const res = await fetch(`${API_BASE}/api/user/delete/${userId}`, { method: 'DELETE' });
                const data = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
                if (res.ok && data.success) {
                    this.loadSecurityGovernance();
                } else {
                    showError('Failed to delete user');
                }
            } catch (err) {
                console.error('Error deleting user:', err);
                showError('Error deleting user');
            }
        }

        // Small sparkline charts for module cards (if present)
        const makeSpark = (id, data, color) => {
            const el = document.getElementById(id);
            if (!el) return;
            try {
                new Chart(el, {
                    type: 'line',
                    data: {
                        labels: data.map((_, i) => i + 1),
                        datasets: [{
                            data: data,
                            borderColor: color || '#ffffff',
                            backgroundColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 2,
                            pointRadius: 0,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { display: false }, y: { display: false } }
                    }
                });
            } catch (e) {
                console.warn('Sparkline error', e);
            }
        };

        // Prepare small sample data (prefer real metrics if available)
        try {
            makeSpark('spark-records', households.slice(0, 8).map((h,i) => (i % 3) + 1), '#ffffff');
            makeSpark('spark-health', households.slice(0, 8).map((h,i) => Math.round(Math.random() * 100)), '#ffffff');
            makeSpark('spark-knowledge', Array.from({length:8}, (_,i) => Math.round(20 + Math.random() * 80)), '#ffffff');
            makeSpark('spark-ml', households.slice(0, 8).map((h,i) => Math.round(Math.random() * 100)), '#ffffff');
            makeSpark('spark-decision', Array.from({length:8}, (_,i) => Math.round(30 + Math.random() * 70)), '#ffffff');
        } catch (e) {}
    }

    editRolePermissions(role) {
        const permissionsList = {
            'City Administrator': ['view_all', 'edit_all', 'delete_all', 'manage_users', 'manage_permissions', 'upload_excel', 'view_analytics', 'manage_documents', 'view_audit_logs', 'manage_system'],
            'POPDEV Manager': ['view_all', 'edit_data', 'delete_data', 'upload_excel', 'view_analytics', 'manage_documents', 'view_reports'],
            'Barangay Data Encoder': ['view_assigned', 'edit_assigned', 'upload_excel', 'view_own_imports'],
            'Analyst': ['view_all', 'view_analytics', 'generate_reports'],
            'Viewer': ['view_public', 'view_summary']
        };

        const currentPerms = permissionsList[role] || [];
        const allPerms = ['view_all', 'edit_all', 'delete_all', 'manage_users', 'manage_permissions', 'upload_excel', 'view_analytics', 'manage_documents', 'view_audit_logs', 'manage_system', 'edit_data', 'delete_data', 'view_reports', 'view_assigned', 'edit_assigned', 'view_own_imports', 'generate_reports', 'view_public', 'view_summary'];

        const modal = document.createElement('div');
        modal.className = 'modal active';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Role Permissions: ${role}</h2>
                    <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Role:</strong> ${role}</p>
                    <div class="form-group">
                        <label>Permissions</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            ${allPerms.map(perm => `
                                <label>
                                    <input type="checkbox" name="${perm}" ${currentPerms.includes(perm) ? 'checked' : ''}>
                                    ${perm.replace(/_/g, ' ')}
                                </label>
                            `).join('')}
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end; padding: 15px;">
                    <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                    <button class="btn btn-primary" onclick="app.saveRolePermissions(event, '${role}')">Save</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    async saveRolePermissions(evt, role) {
        const modal = evt.target.closest('.modal');
        const permissions = [];
        
        modal.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
            permissions.push(cb.name);
        });

        try {
            const res = await fetch(`${API_BASE}/api/role/${role}/permissions`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ permissions: permissions })
            });
            const data = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
            if (res.ok) {
                alert('Role permissions updated successfully');
                modal.remove();
                this.loadSecurityGovernance();
            } else {
                showError('Failed to update role permissions');
            }
        } catch (err) {
            console.error('Error updating role permissions:', err);
            showError('Error updating role permissions');
        }
    }
    async openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            const [docsJson, catsJson] = await Promise.all([
                safeFetchJson(API_BASE + '/api/documents', []),
                safeFetchJson(API_BASE + '/api/categories', [])
            ]);

            const documents = ensureArray(docsJson);
            const categories = ensureArray(catsJson);

        }
    }

    prevSlide() {
        const slides = document.querySelectorAll('.carousel-slide');
        let active = document.querySelector('.carousel-slide.active');
        let prev = active.previousElementSibling;
        
        if (!prev) {

            prev = slides[slides.length - 1];
        }

        active.classList.remove('active');
        prev.classList.add('active');
    }

    nextSlide() {
        const slides = document.querySelectorAll('.carousel-slide');
        let active = document.querySelector('.carousel-slide.active');
        let next = active.nextElementSibling;
        
        if (!next) {
            showError('Unable to load documents');
            next = slides[0];
        }

        active.classList.remove('active');
        next.classList.add('active');
    }

    async loadAccount() {
        const content = document.querySelector('.content');
        content.innerHTML = `
            <div class="card mb-30">
                <div class="card-header">
                    <h3>👤 Account Settings</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; gap: 15px; border-bottom: 2px solid #e5e7eb; margin-bottom: 30px;">
                        <button class="account-tab-btn active" onclick="app.switchAccountTab('profile')">👤 Profile</button>
                        <button class="account-tab-btn" onclick="app.switchAccountTab('security')">🔒 Security</button>
                        <button class="account-tab-btn" onclick="app.switchAccountTab('settings')">⚙️ Settings</button>
                        <button class="account-tab-btn" onclick="app.switchAccountTab('legal')">📋 Legal</button>
                    </div>

                    <div id="account-profile" class="account-tab-content active">
                        <h3>👤 Profile Information</h3>
                        <div class="form-group" style="margin-top: 20px;">
                            <label for="profile-name">Full Name</label>
                            <input type="text" id="profile-name" placeholder="Your name" value="${document.body.dataset.userName || ''}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="profile-email">Email Address</label>
                            <input type="email" id="profile-email" placeholder="Your email" value="${document.body.dataset.userEmail || ''}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="profile-role">Role</label>
                            <input type="text" id="profile-role" placeholder="Your role" value="${document.body.dataset.userRole || ''}" disabled>
                        </div>
                        <button class="btn btn-primary" onclick="app.editProfile()">Edit Profile</button>
                    </div>

                    <div id="account-security" class="account-tab-content" style="display: none;">
                        <h3>🔒 Security Settings</h3>
                        <div style="margin-top: 20px;">
                            <h4 style="margin-bottom: 15px;">Password</h4>
                            <div class="form-group">
                                <label for="current-password">Current Password</label>
                                <input type="password" id="current-password" placeholder="Enter your current password">
                            </div>
                            <div class="form-group">
                                <label for="new-password">New Password</label>
                                <input type="password" id="new-password" placeholder="Enter new password (min 8 characters)">
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm Password</label>
                                <input type="password" id="confirm-password" placeholder="Confirm new password">
                            </div>
                            <button class="btn btn-primary" onclick="app.changePassword()">Update Password</button>
                        </div>
                    </div>

                    <div id="account-settings" class="account-tab-content" style="display: none;">
                        <h3>⚙️ Account Preferences</h3>
                        <div style="margin-top: 20px;">
                            <h4 style="margin-bottom: 15px;">Notification Preferences</h4>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="email-notifications" checked>
                                    <span>Email Notifications</span>
                                </label>
                                <p style="color: #6b7280; font-size: 13px; margin-left: 28px;">Receive email updates about important account activities</p>
                            </div>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="data-updates" checked>
                                    <span>Data Updates</span>
                                </label>
                                <p style="color: #6b7280; font-size: 13px; margin-left: 28px;">Get notified when new data is imported or updated</p>
                            </div>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="weekly-reports">
                                    <span>Weekly Reports</span>
                                </label>
                                <p style="color: #6b7280; font-size: 13px; margin-left: 28px;">Receive weekly summary reports of analytics and metrics</p>
                            </div>

                            <h4 style="margin-top: 30px; margin-bottom: 15px;">Appearance</h4>
                            <div class="form-group">
                                <label for="theme-select">Theme</label>
                                <select id="theme-select">
                                    <option value="light" selected>Light</option>
                                    <option value="dark">Dark</option>
                                    <option value="auto">Auto</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="language-select">Language</label>
                                <select id="language-select">
                                    <option value="en" selected>English</option>
                                    <option value="fil">Filipino</option>
                                </select>
                            </div>

                            <h4 style="margin-top: 30px; margin-bottom: 15px;">Privacy</h4>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="show-profile" checked>
                                    <span>Show Profile to Other Users</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="activity-logging" checked>
                                    <span>Allow Activity Logging</span>
                                </label>
                                <p style="color: #6b7280; font-size: 13px; margin-left: 28px;">This helps us improve the application and investigate issues</p>
                            </div>

                            <button class="btn btn-primary" onclick="app.saveSettings()">Save Settings</button>
                        </div>
                    </div>

                    <div id="account-legal" class="account-tab-content" style="display: none;">
                        <h3>📋 Terms and Conditions</h3>
                        <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 20px; max-height: 400px; overflow-y: auto;">
                            <h4>Calamba PopDev Resource Network - Terms and Conditions</h4>
                            <p style="margin-top: 15px; line-height: 1.6;">
                                <strong>1. Introduction</strong><br>
                                These terms and conditions govern your use of the Calamba PopDev Resource Network (the "Application"). By accessing and using this Application, you accept and agree to be bound by the terms and provision of this agreement.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>2. Use License</strong><br>
                                Permission is granted to temporarily download one copy of the materials (information or software) on the Calamba PopDev Resource Network for personal, non-commercial transitory viewing only.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>3. Disclaimer</strong><br>
                                The materials on the Application are provided on an 'as is' basis. The Application makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>4. Limitations</strong><br>
                                In no event shall the Calamba PopDev Resource Network or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption).
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>5. Accuracy of Materials</strong><br>
                                The materials appearing on the Calamba PopDev Resource Network could include technical, typographical, or photographic errors. The Application does not warrant that any of the materials on the Application are accurate, complete, or current. The Application may make changes to the materials contained on the Application at any time without notice.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>6. Links</strong><br>
                                The Application has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by the Application of the site. Use of any such linked website is at the user's own risk.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>7. Modifications</strong><br>
                                The Application may revise these terms of service for its website at any time without notice. By using this website, you are agreeing to be bound by the then current version of these terms of service.
                            </p>
                            <p style="margin-top: 10px; line-height: 1.6;">
                                <strong>8. Governing Law</strong><br>
                                These terms and conditions are governed by and construed in accordance with the laws of the Philippines, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.
                            </p>
                        </div>
                        <div style="margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" id="terms-agree">
                                <span>I agree to the Terms and Conditions</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add CSS for tabs
        if (!document.querySelector('#account-tab-styles')) {
            const style = document.createElement('style');
            style.id = 'account-tab-styles';
            style.textContent = `
                .account-tab-btn {
                    background: none;
                    border: none;
                    padding: 12px 20px;
                    cursor: pointer;
                    font-size: 14px;
                    color: #6b7280;
                    border-bottom: 3px solid transparent;
                    transition: all 0.3s;
                    font-weight: 500;
                }
                .account-tab-btn:hover {
                    color: #2563eb;
                }
                .account-tab-btn.active {
                    color: #2563eb;
                    border-bottom-color: #2563eb;
                }
                .account-tab-content {
                    transition: opacity 0.3s;
                }
                .account-tab-content.active {
                    display: block !important;
                }
            `;
            document.head.appendChild(style);
        }
    }

    switchAccountTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.account-tab-content').forEach(el => {
            el.classList.remove('active');
            el.style.display = 'none';
        });

        // Remove active class from buttons
        document.querySelectorAll('.account-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById(`account-${tab}`).classList.add('active');
        document.getElementById(`account-${tab}`).style.display = 'block';

        // Add active class to button
        event.target.classList.add('active');
    }

    editProfile() {
        alert('Edit profile functionality coming soon');
    }

    changePassword() {
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Please fill in all password fields');
            return;
        }

        if (newPassword !== confirmPassword) {
            alert('New passwords do not match');
            return;
        }

        if (newPassword.length < 8) {
            alert('New password must be at least 8 characters long');
            return;
        }

        (async () => {
            try {
                const res = await fetch(`${API_BASE}/api/user/change-password`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });
                const data = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
                if (res.ok && data.success) {
                    alert('Password changed successfully');
                    document.getElementById('current-password').value = '';
                    document.getElementById('new-password').value = '';
                    document.getElementById('confirm-password').value = '';
                } else {
                    showError(data.error || 'Failed to change password');
                }
            } catch (err) {
                console.error('Error:', err);
                showError('An error occurred while changing password');
            }
        })();
    }

    saveSettings() {
        const settings = {
            email_notifications: document.getElementById('email-notifications').checked,
            data_updates: document.getElementById('data-updates').checked,
            weekly_reports: document.getElementById('weekly-reports').checked,
            theme: document.getElementById('theme-select').value,
            language: document.getElementById('language-select').value,
            show_profile: document.getElementById('show-profile').checked,
            activity_logging: document.getElementById('activity-logging').checked
        };

        // For now, just save to localStorage
        localStorage.setItem('userSettings', JSON.stringify(settings));
        alert('Settings saved successfully');
    }

    closeModal(modal) {
        if (!modal) return;
        try {
            modal.remove();
        } catch (e) {
            // fallback
            modal.style.display = 'none';
        }
    }

    isSuperAdmin() {
        // Check if current user is city administrator (super admin)
        const userRole = document.body.dataset.userRole;
        return userRole === 'City Administrator';
    }
}

// Initialize app
const app = new App();
window.app = app; // Make app available globally

// Initialize active menu item based on current URL
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const page = item.getAttribute('data-page');
        const href = item.getAttribute('href');
        
        // Remove active from all items first
        item.classList.remove('active');
        
        // Check if this item matches the current URL
        if (page && href) {
            // Match current path with href or data-page
            if (currentPath.includes(href) || 
                (currentPath === '/' && (href === '/dashboard' || page === 'dashboard'))) {
                item.classList.add('active');
            }
        }
    });
});

// Helper functions
function openAddBarangayModal() {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Barangay</h2>
                <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Barangay Name</label>
                    <input id="barangay-name" type="text" placeholder="e.g. Barangay 1">
                </div>
                <div class="form-group">
                    <label>Population</label>
                    <input id="barangay-population" type="number" placeholder="Population">
                </div>
                <div class="form-group">
                    <label>Area (km²)</label>
                    <input id="barangay-area" type="text" placeholder="Area">
                </div>
                <div class="form-group">
                    <label>Chairman</label>
                    <input id="barangay-chairman" type="text" placeholder="Chairman name">
                </div>
                <div class="form-group">
                    <label>Contact</label>
                    <input id="barangay-contact" type="text" placeholder="Contact number or email">
                </div>
            </div>
            <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end; padding:15px;">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" id="save-barangay-btn">Save</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('#save-barangay-btn').addEventListener('click', () => {
        const name = modal.querySelector('#barangay-name').value.trim();
        const population = modal.querySelector('#barangay-population').value;
        const area = modal.querySelector('#barangay-area').value.trim();
        const chairman = modal.querySelector('#barangay-chairman').value.trim();
        const contact = modal.querySelector('#barangay-contact').value.trim();

        if (!name) {
            alert('Please enter a barangay name');
            return;
        }

        (async () => {
            try {
                const res = await fetch(API_BASE + '/api/barangay/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, population, area, chairman, contact })
                });
                const resp = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
                if (res.ok && resp && resp.success) {
                    alert('Barangay created');
                    modal.remove();
                    if (typeof app.loadDataManagement === 'function') app.loadDataManagement();
                } else {
                    showError(resp.message || 'Failed to create barangay');
                }
            } catch (err) {
                console.error('Create barangay error', err);
                showError('Error creating barangay');
            }
        })();
    });
}

function openDocumentUploadModal() {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Upload Document</h2>
                <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input id="doc-title" type="text" placeholder="Document title">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input id="doc-category" type="text" placeholder="Category">
                </div>
                <div class="form-group">
                    <label>Select File</label>
                    <input id="doc-file" type="file">
                </div>
            </div>
            <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end; padding:15px;">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" id="upload-doc-btn">Upload</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('#upload-doc-btn').addEventListener('click', () => {
        const title = modal.querySelector('#doc-title').value.trim();
        const category = modal.querySelector('#doc-category').value.trim();
        const fileInput = modal.querySelector('#doc-file');
        if (!title || !fileInput.files.length) {
            alert('Provide title and select a file');
            return;
        }

        const form = new FormData();
        form.append('title', title);
        form.append('category', category);
        form.append('file', fileInput.files[0]);

        (async () => {
            try {
                const res = await fetch(API_BASE + '/api/document/upload', {
                    method: 'POST',
                    body: form
                });
                const resp = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
                if (res.ok && resp && resp.success) {
                    alert('Document uploaded');
                    modal.remove();
                    if (typeof app.loadKnowledgeManagement === 'function') app.loadKnowledgeManagement();
                } else {
                    showError(resp.message || 'Upload failed');
                }
            } catch (err) {
                console.error('Upload error', err);
                showError('Error uploading document');
            }
        })();
    });
}

function openAddUserModal() {
    const modal = document.createElement('div');
    modal.className = 'modal active';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New User</h2>
                <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email</label>
                    <input id="new-user-email" type="email" placeholder="user@example.com">
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input id="new-user-name" type="text" placeholder="Full name">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input id="new-user-password" type="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="new-user-role">
                        <option value="Viewer">Viewer</option>
                        <option value="Data Encoder">Data Encoder</option>
                        <option value="City Administrator">City Administrator</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="display:flex; gap:10px; justify-content:flex-end; padding:15px;">
                <button class="btn btn-outline" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" id="create-user-btn">Create</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    modal.querySelector('#create-user-btn').addEventListener('click', () => {
        const email = modal.querySelector('#new-user-email').value.trim();
        const name = modal.querySelector('#new-user-name').value.trim();
        const password = modal.querySelector('#new-user-password').value;
        const role = modal.querySelector('#new-user-role').value;

        if (!email || !name || !password) {
            alert('Please fill required fields');
            return;
        }

        (async () => {
            try {
                const res = await fetch(API_BASE + '/api/user/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, name, password, role })
                });
                const resp = await (res.ok ? res.json().catch(()=>({})) : res.json().catch(()=>({}))); 
                if (res.ok && resp && resp.success) {
                    alert('User created');
                    modal.remove();
                    if (typeof app.loadSecurityGovernance === 'function') app.loadSecurityGovernance();
                } else {
                    showError(resp.message || 'Failed to create user');
                }
            } catch (err) {
                console.error('Create user error', err);
                showError('Error creating user');
            }
        })();
    });
}

// Initialize the application when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        const app = new App();
        window.app = app;
    });
} else {
    const app = new App();
    window.app = app;
}

