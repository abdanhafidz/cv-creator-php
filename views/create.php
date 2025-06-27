<!-- views/create.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create CV - CV Creator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .form-content {
            padding: 40px;
        }

        .form-section {
            margin-bottom: 40px;
            padding: 25px;
            border: 2px solid #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .section-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
            width: 100%;
        }

        .file-upload input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .file-upload:hover .file-upload-button {
            background: #e9ecef;
            border-color: #667eea;
            color: #667eea;
        }

        .dynamic-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            position: relative;
        }

        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .add-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-right: 15px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: #6c757d;
        }

        .form-actions {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .skills-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .skill-tag {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .skill-remove {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .form-content {
                padding: 20px;
            }
            
            .form-header h1 {
                font-size: 1.5rem;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="form-container" method="POST" action="?action=store" enctype="multipart/form-data" id="cvForm">
            <div class="form-header">
                <h1><i class="fas fa-file-alt"></i> Create Your CV</h1>
                <p>Fill in your details to create a professional CV</p>
            </div>
            
            <div class="form-content">
                <!-- Personal Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h2>
                    
                    <div class="form-group">
                        <label for="profilePhoto">Profile Photo</label>
                        <div class="file-upload">
                            <input type="file" id="profilePhoto" name="profilePhoto" accept="image/*">
                            <label for="profilePhoto" class="file-upload-button">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose Profile Photo</span>
                            </label>
                        </div>
                        <div id="photoPreview" style="margin-top: 15px; display: none;">
                            <img id="previewImg" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #667eea;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fullName">Full Name *</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"></textarea>
                    </div>
                </div>

                <!-- Work Experience -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-briefcase"></i> Work Experience
                    </h2>
                    
                    <div id="experienceContainer">
                        <div class="dynamic-section" data-section="experience">
                            <button type="button" class="remove-btn" onclick="removeSection(this)" style="display: none;">×</button>
                            
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
                        </div>
                    </div>
                    
                    <button type="button" class="add-btn" onclick="addExperience()">
                        <i class="fas fa-plus"></i> Add More Experience
                    </button>
                </div>

                <!-- Education -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-graduation-cap"></i> Education
                    </h2>
                    
                    <div id="educationContainer">
                        <div class="dynamic-section" data-section="education">
                            <button type="button" class="remove-btn" onclick="removeSection(this)" style="display: none;">×</button>
                            
                            <div class="form-group">
                                <label>Degree/Qualification</label>
                                <input type="text" name="education_degree[]" placeholder="e.g., Bachelor of Computer Science">
                            </div>
                            
                            <div class="form-group">
                                <label>Institution/University</label>
                                <input type="text" name="education_institution[]" placeholder="e.g., University of Technology">
                            </div>
                            
                            <div class="form-group">
                                <label>Year</label>
                                <input type="text" name="education_year[]" placeholder="e.g., 2018 - 2022">
                            </div>
                            
                            <div class="form-group">
                                <label>Grade/GPA (Optional)</label>
                                <input type="text" name="education_grade[]" placeholder="e.g., 3.8/4.0">
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="add-btn" onclick="addEducation()">
                        <i class="fas fa-plus"></i> Add More Education
                    </button>
                </div>

                <!-- Skills -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i> Skills
                    </h2>
                    
                    <div class="form-group">
                        <label for="skillInput">Add Skills</label>
                        <input type="text" id="skillInput" placeholder="Type a skill and press Enter">
                        <small style="color: #666; margin-top: 5px; display: block;">
                            Type a skill and press Enter to add it
                        </small>
                    </div>
                    
                    <input type="hidden" id="skillsData" name="skills">
                    
                    <div class="skills-container" id="skillsContainer">
                        <!-- Skills will be added here dynamically -->
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i> Create CV
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Skills Management
        let skills = [];
        
        document.getElementById('skillInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });
        
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
        
        function removeSkill(skill) {
            skills = skills.filter(s => s !== skill);
            updateSkillsDisplay();
            updateSkillsData();
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
        
        function updateSkillsData() {
            document.getElementById('skillsData').value = JSON.stringify(skills);
        }
        
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
        
        // Education Management
        function addEducation() {
            const container = document.getElementById('educationContainer');
            const newEducation = document.createElement('div');
            newEducation.className = 'dynamic-section';
            newEducation.setAttribute('data-section', 'education');
            
            newEducation.innerHTML = `
                <button type="button" class="remove-btn" onclick="removeSection(this)">×</button>
                
                <div class="form-group">
                    <label>Degree/Qualification</label>
                    <input type="text" name="education_degree[]" placeholder="e.g., Bachelor of Computer Science">
                </div>
                
                <div class="form-group">
                    <label>Institution/University</label>
                    <input type="text" name="education_institution[]" placeholder="e.g., University of Technology">
                </div>
                
                <div class="form-group">
                    <label>Year</label>
                    <input type="text" name="education_year[]" placeholder="e.g., 2018 - 2022">
                </div>
                
                <div class="form-group">
                    <label>Grade/GPA (Optional)</label>
                    <input type="text" name="education_grade[]" placeholder="e.g., 3.8/4.0">
                </div>
            `;
            
            container.appendChild(newEducation);
            updateRemoveButtons('education');
        }
        
        // Remove Section
        function removeSection(button) {
            const section = button.closest('.dynamic-section');
            const sectionType = section.getAttribute('data-section');
            section.remove();
            updateRemoveButtons(sectionType);
        }
        
        // Update Remove Buttons Visibility
        function updateRemoveButtons(sectionType) {
            const container = document.getElementById(sectionType + 'Container');
            const sections = container.querySelectorAll('.dynamic-section');
            
            sections.forEach((section, index) => {
                const removeBtn = section.querySelector('.remove-btn');
                if (sections.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }
        
        // Photo Preview
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
        
        // Form Submission Handler
        document.getElementById('cvForm').addEventListener('submit', function(e) {
            // Process experience data
            const experienceData = [];
            const positions = document.querySelectorAll('input[name="experience_position[]"]');
            const companies = document.querySelectorAll('input[name="experience_company[]"]');
            const durations = document.querySelectorAll('input[name="experience_duration[]"]');
            const descriptions = document.querySelectorAll('textarea[name="experience_description[]"]');
            
            for (let i = 0; i < positions.length; i++) {
                if (positions[i].value.trim()) {
                    experienceData.push({
                        position: positions[i].value.trim(),
                        company: companies[i].value.trim(),
                        duration: durations[i].value.trim(),
                        description: descriptions[i].value.trim()
                    });
                }
            }
            
            // Process education data
            const educationData = [];
            const degrees = document.querySelectorAll('input[name="education_degree[]"]');
            const institutions = document.querySelectorAll('input[name="education_institution[]"]');
            const years = document.querySelectorAll('input[name="education_year[]"]');
            const grades = document.querySelectorAll('input[name="education_grade[]"]');
            
            for (let i = 0; i < degrees.length; i++) {
                if (degrees[i].value.trim()) {
                    educationData.push({
                        degree: degrees[i].value.trim(),
                        institution: institutions[i].value.trim(),
                        year: years[i].value.trim(),
                        grade: grades[i].value.trim()
                    });
                }
            }
            
            // Add hidden inputs for processed data
            const experienceInput = document.createElement('input');
            experienceInput.type = 'hidden';
            experienceInput.name = 'experience';
            experienceInput.value = JSON.stringify(experienceData);
            this.appendChild(experienceInput);
            
            const educationInput = document.createElement('input');
            educationInput.type = 'hidden';
            educationInput.name = 'education';
            educationInput.value = JSON.stringify(educationData);
            this.appendChild(educationInput);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating CV...';
            submitBtn.disabled = true;
            
            // Re-enable button after a delay (in case of validation errors)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons('experience');
            updateRemoveButtons('education');
        });
    </script>
</body>
</html>