<?php
class CVController {
    private $cvRepository;
    private $fileUploadService;
    private $pdfService;
    
    public function __construct() {
        $this->cvRepository = new CVRepository();
        $this->fileUploadService = new FileUploadService();
        $this->pdfService = new PDFService();
    }
    
    public function index() {
        $cvs = $this->cvRepository->findAll();
        require_once 'views/index.php';
    }
    
    public function create() {
        require_once 'views/create.php';
    }
    
    public function store() {
        try {
            $profilePhoto = null;
            if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
                $profilePhoto = $this->fileUploadService->uploadImage($_FILES['profilePhoto']);
            }
            
            $cv = new CV([
                'personal_info' => [
                    'fullName' => $_POST['fullName'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? ''
                ],
                'experience' => json_decode($_POST['experience'] ?? '[]', true),
                'education' => json_decode($_POST['education'] ?? '[]', true),
                'skills' => json_decode($_POST['skills'] ?? '[]', true),
                'profile_photo' => $profilePhoto
            ]);
            
            if ($this->cvRepository->save($cv)) {
                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            header('Location: index.php?error=' . urlencode($e->getMessage()));
            exit;
        }
    }
    
    public function generatePDF($id) {
        $cv = $this->cvRepository->findById($id);
        if (!$cv) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        
        $pdf = $this->pdfService->generateCV($cv);
        $personalInfo = $cv->getPersonalInfo();
        $filename = ($personalInfo['fullName'] ?? 'CV') . '.pdf';
        
        $this->pdfService->output($filename);
    }
}
?>