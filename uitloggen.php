<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    $_SESSION['user'] = null;
    unset($_SESSION['user']);
}

flash('success', 'Je bent succesvol uitgelogd.');
header('Location: homepaginamaken.php');
exit;
