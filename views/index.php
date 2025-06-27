<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Creator</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
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
            margin: 20px 0;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .cv-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .cv-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .cv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }

        .cv-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .cv-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .cv-info h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .cv-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .cv-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 25px;
        }

        .btn-pdf {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .cv-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-alt"></i> CV Creator</h1>
            <p>Create professional CVs with ease</p>
            <a href="?action=create" class="btn">
                <i class="fas fa-plus"></i> Create New CV
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> CV created successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> Error: <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="cv-grid">
            <?php foreach ($cvs as $cv): ?>
                <?php $personalInfo = $cv->getPersonalInfo(); ?>
                <div class="cv-card">
                    <div class="cv-header">
                        <div class="cv-avatar">
                            <?php if ($cv->getProfilePhoto()): ?>
                                <img src="uploads/<?= htmlspecialchars($cv->getProfilePhoto()) ?>" 
                                     alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="cv-info">
                            <h3><?= htmlspecialchars($personalInfo['fullName'] ?? 'No Name') ?></h3>
                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($personalInfo['email'] ?? 'No Email') ?></p>
                            <p><i class="fas fa-phone"></i> <?= htmlspecialchars($personalInfo['phone'] ?? 'No Phone') ?></p>
                        </div>
                    </div>
                    
                    <div class="cv-details">
                        <p><strong>Skills:</strong> <?= count($cv->getSkills()) ?> skills listed</p>
                        <p><strong>Experience:</strong> <?= count($cv->getExperience()) ?> positions</p>
                        <p><strong>Education:</strong> <?= count($cv->getEducation()) ?> entries</p>
                        <p><strong>Created:</strong> <?= date('M d, Y', strtotime($cv->getCreatedAt())) ?></p>
                    </div>
                    
                    <div class="cv-actions">
                        <a href="?action=pdf&id=<?= $cv->getId() ?>" class="btn btn-small btn-pdf">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($cvs)): ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 15px; margin-top: 30px;">
                <i class="fas fa-file-alt" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 10px;">No CVs Created Yet</h3>
                <p style="color: #999;">Get started by creating your first CV!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

