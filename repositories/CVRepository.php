<?php
// repositories/CVRepository.php
interface CVRepositoryInterface {
    public function save(CV $cv);
    public function findById($id);
    public function findAll();
    public function delete($id);
}

class CVRepository implements CVRepositoryInterface {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function save(CV $cv) {
        if ($cv->getId()) {
            return $this->update($cv);
        } else {
            return $this->create($cv);
        }
    }
    
    private function create(CV $cv) {
        $sql = "INSERT INTO cvs (personal_info, experience, education, skills, profile_photo, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            json_encode($cv->getPersonalInfo()),
            json_encode($cv->getExperience()),
            json_encode($cv->getEducation()),
            json_encode($cv->getSkills()),
            $cv->getProfilePhoto(),
            $cv->getCreatedAt()
        ]);
        
        if ($result) {
            $cv->setId($this->db->lastInsertId());
        }
        
        return $result;
    }
    
    private function update(CV $cv) {
        $sql = "UPDATE cvs SET personal_info = ?, experience = ?, education = ?, 
                skills = ?, profile_photo = ? WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            json_encode($cv->getPersonalInfo()),
            json_encode($cv->getExperience()),
            json_encode($cv->getEducation()),
            json_encode($cv->getSkills()),
            $cv->getProfilePhoto(),
            $cv->getId()
        ]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM cvs WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $data['personal_info'] = json_decode($data['personal_info'], true);
            $data['experience'] = json_decode($data['experience'], true);
            $data['education'] = json_decode($data['education'], true);
            $data['skills'] = json_decode($data['skills'], true);
            return new CV($data);
        }
        
        return null;
    }
    
    public function findAll() {
        $sql = "SELECT * FROM cvs ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        
        $cvs = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data['personal_info'] = json_decode($data['personal_info'], true);
            $data['experience'] = json_decode($data['experience'], true);
            $data['education'] = json_decode($data['education'], true);
            $data['skills'] = json_decode($data['skills'], true);
            $cvs[] = new CV($data);
        }
        
        return $cvs;
    }
    
    public function delete($id) {
        $sql = "DELETE FROM cvs WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}

?>