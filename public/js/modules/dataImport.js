/**
 * Data Import Module
 * Handles Excel file uploads and bulk data processing
 */

const DataImportModule = {
    init() {
        this.attachEventListeners();
        this.loadImportHistory();
    },

    attachEventListeners() {
        // File input change event
        const fileInput = document.getElementById('excel-file');
        if (fileInput) {
            fileInput.addEventListener('change', (e) => this.previewFile(e));
        }

        // Upload button
        const uploadBtn = document.getElementById('upload-btn');
        if (uploadBtn) {
            uploadBtn.addEventListener('click', () => this.handleUpload());
        }

        // Download template button
        const downloadBtn = document.getElementById('download-template');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', () => this.downloadTemplate());
        }
    },

    previewFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Check file size (max 10MB)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File size exceeds 10MB limit');
            event.target.value = '';
            return;
        }

        // Check file type
        const allowedTypes = ['application/vnd.ms-excel', 
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv'];
        
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload an Excel (.xlsx, .xls) or CSV file');
            event.target.value = '';
            return;
        }

        // Show file preview
        const preview = document.getElementById('file-preview');
        if (preview) {
            preview.innerHTML = `
                <div class="alert alert-info">
                    <strong>File Selected:</strong> ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                </div>
            `;
        }

        // Try to read file preview (for CSV)
        if (file.type === 'text/csv') {
            this.readCSVPreview(file);
        }
    },

    readCSVPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const lines = e.target.result.split('\n').slice(0, 5);
            const preview = document.getElementById('csv-preview');
            if (preview) {
                preview.innerHTML = '<pre>' + lines.join('\n') + '</pre>';
            }
        };
        reader.readAsText(file);
    },

    async handleUpload() {
        const fileInput = document.getElementById('excel-file');
        const barangaySelect = document.getElementById('barangay-select');

        if (!fileInput.files[0]) {
            alert('Please select a file');
            return;
        }

        if (!barangaySelect.value) {
            alert('Please select a barangay');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', fileInput.files[0]);
        formData.append('barangay_id', barangaySelect.value);

        const uploadBtn = document.getElementById('upload-btn');
        const originalText = uploadBtn.textContent;
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Uploading...';

        try {
            const response = await fetch(API_BASE + '/api/data-import/upload', {
                method: 'POST',
                body: formData
            });

            let result = null;
            try { result = await response.json(); } catch(e) { result = { message: 'No JSON response' }; }

            if (response.ok) {
                this.showUploadSuccess(result);
                fileInput.value = '';
                barangaySelect.value = '';
                this.loadImportHistory();
            } else {
                this.showUploadError(result);
                showError('Upload failed');
            }
        } catch (error) {
            console.error('Upload error:', error);
            showError('Upload failed: ' + (error.message || 'network error'));
        } finally {
            uploadBtn.disabled = false;
            uploadBtn.textContent = originalText;
        }
    },

    showUploadSuccess(result) {
        const message = document.getElementById('upload-message');
        message.innerHTML = `
            <div class="alert alert-success">
                <strong>Success!</strong> ${result.message}
                <br>
                Total Records: ${result.total_records} | Processed: ${result.processed_records}
                <br>
                <a href="#import-details-${result.import_id}" class="btn btn-sm btn-primary mt-10">View Details</a>
            </div>
        `;
    },

    showUploadError(result) {
        const message = document.getElementById('upload-message');
        let errorHTML = '<div class="alert alert-danger"><strong>Upload Failed!</strong><ul>';
        
        if (result.errors && Array.isArray(result.errors)) {
            result.errors.forEach(err => {
                errorHTML += `<li>${err}</li>`;
            });
        } else if (result.error) {
            errorHTML += `<li>${result.error}</li>`;
        }
        
        errorHTML += '</ul></div>';
        message.innerHTML = errorHTML;
    },

    downloadTemplate() {
        // Create CSV template
        const headers = ['name', 'weight', 'barangay', 'address', 'salary', 'family_members'];
        const template = headers.join(',') + '\n' +
                        'Juan Dela Cruz,65,Barangay 1,"123 Main Street, Calamba",25000,4\n' +
                        'Maria Santos,58,Barangay 2,"456 Oak Avenue, Calamba",30000,5\n';

        // Create download link
        const blob = new Blob([template], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'data_import_template.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    },

    async loadImportHistory() {
        try {
            const rawImports = await safeFetchJson(API_BASE + '/api/import-history', []);
            const imports = ensureArray(rawImports);

            const historyTable = document.querySelector('#import-history tbody');
            if (historyTable) {
                historyTable.innerHTML = imports.map(imp => `
                    <tr>
                        <td>${imp.file_name || ''}</td>
                        <td>${imp.barangay_name || 'N/A'}</td>
                        <td>${imp.total_records || 0}</td>
                        <td>${imp.processed_records || 0}</td>
                        <td><span class="badge badge-${this.getStatusColor(imp.status)}">${imp.status || ''}</span></td>
                        <td>${imp.import_date ? new Date(imp.import_date).toLocaleDateString() : ''}</td>
                        <td>
                            <button class="btn btn-sm btn-outline" onclick="DataImportModule.viewDetails(${imp.id})">View</button>
                        </td>
                    </tr>
                `).join('');
            }

            // Load stats
            this.loadImportStats();
        } catch (error) {
            console.error('Error loading import history:', error);
            showError('Unable to load import history');
        }
    },

    async loadImportStats() {
        try {
            const stats = await safeFetchJson(API_BASE + '/api/import-stats', {});

            const statsDiv = document.getElementById('import-stats');
            if (statsDiv) {
                statsDiv.innerHTML = `
                    <div class="grid">
                        <div class="stat-card">
                            <h4>Total Imports</h4>
                            <div class="stat-value">${stats.total_imports || 0}</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #10b981;">
                            <h4>Records Uploaded</h4>
                            <div class="stat-value">${(stats.total_records_uploaded || 0).toLocaleString()}</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #7c3aed;">
                            <h4>Records Processed</h4>
                            <div class="stat-value">${(stats.total_records_processed || 0).toLocaleString()}</div>
                        </div>
                        <div class="stat-card" style="border-left-color: #f59e0b;">
                            <h4>Completed Imports</h4>
                            <div class="stat-value">${stats.completed_imports || 0}</div>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading import stats:', error);
            showError('Unable to load import stats');
        }
    },

    async viewDetails(importId) {
        try {
            const import_data = await safeFetchJson(`${API_BASE}/api/import/${importId}`, {});

            const modal = document.createElement('div');
            modal.className = 'modal active';
            modal.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Import Details</h2>
                        <button class="modal-close" onclick="this.closest('.modal').remove()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <td><strong>File Name:</strong></td>
                                <td>${import_data.file_name || ''}</td>
                            </tr>
                            <tr>
                                <td><strong>Barangay:</strong></td>
                                <td>${import_data.barangay_name || ''}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Records:</strong></td>
                                <td>${import_data.total_records || 0}</td>
                            </tr>
                            <tr>
                                <td><strong>Processed Records:</strong></td>
                                <td>${import_data.processed_records || 0}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge badge-${this.getStatusColor(import_data.status)}">${import_data.status || ''}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Import Date:</strong></td>
                                <td>${import_data.import_date ? new Date(import_data.import_date).toLocaleString() : ''}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        } catch (error) {
            console.error('Error loading import details:', error);
            showError('Failed to load import details');
        }
    },

    getStatusColor(status) {
        switch(status) {
            case 'completed':
                return 'success';
            case 'processing':
                return 'info';
            case 'pending':
                return 'warning';
            case 'failed':
                return 'danger';
            default:
                return 'secondary';
        }
    }
};

// Initialize when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('excel-file')) {
            DataImportModule.init();
        }
    });
} else {
    if (document.getElementById('excel-file')) {
        DataImportModule.init();
    }
}
