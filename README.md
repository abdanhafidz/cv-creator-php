# CV Creator - Aplikasi Pembuat CV Online

## 📋 Daftar Isi
- [Overview](#overview)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Arsitektur Aplikasi](#arsitektur-aplikasi)
- [Struktur File](#struktur-file)
- [Implementasi Fitur Kunci](#implementasi-fitur-kunci)
- [Instalasi dan Setup](#instalasi-dan-setup)
- [Cara Penggunaan](#cara-penggunaan)
- [Database Schema](#database-schema)
- [Security Considerations](#security-considerations)
- [Kesimpulan](#kesimpulan)

## 📖 Overview

CV Creator adalah aplikasi web berbasis PHP yang memungkinkan pengguna untuk membuat Curriculum Vitae (CV) secara online dengan mudah dan profesional. Aplikasi ini menggunakan pola arsitektur MVC (Model-View-Controller) dan menyediakan fitur export ke PDF serta upload foto profil.

### Tujuan Aplikasi
- Mempermudah pembuatan CV dengan interface yang user-friendly
- Menyediakan template CV yang profesional
- Memungkinkan export CV dalam format PDF
- Menyimpan data CV untuk dapat diakses kembali

## ✨ Fitur Utama

### 1. **Manajemen Data Personal**
- Input informasi pribadi (nama, email, telepon, alamat)
- Upload foto profil dengan preview
- Validasi input data

### 2. **Manajemen Pengalaman Kerja**
- Tambah/hapus pengalaman kerja secara dinamis
- Input detail posisi, perusahaan, durasi, dan deskripsi
- Interface yang responsive

### 3. **Manajemen Pendidikan**
- Tambah/hapus riwayat pendidikan
- Input detail gelar, institusi, tahun, dan nilai
- Form dinamis yang mudah digunakan

### 4. **Manajemen Skills**
- Tambah skills dengan system tag
- Hapus skills secara individual
- Interface yang interaktif

### 5. **Export PDF**
- Generate CV dalam format PDF menggunakan FPDF
- Layout profesional dan terstruktur
- Include foto profil dalam PDF

### 6. **Galeri CV**
- Tampilkan semua CV yang telah dibuat
- Preview informasi CV
- Akses cepat untuk download PDF

## 🛠 Teknologi yang Digunakan

### Backend
- **PHP 7.4+** - Server-side programming
- **MySQL** - Database management
- **PDO** - Database abstraction layer
- **FPDF** - PDF generation library

### Frontend
- **HTML5** - Structure markup
- **CSS3** - Styling dan layout
- **JavaScript (Vanilla)** - Interactive functionality
- **Font Awesome** - Icons

### Design Pattern
- **MVC (Model-View-Controller)** - Architectural pattern
- **Singleton Pattern** - Database connection
- **Repository Pattern** - Data access layer
- **Service Layer Pattern** - Business logic separation

## 🏗 Arsitektur Aplikasi

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

### Penjelasan Layer:
- **Presentation**: Handle UI dan user interaction
- **Controller**: Orchestrate business flow
- **Service**: Business logic dan external operations
- **Repository**: Data access abstraction
- **Model**: Data representation
- **Infrastructure**: External dependencies (database)

## 📁 Struktur File

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

## 🔧 Implementasi Fitur Kunci

### 1. **Upload Image dengan Validation**

#### FileUploadService.php - Implementasi Detail

```php
class FileUploadService {
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;
    
    public function __construct() {
        // Konfigurasi upload
        $this->uploadDir = 'uploads/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB
        
        // Buat direktori jika belum ada
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function uploadImage($file) {
        // Validasi file
        if (!$this->validateFile($file)) {
            throw new Exception('File validation failed');
        }
        
        // Generate nama file unik
        $filename = $this->generateUniqueFilename($file['name']);
        $targetPath = $this->uploadDir . $filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $filename;
        }
        
        throw new Exception('Failed to upload file');
    }
    
    private function validateFile($file) {
        // Cek error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Cek ukuran file
        if ($file['size'] > $this->maxSize) {
            return false;
        }
        
        // Cek tipe file
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

#### Frontend Implementation - Photo Upload dengan Preview

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

#### Keamanan Upload:
- **Validasi tipe file** menggunakan MIME type
- **Batasan ukuran file** maksimal 5MB
- **Nama file unik** untuk mencegah collision
- **Direktori terpisah** untuk uploaded files
- **Validasi error upload** dari $_FILES

### 2. **Export PDF menggunakan FPDF**

#### PDFService.php - Implementasi Detail

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
        
        // Header dengan nama
        $personalInfo = $cv->getPersonalInfo();
        $this->pdf->Cell(0, 10, $personalInfo['fullName'] ?? 'N/A', 0, 1, 'C');
        
        // Profile Photo dengan positioning
        if ($cv->getProfilePhoto()) {
            $photoPath = 'uploads/' . $cv->getProfilePhoto();
            if (file_exists($photoPath)) {
                // Posisi: X=10, Y=30, Width=30, Height=30
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
            // Job title dan company
            $this->pdf->Cell(0, 8, $exp['position'] . ' at ' . $exp['company'], 0, 1);
            
            // Duration
            $this->pdf->Cell(0, 6, $exp['duration'], 0, 1);
            
            // Description dengan word wrap
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
        // Download file PDF
        $this->pdf->Output('D', $filename);
    }
}
```

#### Fitur FPDF yang Digunakan:
- **AddPage()**: Membuat halaman baru
- **SetFont()**: Mengatur font dan ukuran
- **Cell()**: Membuat cell untuk teks
- **MultiCell()**: Cell dengan word wrap
- **Image()**: Insert gambar dengan positioning
- **Ln()**: Line break
- **Output()**: Export PDF (D = Download)

### 3. **Dynamic Form Management**

#### JavaScript Implementation untuk Dynamic Sections

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

// Skills Management dengan Tag System
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

## ⚙️ Instalasi dan Setup

### Persyaratan System
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- FPDF library

### Langkah Instalasi

1. **Clone atau Download Project**
```bash
git clone [repository-url]
cd cv-creator
```

2. **Setup Database**
```sql
-- Buat database
CREATE DATABASE cv_creator;

-- Import schema
mysql -u username -p cv_creator < create_tables.sql
```

3. **Konfigurasi Database**
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
- Download FPDF dari http://www.fpdf.org/
- Extract ke folder `fpdf/`

6. **Setup Virtual Host (Optional)**
```apache
<VirtualHost *:80>
    DocumentRoot "/path/to/cv-creator"
    ServerName cv-creator.local
</VirtualHost>
```

## 📱 Cara Penggunaan

### 1. Mengakses Aplikasi
- Buka browser dan akses URL aplikasi
- Anda akan melihat halaman utama dengan daftar CV

### 2. Membuat CV Baru
1. Klik tombol "Create New CV"
2. Isi formulir dengan informasi pribadi
3. Upload foto profil (opsional)
4. Tambah pengalaman kerja
5. Tambah riwayat pendidikan
6. Tambah skills
7. Klik "Create CV"

### 3. Mengelola CV
- Lihat daftar CV di halaman utama
- Download CV dalam format PDF
- Setiap CV menampilkan preview informasi

### 4. Export PDF
- Klik tombol "Download PDF" pada CV yang diinginkan
- File PDF akan otomatis terdownload
- PDF berisi semua informasi yang telah diinput

## 🗄 Database Schema

### Tabel: `cvs`

```sql
CREATE TABLE cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    personal_info JSON,           -- Data personal (nama, email, phone, address)
    experience JSON,              -- Array pengalaman kerja
    education JSON,               -- Array riwayat pendidikan
    skills JSON,                  -- Array skills
    profile_photo VARCHAR(255),   -- Nama file foto profil
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_created_at ON cvs(created_at);
```

### Format Data JSON

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
- **Validasi tipe file** menggunakan MIME type
- **Batasan ukuran file** untuk mencegah DoS
- **Nama file random** untuk mencegah path traversal
- **Direktori upload** di luar web root (recommended)

### 2. Database Security
- **Prepared statements** untuk mencegah SQL injection
- **Input validation** dan sanitization
- **Error handling** yang tidak expose sensitive info

### 3. General Security
- **Input validation** di semua form fields
- **Output escaping** menggunakan `htmlspecialchars()`
- **Session management** untuk authentication (jika ditambahkan)

### Contoh Implementasi Security:

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
- **Index** pada kolom yang sering di-query
- **JSON** data type untuk flexible schema
- **Connection pooling** dengan Singleton pattern

### 2. File Handling
- **Unique filename** generation
- **Lazy loading** untuk images
- **File size optimization**

### 3. Frontend Optimization
- **Vanilla JavaScript** untuk minimal overhead
- **CSS minification** (production)
- **Image compression** for uploads

## 🔧 Customization Options

### 1. PDF Template Customization
```php
// Modifikasi layout PDF
class PDFService {
    private function customizeLayout() {
        // Ubah font
        $this->pdf->SetFont('Times', 'B', 18);
        
        // Ubah warna
        $this->pdf->SetTextColor(50, 50, 50);
        
        // Tambah header/footer
        $this->addHeader();
        $this->addFooter();
    }
}
```

### 2. Form Field Customization
- Tambah field baru di model CV
- Update database schema
- Modifikasi form di create.php
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

## 📝 Kesimpulan

CV Creator adalah aplikasi web yang demonstrasi implementasi komprehensif dari berbagai konsep pemrograman web modern:

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
Aplikasi ini mendemonstrasikan pemahaman mendalam tentang:
- **Backend Development** dengan PHP dan MySQL
- **Frontend Development** dengan HTML, CSS, dan JavaScript
- **File Handling** dan upload management
- **PDF Generation** library integration
- **Database Design** dan optimization
- **Security Best Practices**
- **Code Organization** dan architecture patterns

Aplikasi CV Creator ini merupakan foundation yang solid untuk pengembangan aplikasi web yang lebih kompleks dan dapat dijadikan portfolio project yang menunjukkan kemampuan full-stack development.

---

**Dibuat untuk Mata Kuliah Pemrograman Website**  
**Teknologi:** PHP, MySQL, JavaScript, HTML5, CSS3, FPDF  
**Pattern:** MVC, Repository, Service Layer, Singleton
