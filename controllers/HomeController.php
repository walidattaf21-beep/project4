<?php

class HomeController {
    public function index() {
        global $pdo;
        
        // Initialize the database connection to trigger the error page in case of downtime
        require_once __DIR__ . '/../database/config.php';

        require_once __DIR__ . '/../views/home.view.php';
    }
}
