<?php

$host = "localhost";
// $dbname = "Aurora_OfflineTest";
$dbname = "Aurora";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    $db_error_message = $e->getMessage();
    include_once __DIR__ . '/../db_error.php';
    exit;
}

?>