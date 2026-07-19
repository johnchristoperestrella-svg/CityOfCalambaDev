<?php
namespace App\Controllers;

use App\Models\Document;

class KnowledgeManagementController {
    private $documentModel;

    public function __construct() {
        if (!is_authenticated()) {
            redirect('/');
        }
        $this->documentModel = new Document();
    }

    public function index() {
        $router = new \Router();
        $documents = $this->documentModel->getAll();
        $categories = $this->documentModel->getByCategory();
        
        return $router->render('knowledge-management.index', [
            'user' => auth_user(),
            'documents' => $documents,
            'categories' => $categories,
            'totalDocuments' => $this->documentModel->getTotalCount(),
            'totalCategories' => $this->documentModel->getTotalCategories()
        ]);
    }

    public function getDocuments() {
        $category = $_GET['category'] ?? null;
        $documents = $this->documentModel->getAll($category);
        return response($documents, 200);
    }

    public function getCategories() {
        $categories = $this->documentModel->getByCategory();
        return response($categories, 200);
    }

    public function download() {
        $documentId = $_GET['id'] ?? null;
        
        if (!$documentId) {
            http_response_code(400);
            return response(['error' => 'Document ID required'], 400);
        }

        // Increment views when document is accessed
        $this->documentModel->incrementViews($documentId);

        return response(['success' => true, 'message' => 'View tracked'], 200);
    }

    public function uploadDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return response(['error' => 'Method not allowed'], 405);
        }

        if (!isset($_FILES['document']) || !isset($_POST['category'])) {
            http_response_code(400);
            return response(['error' => 'Missing required fields'], 400);
        }

        $file = $_FILES['document'];
        $category = sanitize_input($_POST['category']);
        $title = sanitize_input($_POST['title'] ?? $file['name']);

        $uploadDir = base_path('public/documents/');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->documentModel->create([
                'title' => $title,
                'category' => $category,
                'file_path' => 'documents/' . $fileName,
                'file_type' => $file['type']
            ]);

            return response(['success' => true, 'message' => 'Document uploaded'], 201);
        }

        http_response_code(500);
        return response(['error' => 'Upload failed'], 500);
    }
}
