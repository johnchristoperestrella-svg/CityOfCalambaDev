<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;">📤 Upload Excel Data</h1>
        <p style="font-size: 15px; color: #6b7280;">Import household and individual data from Excel files</p>
    </div>

    <!-- Two Column Layout -->
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Left Column: Download Template -->
        <div class="card">
            <div class="card-header">
                <h3>📥 Step 1: Download Template</h3>
            </div>
            <div class="card-body">
                <p style="color: #6b7280; margin-bottom: 20px;">Start by downloading the Excel template with the correct format and sample data.</p>
                
                <div style="background: #eff6ff; border: 2px dashed #3b82f6; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 40px; margin-bottom: 10px;">📊</div>
                    <p style="margin: 0 0 15px 0; color: #1e40af; font-weight: 600;">Excel Template</p>
                    <p style="margin: 0 0 20px 0; color: #3b82f6; font-size: 14px;">Includes sample data and instructions</p>
                    <a href="/api/data-import/template" style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.4)';"
                        onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        ⬇️ Download Template
                    </a>
                </div>

                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                    <p style="margin: 0; color: #78350f; font-size: 14px;">
                        <strong>💡 Tip:</strong> The template includes sample data to show the correct format. Replace it with your actual data.
                    </p>
                </div>

                <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px;">
                    <p style="margin: 0; color: #1e40af; font-size: 14px;">
                        <strong>ℹ️ Note:</strong> All columns are required. Do not delete or rename column headers.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: Upload File -->
        <div class="card">
            <div class="card-header">
                <h3>📝 Step 2: Upload Your Data</h3>
            </div>
            <div class="card-body">
                <p style="color: #6b7280; margin-bottom: 20px;">Fill in your data in the Excel file, then upload it here.</p>

                <form id="uploadForm" style="background: #f9fafb; padding: 20px; border-radius: 8px;">
                    <!-- Barangay Selection -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                            🏘️ Select Barangay *
                        </label>
                        <select id="barangaySelect" name="barangay_id" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                            <option value="">-- Choose a barangay --</option>
                            <?php foreach ($barangays as $barangay): ?>
                                <option value="<?php echo $barangay['id']; ?>"><?php echo htmlspecialchars($barangay['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                            📁 Select Excel File *
                        </label>
                        <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s ease;" 
                             id="dropZone" 
                             onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff';"
                             onmouseout="this.style.borderColor='#d1d5db'; this.style.backgroundColor='transparent';">
                            <input type="file" id="fileInput" name="excel_file" accept=".xlsx,.xls,.csv" style="display: none;" />
                            <div style="font-size: 32px; margin-bottom: 10px;">📤</div>
                            <p style="margin: 0 0 5px 0; font-weight: 600; color: #1f2937;">Click to select or drag and drop</p>
                            <p style="margin: 0; color: #6b7280; font-size: 14px;">Excel (.xlsx, .xls) or CSV files only • Max 10MB</p>
                            <p id="fileName" style="margin: 10px 0 0 0; color: #3b82f6; font-weight: 600; display: none;"></p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" style="width: 100%; background: #10b981; color: white; padding: 12px; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.4)';"
                        onmouseout="this.style.background='#10b981'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        ✅ Upload Data
                    </button>
                </form>

                <!-- Progress Bar -->
                <div id="progressContainer" style="display: none; margin-top: 20px;">
                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between;">
                        <span style="color: #6b7280; font-weight: 600;">Uploading...</span>
                        <span id="progressPercent" style="color: #3b82f6; font-weight: 600;">0%</span>
                    </div>
                    <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
                        <div id="progressBar" style="background: #3b82f6; height: 100%; width: 0%; transition: width 0.3s ease;"></div>
                    </div>
                </div>

                <!-- Status Messages -->
                <div id="successMessage" style="display: none; background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; border-radius: 4px; margin-top: 20px;">
                    <p id="successText" style="margin: 0; color: #065f46;"></p>
                </div>

                <div id="errorMessage" style="display: none; background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; border-radius: 4px; margin-top: 20px;">
                    <p id="errorText" style="margin: 0; color: #7f1d1d;"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Info -->
    <div class="card">
        <div class="card-header">
            <h3>📋 Upload Requirements</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-weight: 600; color: #1f2937;">✅ Required Fields</p>
                    <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 14px;">
                        <li>Household Head Name</li>
                        <li>Weight (kg)</li>
                        <li>Address</li>
                        <li>Salary (PHP)</li>
                        <li>Family Members</li>
                    </ul>
                </div>

                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-weight: 600; color: #1f2937;">📊 File Format</p>
                    <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 14px;">
                        <li>Excel 2007+ (.xlsx)</li>
                        <li>Excel 97-2003 (.xls)</li>
                        <li>Comma-separated (.csv)</li>
                        <li>Max file size: 10MB</li>
                    </ul>
                </div>

                <div style="background: #f3f4f6; padding: 15px; border-radius: 8px;">
                    <p style="margin: 0 0 10px 0; font-weight: 600; color: #1f2937;">⚠️ Important Notes</p>
                    <ul style="margin: 0; padding-left: 20px; color: #6b7280; font-size: 14px;">
                        <li>Do not modify column headers</li>
                        <li>All rows must have data in all columns</li>
                        <li>Numeric fields cannot contain symbols</li>
                        <li>Select correct barangay before uploading</li>
                    </ul>
                </div>
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

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileName = document.getElementById('fileName');
const uploadForm = document.getElementById('uploadForm');

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#3b82f6';
    dropZone.style.backgroundColor = '#eff6ff';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.borderColor = '#d1d5db';
    dropZone.style.backgroundColor = 'transparent';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#d1d5db';
    dropZone.style.backgroundColor = 'transparent';
    
    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        updateFileName();
    }
});

fileInput.addEventListener('change', updateFileName);

function updateFileName() {
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        fileName.textContent = '✓ Selected: ' + file.name;
        fileName.style.display = 'block';
    } else {
        fileName.style.display = 'none';
    }
}

// Form submission
uploadForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const barangayId = document.getElementById('barangaySelect').value;
    const file = fileInput.files[0];

    if (!barangayId) {
        showError('Please select a barangay');
        return;
    }

    if (!file) {
        showError('Please select a file');
        return;
    }

    const formData = new FormData();
    formData.append('barangay_id', barangayId);
    formData.append('excel_file', file);

    try {
        document.getElementById('progressContainer').style.display = 'block';
        document.getElementById('submitBtn').disabled = true;

        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                document.getElementById('progressBar').style.width = percentComplete + '%';
                document.getElementById('progressPercent').textContent = percentComplete + '%';
            }
        });

        xhr.addEventListener('load', () => {
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('progressContainer').style.display = 'none';

            if (xhr.status === 201 || xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                showSuccess('✅ ' + response.message + ' (' + response.processed_records + ' of ' + response.total_records + ' records processed)');
                uploadForm.reset();
                fileName.style.display = 'none';
                setTimeout(() => {
                    window.location.href = '/data-import';
                }, 2000);
            } else {
                const response = JSON.parse(xhr.responseText);
                let errorMsg = response.error || 'Upload failed';
                
                // Show detailed errors if available
                if (response.errors && Array.isArray(response.errors) && response.errors.length > 0) {
                    errorMsg += '\n\nDetails:\n' + response.errors.slice(0, 5).join('\n');
                    if (response.errors.length > 5) {
                        errorMsg += '\n... and ' + (response.errors.length - 5) + ' more errors';
                    }
                }
                
                showError('❌ ' + errorMsg);
            }
        });

        xhr.addEventListener('error', () => {
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('progressContainer').style.display = 'none';
            showError('❌ Upload failed. Please try again.');
        });

        xhr.open('POST', '/api/data-import/upload');
        xhr.send(formData);
    } catch (error) {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('progressContainer').style.display = 'none';
        showError('❌ Error: ' + error.message);
    }
});

function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    document.getElementById('successText').textContent = message;
    successDiv.style.display = 'block';
    document.getElementById('errorMessage').style.display = 'none';
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    document.getElementById('errorText').textContent = message;
    errorDiv.style.display = 'block';
    document.getElementById('successMessage').style.display = 'none';
}
</script>
