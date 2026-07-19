<?php
namespace App\Controllers;

use App\Models\User;

class AccountController {
    private $userModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->userModel = new User();
    }

    public function index() {
        $router = new \Router();
        $user = auth_user();
        
        // Refresh user data from database to ensure profile_photo is up-to-date
        if ($user) {
            $freshUser = $this->userModel->findById($user['id']);
            if ($freshUser) {
                $user = $freshUser;
                // Also update session
                $_SESSION['user'] = $user;
            }
        }
        
        return $router->render('account.index', [
            'user' => $user
        ]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        $id = auth_id();
        $name = sanitize_input($_POST['name'] ?? '');
        
        if (empty($name)) {
            http_response_code(400);
            return response(['error' => 'Name is required'], 400);
        }

        if ($this->userModel->update($id, ['name' => $name])) {
            return response(['success' => true, 'message' => 'Profile updated'], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to update profile'], 500);
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        $id = auth_id();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            http_response_code(400);
            return response(['error' => 'All password fields are required'], 400);
        }

        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            return response(['error' => 'New passwords do not match'], 400);
        }

        if (strlen($newPassword) < 8) {
            http_response_code(400);
            return response(['error' => 'New password must be at least 8 characters'], 400);
        }

        // Verify current password
        $user = $this->userModel->findById($id);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            http_response_code(401);
            return response(['error' => 'Current password is incorrect'], 401);
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        if ($this->userModel->update($id, ['password' => $hashedPassword])) {
            return response(['success' => true, 'message' => 'Password changed successfully'], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to change password'], 500);
    }

    public function uploadProfilePhoto() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        $id = auth_id();
        
        // Check if file was uploaded
        if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return response(['error' => 'No file uploaded or upload error'], 400);
        }

        $file = $_FILES['profile_photo'];
        
        // Validate file size (max 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            return response(['error' => 'File size exceeds 5MB limit'], 400);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            http_response_code(400);
            return response(['error' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed'], 400);
        }

        // Create uploads directory if it doesn't exist
        $uploadDir = base_path('public/uploads/profile-photos');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'profile_' . $id . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . '/' . $fileName;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            http_response_code(500);
            return response(['error' => 'Failed to save uploaded file'], 500);
        }

        // Get the URL path for storage
        $photoPath = '/uploads/profile-photos/' . $fileName;

        // Delete old profile photo if it exists
        $user = $this->userModel->findById($id);
        if ($user && $user['profile_photo']) {
            $oldFilePath = base_path('public' . $user['profile_photo']);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        // Update user profile photo in database
        if ($this->userModel->update($id, ['profile_photo' => $photoPath])) {
            // Update session with new profile photo
            $_SESSION['user']['profile_photo'] = $photoPath;
            
            return response([
                'success' => true, 
                'message' => 'Profile photo uploaded successfully',
                'photo_url' => $photoPath
            ], 200);
        }

        http_response_code(500);
        return response(['error' => 'Failed to update profile photo'], 500);
    }

    public function removeProfilePhoto() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                return response(['error' => 'Method not allowed'], 405);
            }

            $id = auth_id();
            if (!$id) {
                http_response_code(401);
                return response(['error' => 'Unauthorized'], 401);
            }

            $user = $this->userModel->findById($id);

            if (!$user || empty($user['profile_photo'])) {
                http_response_code(400);
                return response(['error' => 'No profile photo to remove'], 400);
            }

            // Delete the file
            $filePath = base_path('public' . $user['profile_photo']);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            // Update user profile photo in database to NULL
            if ($this->userModel->update($id, ['profile_photo' => null])) {
                // Update session
                $_SESSION['user']['profile_photo'] = null;
                
                http_response_code(200);
                return response([
                    'success' => true, 
                    'message' => 'Profile photo removed successfully'
                ], 200);
            }

            http_response_code(500);
            return response(['error' => 'Failed to remove profile photo'], 500);
        } catch (Exception $e) {
            http_response_code(500);
            return response(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}