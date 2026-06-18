<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/database/config.php';

$foutmelding = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam       = trim($_POST['voornaam'] ?? '');
    $achternaam     = trim($_POST['achternaam'] ?? '');
    $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
    $wachtwoord     = $_POST['wachtwoord'] ?? '';
    $email          = trim($_POST['email'] ?? '');
    $mobiel         = trim($_POST['mobiel'] ?? '');

    if ($voornaam === '' || $achternaam === '' || $gebruikersnaam === '' || $wachtwoord === '' || $email === '' || $mobiel === '') {
        $foutmelding = 'Vul alle velden in.';
    } else {
        $stmt = $pdo->prepare("SELECT Id FROM Gebruiker WHERE Gebruikersnaam = ?");
        $stmt->execute([$gebruikersnaam]);

        if ($stmt->fetch()) {
            $foutmelding = 'Gebruikersnaam bestaat al.';
        } else {
            $stmt = $pdo->prepare("SELECT Id FROM Contact WHERE Email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $foutmelding = 'E-mailadres bestaat al.';
            } else {
                try {
                    $pdo->beginTransaction();

                    $wachtwoordHash = password_hash($wachtwoord, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare(
                        "INSERT INTO Gebruiker (Voornaam, Achternaam, Gebruikersnaam, Wachtwoord)
                         VALUES (?, ?, ?, ?)"
                    );
                    $stmt->execute([$voornaam, $achternaam, $gebruikersnaam, $wachtwoordHash]);
                    $gebruikerId = $pdo->lastInsertId();

                    $stmt = $pdo->prepare(
                        "INSERT INTO Contact (GebruikerId, Email, Mobiel) VALUES (?, ?, ?)"
                    );
                    $stmt->execute([$gebruikerId, $email, $mobiel]);

                    $relatienummer = $pdo->query("SELECT COALESCE(MAX(Relatienummer), 0) + 1 FROM Bezoeker")
                        ->fetchColumn();

                    $stmt = $pdo->prepare(
                        "INSERT INTO Bezoeker (GebruikerId, Relatienummer) VALUES (?, ?)"
                    );
                    $stmt->execute([$gebruikerId, $relatienummer]);

                    $pdo->prepare("INSERT INTO Rol (GebruikerId, Naam) VALUES (?, 'Bezoeker')")
                        ->execute([$gebruikerId]);

                    $pdo->commit();

                    header('Location: login.php');
                    exit;
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $foutmelding = 'Er ging iets mis bij het registreren. Probeer het opnieuw.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registreren – Aurora Theater</title>
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
  <h1>Registreren</h1>
  <?php if ($foutmelding): ?>
    <p class="fout"><?= htmlspecialchars($foutmelding) ?></p>
  <?php endif; ?>
  <form method="post">
    <label>Voornaam</label>
    <input type="text" name="voornaam" required>
    <label>Achternaam</label>
    <input type="text" name="achternaam" required>
    <label>Gebruikersnaam</label>
    <input type="text" name="gebruikersnaam" required>
    <label>Wachtwoord</label>
    <input type="password" name="wachtwoord" required>
    <label>E-mailadres</label>
    <input type="email" name="email" required>
    <label>Mobiel nummer</label>
    <input type="text" name="mobiel" required>
    <button type="submit">Registreren</button>
  </form>
  <p>Heb je al een account? <a href="login.php">Log hier in</a></p>
</body>
</html>
