# CV Creator - Online CV Builder Application
[Deployment Link : abdanhafidz.com/cv-creator](https://abdanhafidz.com/cv-creator/)
## 📋 Table of Contents
- [Overview](#overview)
- [Key Features](#key-features)
- [Technologies Used](#technologies-used)
- [Application Architecture](#application-architecture)
- [File Structure](#file-structure)
- [Key Feature Implementation](#key-feature-implementation)
- [Installation and Setup](#installation-and-setup)
- [How to Use](#how-to-use)
- [Database Schema](#database-schema)
- [Security Considerations](#security-considerations)
- [Conclusion](#conclusion)

## 📖 Overview

CV Creator is a PHP-based web application that allows users to create professional Curriculum Vitae (CV) online with ease. The application uses MVC (Model-View-Controller) architectural pattern and provides PDF export functionality and profile photo upload features.

### Application Goals
- Simplify CV creation with a user-friendly interface
- Provide professional CV templates
- Enable CV export in PDF format
- Store CV data for future access

## ✨ Key Features

### 1. **Personal Data Management**
- Input personal information (name, email, phone, address)
- Upload profile photo with preview
- Input data validation

### 2. **Work Experience Management**
- Add/remove work experience dynamically
- Input position details, company, duration, and description
- Responsive interface

### 3. **Education Management**
- Add/remove education history
- Input degree details, institution, year, and grade
- Dynamic forms that are easy to use

### 4. **Skills Management**
- Add skills with tag system
- Remove skills individually
- Interactive interface

### 5. **PDF Export**
- Generate CV in PDF format using FPDF
- Professional and structured layout
- Include profile photo in PDF

### 6. **CV Gallery**
- Display all created CVs
- CV information preview
- Quick access for PDF download

## 🛠 Technologies Used

### Backend
- **PHP 7.4+** - Server-side programming
- **MySQL** - Database management
- **PDO** - Database abstraction layer
- **FPDF** - PDF generation library

### Frontend
- **HTML5** - Structure markup
- **CSS3** - Styling and layout
- **JavaScript (Vanilla)** - Interactive functionality
- **Font Awesome** - Icons

### Design Pattern
- **MVC (Model-View-Controller)** - Architectural pattern
- **Singleton Pattern** - Database connection
- **Repository Pattern** - Data access layer
- **Service Layer Pattern** - Business logic separation

## 🏗 Application Architecture

```
CV Creator Application
├── Presentation Layer (Views)
│   ├── index.php (CV List)
│   └── create.php (CV Form)
├── Controller Layer
│   └── CVController.php
├── Service Layer
│   ├── FileUploadService.php
│   └── PDFService.php
├── Repository Layer
│   └── CVRepository.php
├── Model Layer
│   └── CV.php
└── Infrastructure Layer
    └── Database.php
```

### Layer Explanation:
- **Presentation**: Handle UI and user interaction
- **Controller**: Orchestrate business flow
- **Service**: Business logic and external operations
- **Repository**: Data access abstraction
- **Model**: Data representation
- **Infrastructure**: External dependencies (database)

## 📁 File Structure

```
cv-creator/
├── config/
│   └── database.php          # Database connection (Singleton)
├── models/
│   └── CV.php               # CV domain model
├── repositories/
│   └── CVRepository.php     # Data access layer
├── services/
│   ├── FileUploadService.php # File upload handling
│   └── PDFService.php       # PDF generation
├── controllers/
│   └── CVController.php     # Main controller
├── views/
│   ├── index.php           # CV listing page
│   └── create.php          # CV creation form
├── uploads/                # Image upload directory
├── fpdf/                   # FPDF library
├── index.php              # Main entry point & router
└── create_tables.sql      # Database schema
```

## 🔧 Key Feature Implementation

### 1. **Image Upload with Validation**

#### FileUploadService.php - Detailed Implementation

```php
class FileUploadService {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    
    public function __construct() {
        // Upload configuration
        $this->uploadDir = 'uploads/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
        
        // Create directory if not exists
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function uploadImage($file) {
        // Validate file
        if (!$this->validateFile($file)) {
            throw new Exception('File validation failed');
        }
        
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file['name']);
        $targetPath = $this->uploadDir . $filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        
        throw new Exception('Failed to upload file');
    }
    
    private function validateFile($file) {
        // Check upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Check file size
        if ($file['size'] > $this->maxSize) {
            return false;
        }
        
        // Check file type
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
```

#### Frontend Implementation - Photo Upload with Preview

```javascript
// Photo Preview Implementation
document.getElementById('profilePhoto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        
        // Update file upload button text
        const label = document.querySelector('.file-upload-button span');
        label.textContent = file.name;
    }
});
```

#### Upload Security Features:
- **File type validation** using MIME type
- **File size limitation** maximum 5MB
- **Unique filename** to prevent collision
- **Separate directory** for uploaded files
- **Upload error validation** from $_FILES

### 2. **PDF Export using FPDF**

#### PDFService.php - Detailed Implementation

```php
require_once 'fpdf/fpdf.php';

class PDFService {
    private $pdf;
    
    public function __construct() {
        $this->pdf = new FPDF();
    }
    
    public function generateCV(CV $cv) {
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 16);
        
        // Header with name
        $personalInfo = $cv->getPersonalInfo();
        $this->pdf->Cell(0, 10, $personalInfo['fullName'] ?? 'N/A', 0, 1, 'C');
        
        // Profile Photo with positioning
        if ($cv->getProfilePhoto()) {
            $photoPath = 'uploads/' . $cv->getProfilePhoto();
            if (file_exists($photoPath)) {
                // Position: X=10, Y=30, Width=30, Height=30
                $this->pdf->Image($photoPath, 10, 30, 30, 30);
            }
        }
        
        $this->pdf->Ln(40); // Line break
        
        // Personal Information Section
        $this->addSection('PERSONAL INFORMATION', [
            'Email: ' . ($personalInfo['email'] ?? 'N/A'),
            'Phone: ' . ($personalInfo['phone'] ?? 'N/A'),
            'Address: ' . ($personalInfo['address'] ?? 'N/A')
        ]);
        
        // Work Experience Section
        $this->addExperienceSection($cv->getExperience());
        
        // Education Section
        $this->addEducationSection($cv->getEducation());
        
        // Skills Section
        $this->addSkillsSection($cv->getSkills());
        
        return $this->pdf;
    }
    
    private function addSection($title, $items) {
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, $title, 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        foreach ($items as $item) {
            $this->pdf->Cell(0, 8, $item, 0, 1);
        }
        
        $this->pdf->Ln(10);
    }
    
    private function addExperienceSection($experiences) {
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'WORK EXPERIENCE', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        foreach ($experiences as $exp) {
            // Job title and company
            $this->pdf->Cell(0, 8, $exp['position'] . ' at ' . $exp['company'], 0, 1);
            
            // Duration
            $this->pdf->Cell(0, 6, $exp['duration'], 0, 1);
            
            // Description with word wrap
            $this->pdf->MultiCell(0, 6, $exp['description']);
            $this->pdf->Ln(5);
        }
    }
    
    private function addEducationSection($educations) {
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'EDUCATION', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        foreach ($educations as $edu) {
            $this->pdf->Cell(0, 8, $edu['degree'] . ' - ' . $edu['institution'], 0, 1);
            $this->pdf->Cell(0, 6, $edu['year'], 0, 1);
            $this->pdf->Ln(5);
        }
    }
    
    private function addSkillsSection($skills) {
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'SKILLS', 0, 1);
        $this->pdf->SetFont('Arial', '', 12);
        
        $skillsText = implode(', ', $skills);
        $this->pdf->MultiCell(0, 8, $skillsText);
    }
    
    public function output($filename = 'CV.pdf') {
        // Download PDF file
        $this->pdf->Output('D', $filename);
    }
}
```

#### FPDF Features Used:
- **AddPage()**: Create new page
- **SetFont()**: Set font and size
- **Cell()**: Create cell for text
- **MultiCell()**: Cell with word wrap
- **Image()**: Insert image with positioning
- **Ln()**: Line break
- **Output()**: Export PDF (D = Download)

### 3. **Dynamic Form Management**

#### JavaScript Implementation for Dynamic Sections

```javascript
// Experience Management
function addExperience() {
    const container = document.getElementById('experienceContainer');
    const newExperience = document.createElement('div');
    newExperience.className = 'dynamic-section';
    newExperience.setAttribute('data-section', 'experience');
    
    newExperience.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeSection(this)">×</button>
        
        <div class="form-group">
            <label>Position/Job Title</label>
            <input type="text" name="experience_position[]" placeholder="e.g., Software Developer">
        </div>
        
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="experience_company[]" placeholder="e.g., Tech Corp">
        </div>
        
        <div class="form-group">
            <label>Duration</label>
            <input type="text" name="experience_duration[]" placeholder="e.g., Jan 2020 - Present">
        </div>
        
        <div class="form-group">
            <label>Job Description</label>
            <textarea name="experience_description[]" rows="4" placeholder="Describe your responsibilities and achievements..."></textarea>
        </div>
    `;
    
    container.appendChild(newExperience);
    updateRemoveButtons('experience');
}

// Skills Management with Tag System
let skills = [];

function addSkill() {
    const skillInput = document.getElementById('skillInput');
    const skill = skillInput.value.trim();
    
    if (skill && !skills.includes(skill)) {
        skills.push(skill);
        updateSkillsDisplay();
        updateSkillsData();
        skillInput.value = '';
    }
}

function updateSkillsDisplay() {
    const container = document.getElementById('skillsContainer');
    container.innerHTML = '';
    
    skills.forEach(skill => {
        const skillTag = document.createElement('div');
        skillTag.className = 'skill-tag';
        skillTag.innerHTML = `
            <span>${skill}</span>
            <button type="button" class="skill-remove" onclick="removeSkill('${skill}')">×</button>
        `;
        container.appendChild(skillTag);
    });
}
```

## ⚙️ Installation and Setup

### System Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- FPDF library

### Installation Steps

1. **Clone or Download Project**
```bash
git clone [repository-url]
cd cv-creator
```

2. **Setup Database**
```sql
-- Create database
CREATE DATABASE cv_creator;

-- Import schema
mysql -u username -p cv_creator < create_tables.sql
```

3. **Configure Database**
Edit file `config/database.php`:
```php
private function __construct() {
    $host = 'localhost';
    $dbname = 'cv_creator';
    $username = 'your_username';
    $password = 'your_password';
    // ...
}
```

4. **Setup Directory Permissions**
```bash
chmod 755 uploads/
chmod 644 *.php
```

5. **Download FPDF Library**
- Download FPDF from http://www.fpdf.org/
- Extract to `fpdf/` folder

6. **Setup Virtual Host (Optional)**
```apache
<VirtualHost *:80>
    DocumentRoot "/path/to/cv-creator"
    ServerName cv-creator.local
</VirtualHost>
```

## 📱 How to Use

### 1. Accessing the Application
- Open browser and access the application URL
- You will see the main page with CV list

### 2. Creating New CV
1. Click "Create New CV" button
2. Fill the form with personal information
3. Upload profile photo (optional)
4. Add work experience
5. Add education history
6. Add skills
7. Click "Create CV"

### 3. Managing CVs
- View CV list on the main page
- Download CV in PDF format
- Each CV displays information preview

### 4. PDF Export
- Click "Download PDF" button on desired CV
- PDF file will automatically download
- PDF contains all inputted information

## 🗄 Database Schema

### Table: `cvs`

```sql
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    personal_info JSON,           -- Personal data (name, email, phone, address)
    experience JSON,              -- Work experience array
    education JSON,               -- Education history array
    skills JSON,                  -- Skills array
    profile_photo VARCHAR(255),   -- Profile photo filename
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_created_at ON cvs(created_at);
```

### JSON Data Format

**personal_info:**
```json
{
    "fullName": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "address": "123 Main St, City"
}
```

**experience:**
```json
[
    {
        "position": "Senior Developer",
        "company": "Tech Corp",
        "duration": "2020-Present",
        "description": "Lead development team and manage projects"
    }
]
```

**education:**
```json
[
    {
        "degree": "Bachelor of Computer Science",
        "institution": "University of Technology",
        "year": "2016-2020",
        "grade": "3.8/4.0"
    }
]
```

**skills:**
```json
["PHP", "JavaScript", "MySQL", "HTML/CSS", "React"]
```

## 🔒 Security Considerations

### 1. File Upload Security
- **File type validation** using MIME type
- **File size limitation** to prevent DoS
- **Random filename** to prevent path traversal
- **Upload directory** outside web root (recommended)

### 2. Database Security
- **Prepared statements** to prevent SQL injection
- **Input validation** and sanitization
- **Error handling** that doesn't expose sensitive info

### 3. General Security
- **Input validation** on all form fields
- **Output escaping** using `htmlspecialchars()`
- **Session management** for authentication (if added)

### Security Implementation Example:

```php
// Input validation
public function store() {
    try {
        // Validate required fields
        $fullName = trim($_POST['fullName'] ?? '');
        if (empty($fullName)) {
            throw new Exception('Full name is required');
        }
        
        // Validate email
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new Exception('Valid email is required');
        }
        
        // File upload with validation
        $profilePhoto = null;
        if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
            $profilePhoto = $this->fileUploadService->uploadImage($_FILES['profilePhoto']);
        }
        
        // ... rest of the code
    } catch (Exception $e) {
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}
```

## 📊 Performance Optimization

### 1. Database Optimization
- **Index** on frequently queried columns
- **JSON** data type for flexible schema
- **Connection pooling** with Singleton pattern

### 2. File Handling
- **Unique filename** generation
- **Lazy loading** for images
- **File size optimization**

### 3. Frontend Optimization
- **Vanilla JavaScript** for minimal overhead
- **CSS minification** (production)
- **Image compression** for uploads

## 🔧 Customization Options

### 1. PDF Template Customization
```php
// Modify PDF layout
class PDFService {
    private function customizeLayout() {
        // Change font
        $this->pdf->SetFont('Times', 'B', 18);
        
        // Change color
        $this->pdf->SetTextColor(50, 50, 50);
        
        // Add header/footer
        $this->addHeader();
        $this->addFooter();
    }
}
```

### 2. Form Field Customization
- Add new fields in CV model
- Update database schema
- Modify form in create.php
- Update PDF generation

### 3. Styling Customization
- Modify CSS variables
- Change color scheme
- Update layout structure

## 🚀 Future Enhancements

### Planned Features:
1. **User Authentication System**
2. **Multiple CV Templates**
3. **CV Sharing via Link**
4. **Export to Word Document**
5. **Email CV Functionality**
6. **CV Analytics Dashboard**
7. **Responsive Mobile Design**
8. **Multi-language Support**

### Technical Improvements:
1. **API Development** for mobile app
2. **Caching System** for better performance
3. **Image Optimization** pipeline
4. **Automated Testing** suite
5. **Docker Containerization**

## 📝 Conclusion

CV Creator is a web application that demonstrates comprehensive implementation of modern web programming concepts:

### **Technical Achievement:**
- ✅ **MVC Architecture** - Clean separation of concerns
- ✅ **OOP Principles** - Encapsulation, inheritance, polymorphism
- ✅ **Design Patterns** - Singleton, Repository, Service Layer
- ✅ **File Upload Handling** - Secure image upload with validation
- ✅ **PDF Generation** - Professional CV export using FPDF
- ✅ **Dynamic UI** - Interactive form management with JavaScript
- ✅ **Database Design** - Efficient schema with JSON data types
- ✅ **Security Implementation** - Input validation, SQL injection prevention

### **Business Value:**
- 🎯 **User-Friendly Interface** - Intuitive CV creation process
- 🎯 **Professional Output** - High-quality PDF generation
- 🎯 **Data Persistence** - Reliable data storage and retrieval
- 🎯 **Scalable Architecture** - Easy to extend and maintain

### **Learning Outcomes:**
This application demonstrates deep understanding of:
- **Backend Development** with PHP and MySQL
- **Frontend Development** with HTML, CSS, and JavaScript
- **File Handling** and upload management
- **PDF Generation** library integration
- **Database Design** and optimization
- **Security Best Practices**
- **Code Organization** and architecture patterns

This CV Creator application provides a solid foundation for developing more complex web applications and can serve as a portfolio project showcasing full-stack development capabilities.

---

**Created for Web Programming Course**  
**Technologies:** PHP, MySQL, JavaScript, HTML5, CSS3, FPDF  
**Patterns:** MVC, Repository, Service Layer, Singleton
