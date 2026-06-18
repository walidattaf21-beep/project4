<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user']['id']);
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function canAccessDashboard(): bool {
    $user = currentUser();
    return isLoggedIn() && in_array($user['role'] ?? '', ['Administrator', 'Medewerker'], true);
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
