<?php
session_start();

require_once __DIR__ . '/../controllers/ShowController.php';

$controller = new ShowController();
$controller->edit();
