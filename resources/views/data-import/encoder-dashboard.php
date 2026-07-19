<?php
// Data Encoder Dashboard - Limited interface for data entry users
include __DIR__ . '/../layouts/app.php';
?>

<style>
    .encoder-restricted-sidebar {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        padding: 20px 0;
        border-right: 2px solid #2563eb;
        z-index: 1000;
    }

    .encoder-restricted-sidebar .logo {
        padding: 0 20px 30px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }

    .encoder-restricted-sidebar .nav-item {
        padding: 12px 20px;
        color: #e5e7eb;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .encoder-restricted-sidebar .nav-item:hover {
        background: rgba(37, 99, 235, 0.2);
        border-left-color: #2563eb;
    }

    .encoder-restricted-sidebar .nav-item.active {
        background: rgba(37, 99, 235, 0.3);
        border-left-color: #2563eb;
        color: #fff;
    }

    .encoder-layout {
        margin-left: 280px;
    }

    .encoder-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .encoder-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .encoder-header p {
        margin: 5px 0 0;
        opacity: 0.9;
    }

    .encoder-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #2563eb;
    }

    .encoder-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .encoder-stat-item {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #bae6fd;
    }

    .encoder-stat-value {
        font-size: 28px;
        font-weight: bold;
        color: #0c4a6e;
    }

    .encoder-stat-label {
        font-size: 12px;
        color: #0369a1;
        margin-top: 5px;
    }
</style>

<div class="encoder-layout">
    <div class="encoder-header">
        <h1> Data Import - Your Dashboard</h1>
        <p>Upload and manage your barangay data files</p>
    </div>

    <div class="encoder-stats" id="encoder-stats"></div>

    <div class="encoder-card">
        <h2>Upload New File</h2>
        <div id="upload-message"></div>

        <div class="form-group">
            <label for="encoder-barangay">Your Barangay: <span id="encoder-barangay-name">Loading...</span></label>
        </div>

        <div class="form-group">
            <label for="encoder-file">Select Excel/CSV File *</label>
            <input type="file" id="encoder-file" accept=".xlsx,.xls,.csv" required>
            <small style="color: #6b7280;">Supported formats: Excel (.xlsx, .xls), CSV | Max 10MB</small>
        </div>

        <div id="file-preview"></div>

        <div class="form-group mt-20">
            <button class="btn btn-primary" id="encoder-upload-btn">Upload File</button>
            <button class="btn btn-outline" id="encoder-download-template" style="margin-left: 10px;">Download Template</button>
        </div>

        <div class="alert alert-info mt-20">
            <strong>Required Columns:</strong>
            <ul style="margin: 10px 0 0 0;">
                <li><strong>name</strong> - Full name</li>
                <li><strong>weight</strong> - Weight in kg</li>
                <li><strong>address</strong> - Full address</li>
                <li><strong>salary</strong> - Monthly salary</li>
                <li><strong>family_members</strong> - Number of family members</li>
            </ul>
        </div>
    </div>

    <div class="encoder-card">
        <h2>Your Upload History</h2>
        <table class="table" id="encoder-history-table">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Records</th>
                    <th>Processed</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
const EncoderDashboard = {
    barangayId: null,
    barangayName: null,

    init() {
        // Get user's assigned barangay
        fetch('/CityOfCalambaDev/public/api/user-profile')
            .then(r => r.json())
            .then(data => {
                this.barangayId = data.barangay_id;
                this.barangayName = data.barangay_name;
                document.getElementById('encoder-barangay-name').textContent = this.barangayName || 'Not assigned';
                this.loadStats();
                this.loadHistory();
            });

        // Event listeners
        document.getElementById('encoder-upload-btn').addEventListener('click', () => this.handleUpload());
        document.getElementById('encoder-download-template').addEventListener('click', () => this.downloadTemplate());
        document.getElementById('encoder-file').addEventListener('change', (e) => this.previewFile(e));
    },

    previewFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            alert('File too large');
            event.target.value = '';
            return;
        }

        const preview = document.getElementById('file-preview');
        preview.innerHTML = `<div class="alert alert-info">File: ${file.name} (${(file.size / 1024).toFixed(2)} KB)</div>`;
    },

    async handleUpload() {
        const file = document.getElementById('encoder-file').files[0];
        if (!file || !this.barangayId) {
            alert('Please select a file');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', file);
        formData.append('barangay_id', this.barangayId);

        const btn = document.getElementById('encoder-upload-btn');
        btn.disabled = true;
        btn.textContent = 'Uploading...';

        try {
            const response = await fetch('/CityOfCalambaDev/public/api/data-import/upload', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                document.getElementById('upload-message').innerHTML = 
                    `<div class="alert alert-success">âœ“ Successfully uploaded ${result.processed_records} records!</div>`;
                document.getElementById('encoder-file').value = '';
                this.loadHistory();
                this.loadStats();
            } else {
                document.getElementById('upload-message').innerHTML = 
                    `<div class="alert alert-danger">âœ— ${result.error || 'Upload failed'}</div>`;
            }
        } catch (error) {
            alert('Upload error: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Upload File';
        }
    },

    downloadTemplate() {
        const csv = 'name,weight,address,salary,family_members\n' +
                   'Juan Dela Cruz,65,"123 Main St",25000,4\n' +
                   'Maria Santos,58,"456 Oak Ave",30000,5\n';
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'data_template.csv';
        a.click();
        URL.revokeObjectURL(url);
    },

    async loadStats() {
        try {
            const response = await fetch('/CityOfCalambaDev/public/api/import-stats');
            const stats = await response.json();

            document.getElementById('encoder-stats').innerHTML = `
                <div class="encoder-stat-item">
                    <div class="encoder-stat-value">${stats.total_imports || 0}</div>
                    <div class="encoder-stat-label">Total Uploads</div>
                </div>
                <div class="encoder-stat-item">
                    <div class="encoder-stat-value">${(stats.total_records_processed || 0).toLocaleString()}</div>
                    <div class="encoder-stat-label">Records Imported</div>
                </div>
                <div class="encoder-stat-item">
                    <div class="encoder-stat-value">${stats.completed_imports || 0}</div>
                    <div class="encoder-stat-label">Completed</div>
                </div>
            `;
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    },

    async loadHistory() {
        try {
            const response = await fetch('/CityOfCalambaDev/public/api/import-history');
            const imports = await response.json();

            const tbody = document.querySelector('#encoder-history-table tbody');
            tbody.innerHTML = (imports || []).map(imp => `
                <tr>
                    <td>${imp.file_name}</td>
                    <td>${imp.total_records}</td>
                    <td>${imp.processed_records}</td>
                    <td><span class="badge badge-${imp.status === 'completed' ? 'success' : 'warning'}">${imp.status}</span></td>
                    <td>${new Date(imp.import_date).toLocaleDateString()}</td>
                </tr>
            `).join('');

            if (!imports || imports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #9ca3af;">No uploads yet</td></tr>';
            }
        } catch (error) {
            console.error('Error loading history:', error);
        }
    }
};

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => EncoderDashboard.init());
} else {
    EncoderDashboard.init();
}
</script>

