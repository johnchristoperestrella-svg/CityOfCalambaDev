<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> My Account</h1>
        <p style="font-size: 15px; color: #6b7280;">Manage your account settings and security.</p>
    </div>

    <!-- Account Information Card -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>Account Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['name'] ?? 'N/A'); ?>" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Role</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['role'] ?? 'N/A'); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background-color: #f3f4f6; color: #6b7280;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Member Since</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background-color: #f3f4f6; color: #6b7280;">
                </div>
            </div>
            <button style="margin-top: 20px; padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Save Changes</button>
        </div>
    </div>

    <!-- Profile Photo Card -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3>Profile Photo</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 30px; align-items: start;">
                <!-- Photo Display -->
                <div style="text-align: center;">
                    <div style="width: 180px; height: 180px; border-radius: 50%; background-color: #e5e7eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; overflow: hidden; border: 3px solid #d1d5db;">
                        <?php if (!empty($user['profile_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="font-size: 60px; color: #9ca3af;">📷</div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($user['profile_photo'])): ?>
                        <button id="removePhotoBtn" type="button" style="padding: 8px 16px; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px;">Remove Photo</button>
                    <?php endif; ?>
                </div>

                <!-- Upload Form -->
                <div>
                    <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1f2937;">Upload New Photo</h4>
                    <form id="photoUploadForm" enctype="multipart/form-data" style="display: grid; gap: 15px;">
                        <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s ease; background-color: #f9fafb;" id="dropZone">
                            <input type="file" id="photoInput" name="profile_photo" accept="image/*" style="display: none;">
                            <div style="font-size: 40px; margin-bottom: 10px;">📁</div>
                            <p style="margin: 0 0 10px 0; font-size: 14px; color: #1f2937; font-weight: 600;">Drag and drop your photo here</p>
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">or click to select a file</p>
                            <p style="margin: 10px 0 0 0; font-size: 12px; color: #9ca3af;">JPEG, PNG, GIF, or WebP (max 5MB)</p>
                        </div>
                        <div id="fileInfo" style="display: none; padding: 10px; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 14px; color: #1e40af;">
                            <strong>Selected file:</strong> <span id="fileName"></span>
                        </div>
                        <button type="submit" style="padding: 10px 20px; background-color: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: none;" id="uploadBtn">Upload Photo</button>
                    </form>
                    <div id="photoMessage" style="margin-top: 15px; padding: 12px; border-radius: 6px; display: none; font-size: 14px; font-weight: 500;"></div>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 6px; font-size: 13px; color: #4b5563; line-height: 1.6;">
                <strong>📝 Guidelines:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    <li>Use a recent, clear photo</li>
                    <li>File must be JPEG, PNG, GIF, or WebP format</li>
                    <li>Maximum file size is 5MB</li>
                    <li>The image will be cropped to a circle for your profile</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Security Settings Card -->
    <div class="card">
        <div class="card-header">
            <h3>Security Settings</h3>
        </div>
        <div class="card-body">
            <h4 style="margin: 0 0 20px 0; font-size: 16px; font-weight: 600; color: #1f2937;">Change Password</h4>
            <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Current Password</label>
                    <input type="password" placeholder="Enter your current password" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">New Password</label>
                    <input type="password" placeholder="Enter new password (minimum 8 characters)" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">Confirm New Password</label>
                    <input type="password" placeholder="Confirm your new password" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>
            </div>
            <button style="margin-top: 20px; padding: 10px 20px; background-color: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Change Password</button>
        </div>
    </div>

    <!-- Data Privacy & Protection Card -->
    <div class="card" style="margin-top: 30px; border-left: 4px solid #8b5cf6;">
        <div class="card-header" style="background-color: #f9f5ff;">
            <h3 style="color: #7c3aed;">Data Privacy & Protection</h3>
        </div>
        <div class="card-body">
            <div style="background-color: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                <p style="margin: 0; font-size: 14px; color: #0c4a6e; font-weight: 600;">🔒 Your Privacy Commitment</p>
            </div>

            <div style="display: grid; gap: 15px;">
                <div>
                    <h4 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 600; color: #1f2937;">Data Protection</h4>
                    <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.6;">
                        Your personal data is protected and will never be used for unauthorized purposes. We are committed to safeguarding your information and using it only for the services you have requested and as required by law.
                    </p>
                </div>

                <div>
                    <h4 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 600; color: #1f2937;">Picture & Image Usage</h4>
                    <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.6;">
                        Any pictures or images you upload are securely stored and will only be used for the specific purpose you authorize. They will not be shared, sold, or used in any way without your explicit consent.
                    </p>
                </div>

                <div>
                    <h4 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 600; color: #1f2937;">Your Rights</h4>
                    <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #4b5563; line-height: 1.8;">
                        <li>Access your personal information at any time</li>
                        <li>Request correction or deletion of your data</li>
                        <li>Withdraw consent for data processing</li>
                        <li>Request a copy of your data in a portable format</li>
                    </ul>
                </div>

                <div>
                    <h4 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 600; color: #1f2937;">Questions or Concerns?</h4>
                    <p style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.6;">
                        If you have any questions about how your data is handled or would like to exercise your privacy rights, please contact our support team.
                    </p>
                </div>
            </div>

            <button style="margin-top: 20px; padding: 10px 20px; background-color: #8b5cf6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;">Privacy Policy</button>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
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
    .card-body > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const photoInput = document.getElementById('photoInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const uploadBtn = document.getElementById('uploadBtn');
    const photoUploadForm = document.getElementById('photoUploadForm');
    const photoMessage = document.getElementById('photoMessage');
    const removePhotoBtn = document.getElementById('removePhotoBtn');

    // Click on drop zone to select file
    dropZone.addEventListener('click', () => photoInput.click());

    // Drag over
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#3b82f6';
        dropZone.style.backgroundColor = '#eff6ff';
    });

    // Drag leave
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = '#f9fafb';
    });

    // Drop
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = '#f9fafb';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            handleFileSelect();
        }
    });

    // File input change
    photoInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        if (photoInput.files.length > 0) {
            const file = photoInput.files[0];
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showMessage('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.', 'error');
                photoInput.value = '';
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showMessage('File size exceeds 5MB limit.', 'error');
                photoInput.value = '';
                return;
            }

            fileName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            fileInfo.style.display = 'block';
            uploadBtn.style.display = 'inline-block';
            photoMessage.style.display = 'none';
        }
    }

    // Handle form submission
    photoUploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (photoInput.files.length === 0) {
            showMessage('Please select a file.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('profile_photo', photoInput.files[0]);

        uploadBtn.disabled = true;
        uploadBtn.textContent = 'Uploading...';

        try {
            const response = await fetch('/account/upload-photo', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showMessage('Profile photo uploaded successfully!', 'success');
                photoInput.value = '';
                fileInfo.style.display = 'none';
                uploadBtn.style.display = 'none';
                uploadBtn.textContent = 'Upload Photo';
                
                // Reload page to show updated photo
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showMessage(data.error || 'Failed to upload photo.', 'error');
            }
        } catch (error) {
            showMessage('An error occurred: ' + error.message, 'error');
        } finally {
            uploadBtn.disabled = false;
            uploadBtn.textContent = 'Upload Photo';
        }
    });

    // Handle remove photo
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', async () => {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                try {
                    const response = await fetch('/account/remove-photo', {
                        method: 'POST'
                    });

                    const data = await response.json();

                    if (response.ok) {
                        showMessage('Profile photo removed successfully!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showMessage(data.error || 'Failed to remove photo.', 'error');
                    }
                } catch (error) {
                    showMessage('An error occurred: ' + error.message, 'error');
                }
            }
        });
    }

    function showMessage(message, type) {
        photoMessage.textContent = message;
        photoMessage.style.display = 'block';
        photoMessage.style.backgroundColor = type === 'success' ? '#d1fae5' : '#fee2e2';
        photoMessage.style.color = type === 'success' ? '#065f46' : '#991b1b';
        photoMessage.style.borderLeft = '4px solid ' + (type === 'success' ? '#10b981' : '#ef4444');
    }
});
</script>

