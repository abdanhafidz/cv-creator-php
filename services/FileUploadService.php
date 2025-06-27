<?php

class FileUploadService {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    
    public function __construct() {
        $this->uploadDir = 'uploads/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function uploadImage($file) {
        if (!$this->validateFile($file)) {
            throw new Exception('File validation failed');
        }
        
        $filename = $this->generateUniqueFilename($file['name']);
        $targetPath = $this->uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        
        throw new Exception('Failed to upload file');
    }
    
    private function validateFile($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        if ($file['size'] > $this->maxSize) {
            return false;
        }
        
        if (!in_array($file['type'], $this->allowedTypes)) {
            return false;
        }
        
        return true;
    }
    
    private function generateUniqueFilename($originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $extension;
    }
}

?>