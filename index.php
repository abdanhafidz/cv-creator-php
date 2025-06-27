<?php
session_start();
require_once 'config/database.php';
require_once 'models/CV.php';
require_once 'repositories/CVRepository.php';
require_once 'services/FileUploadService.php';
require_once 'services/PDFService.php';
require_once 'controllers/CVController.php';

class Router {
    private $routes = [];
    private $controller;
    
    public function __construct() {
        $this->controller = new CVController();
    }
    
    public function route() {
        $action = $_GET['action'] ?? 'index';
        
        switch ($action) {
            case 'create':
                $this->controller->create();
                break;
            case 'store':
                $this->controller->store();
                break;
            case 'pdf':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $this->controller->generatePDF($id);
                }
                break;
            default:
                $this->controller->index();
                break;
        }
    }
}

// index.php


$router = new Router();
$router->route();


?>