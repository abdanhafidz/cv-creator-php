<?php
require_once 'lib/fpdf.php';

class PDFService {
    private $pdf;
    
    public function __construct() {
        $this->pdf = new FPDF();
    }
    
    public function generateCV(CV $cv) {
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        
        // Header
        $personalInfo = $cv->getPersonalInfo();
        $this->pdf->Cell(0, 10, $personalInfo['fullName'] ?? 'N/A', 0, 1, 'C');
        
        // Profile Photo
        if ($cv->getProfilePhoto()) {
            $photoPath = 'uploads/' . $cv->getProfilePhoto();
            if (file_exists($photoPath)) {
                $this->pdf->Image($photoPath, 10, 30, 30, 30);
            }
        }
        
        $this->pdf->Ln(40);
        
        // Personal Information
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'PERSONAL INFORMATION', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        $this->pdf->Cell(0, 8, 'Email: ' . ($personalInfo['email'] ?? 'N/A'), 0, 1);
        $this->pdf->Cell(0, 8, 'Phone: ' . ($personalInfo['phone'] ?? 'N/A'), 0, 1);
        $this->pdf->Cell(0, 8, 'Address: ' . ($personalInfo['address'] ?? 'N/A'), 0, 1);
        
        $this->pdf->Ln(10);
        
        // Experience
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'WORK EXPERIENCE', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        foreach ($cv->getExperience() as $exp) {
            $this->pdf->Cell(0, 8, $exp['position'] . ' at ' . $exp['company'], 0, 1);
            $this->pdf->Cell(0, 6, $exp['duration'], 0, 1);
            $this->pdf->MultiCell(0, 6, $exp['description']);
            $this->pdf->Ln(5);
        }
        
        // Education
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'EDUCATION', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        foreach ($cv->getEducation() as $edu) {
            $this->pdf->Cell(0, 8, $edu['degree'] . ' - ' . $edu['institution'], 0, 1);
            $this->pdf->Cell(0, 6, $edu['year'], 0, 1);
            $this->pdf->Ln(5);
        }
        
        // Skills
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'SKILLS', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        $skillsText = implode(', ', $cv->getSkills());
        $this->pdf->MultiCell(0, 8, $skillsText);
        
        return $this->pdf;
    }
    
    public function output($filename = 'CV.pdf') {
        $this->pdf->Output('D', $filename);
    }
}
?>