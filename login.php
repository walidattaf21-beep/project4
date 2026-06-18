<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/database/config.php';

$foutmelding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord     = $_POST['wachtwoord'] ?? '';

    if ($gebruikersnaam === '' || $wachtwoord === '') {
        $foutmelding = 'Vul gebruikersnaam en wachtwoord in.';
    } else {
        $stmt = $pdo->prepare(
            "SELECT Id, Voornaam, Achternaam, Wachtwoord, IsActief
             FROM Gebruiker WHERE Gebruikersnaam = ?"
        );
        $stmt->execute([$gebruikersnaam]);
        $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$gebruiker || !password_verify($wachtwoord, $gebruiker['Wachtwoord'])) {
            $foutmelding = 'Gebruikersnaam of wachtwoord is onjuist.';
        } elseif (!$gebruiker['IsActief']) {
            $foutmelding = 'Dit account is niet actief.';
        } else {
            $stmtRol = $pdo->prepare("SELECT Naam FROM Rol WHERE GebruikerId = ? AND IsActief = 1");
            $stmtRol->execute([$gebruiker['Id']]);
            $rollen = $stmtRol->fetchAll(PDO::FETCH_COLUMN);

            $_SESSION['GebruikerId'] = $gebruiker['Id'];
            $_SESSION['Naam']        = $gebruiker['Voornaam'] . ' ' . $gebruiker['Achternaam'];
            $_SESSION['Rollen']      = $rollen;

            $pdo->prepare("UPDATE Gebruiker SET IsIngelogd = 1, Ingelogd = NOW() WHERE Id = ?")
                ->execute([$gebruiker['Id']]);

            if (array_intersect(['Administrator', 'Medewerker'], $rollen)) {
                header('Location: beheerdashboard.php');
            } else {
                header('Location: homepaginamaken.php');
            }
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inloggen – Aurora Theater</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: sans-serif; max-width: 400px; margin: 60px auto; padding: 0 20px; }
    label { display:block; margin-top: 14px; margin-bottom: 4px; font-weight: bold; }
    input { width: 100%; padding: 8px; box-sizing: border-box; }
    button { margin-top: 20px; padding: 10px 18px; cursor: pointer; }
    .fout { color: red; }
    a { color: #555; }
  </style>
</head>
<body>
  <h1>Inloggen</h1>
  <?php if ($foutmelding): ?>
    <p class="fout"><?= htmlspecialchars($foutmelding) ?></p>
  <?php endif; ?>
  <form method="post">
    <label>Gebruikersnaam</label>
    <input type="text" name="gebruikersnaam" required>
    <label>Wachtwoord</label>
    <input type="password" name="wachtwoord" required>
    <button type="submit">Inloggen</button>
  </form>
  <p>Nog geen account? <a href="registreren.php">Registreer hier</a></p>
</body>
</html>
