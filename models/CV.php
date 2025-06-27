<?php
class CV {
    private $id;
    private $personalInfo;
    private $experience;
    private $education;
    private $skills;
    private $profilePhoto;
    private $createdAt;
    
    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->personalInfo = $data['personal_info'] ?? [];
        $this->experience = $data['experience'] ?? [];
        $this->education = $data['education'] ?? [];
        $this->skills = $data['skills'] ?? [];
        $this->profilePhoto = $data['profile_photo'] ?? null;
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getPersonalInfo() { return $this->personalInfo; }
    public function getExperience() { return $this->experience; }
    public function getEducation() { return $this->education; }
    public function getSkills() { return $this->skills; }
    public function getProfilePhoto() { return $this->profilePhoto; }
    public function getCreatedAt() { return $this->createdAt; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setPersonalInfo($info) { $this->personalInfo = $info; }
    public function setExperience($exp) { $this->experience = $exp; }
    public function setEducation($edu) { $this->education = $edu; }
    public function setSkills($skills) { $this->skills = $skills; }
    public function setProfilePhoto($photo) { $this->profilePhoto = $photo; }
}



?>