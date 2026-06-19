<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/config.php';

$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam = trim($_POST['voornaam'] ?? '');
    $achternaam = trim($_POST['achternaam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($voornaam === '' || $achternaam === '' || $email === '' || $password === '' || $passwordConfirm === '') {
        flash('error', 'Vul alle velden correct in.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Vul een geldig e-mailadres in.');
    } elseif ($password !== $passwordConfirm) {
        flash('error', 'De wachtwoorden komen niet overeen.');
    } elseif (strlen($password) < 6) {
        flash('error', 'Het wachtwoord moet minimaal 6 tekens lang zijn.');
    } else {
        $checkEmail = $pdo->prepare('SELECT 1 FROM Contact WHERE Email = :email AND IsActief = 1');
        $checkEmail->execute([':email' => $email]);
        if ($checkEmail->fetch()) {
            flash('error', 'Dit e-mailadres is al in gebruik.');
        } else {
            $gebruikersnaam = strtolower(str_replace(' ', '', $voornaam . '.' . $achternaam . '.' . time()));
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $pdo->beginTransaction();

                $insertUser = $pdo->prepare(
                    'INSERT INTO Gebruiker (Voornaam, Tussenvoegsel, Achternaam, Gebruikersnaam, Wachtwoord, IsIngelogd, IsActief)
                     VALUES (:voornaam, :tussenvoegsel, :achternaam, :gebruikersnaam, :wachtwoord, 0, 1)'
                );
                $insertUser->execute([
                    ':voornaam' => $voornaam,
                    ':tussenvoegsel' => null,
                    ':achternaam' => $achternaam,
                    ':gebruikersnaam' => $gebruikersnaam,
                    ':wachtwoord' => $hashedPassword,
                ]);

                $gebruikerId = (int)$pdo->lastInsertId();

                $insertContact = $pdo->prepare(
                    'INSERT INTO Contact (GebruikerId, Email, Mobiel, IsActief) VALUES (:gebruiker_id, :email, :mobiel, 1)'
                );
                $insertContact->execute([
                    ':gebruiker_id' => $gebruikerId,
                    ':email' => $email,
                    ':mobiel' => '0000000000'
                ]);

                $insertRole = $pdo->prepare(
                    'INSERT INTO Rol (GebruikerId, Naam, IsActief) VALUES (:gebruiker_id, :naam, 1)'
                );
                $insertRole->execute([
                    ':gebruiker_id' => $gebruikerId,
                    ':naam' => 'Bezoeker'
                ]);

                $nextRelation = (int)$pdo->query('SELECT COALESCE(MAX(Relatienummer), 0) + 1 FROM Bezoeker')->fetchColumn();
                $insertVisitor = $pdo->prepare(
                    'INSERT INTO Bezoeker (GebruikerId, Relatienummer, IsActief) VALUES (:gebruiker_id, :relatienummer, 1)'
                );
                $insertVisitor->execute([
                    ':gebruiker_id' => $gebruikerId,
                    ':relatienummer' => $nextRelation
                ]);

                $pdo->commit();
                flash('success', 'Je account is succesvol aangemaakt. Je kunt nu inloggen.');
                header('Location: inloggen.php');
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                flash('error', 'Er is iets misgegaan bij het aanmaken van je account.');
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
    <title>Registreren - Aurora Theater</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #241c36 0%, #0f0f14 100%);
            color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }
        .auth-card {
            width: 100%;
            max-width: 480px;
            background: #f7f3e7;
            color: #1e1e1e;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 18px 45px rgba(0,0,0,.35);
        }
        .auth-card h2 {
            margin: 0 0 8px;
            font-size: 28px;
        }
        .auth-card p {
            margin: 0 0 22px;
            color: #5d5d5d;
        }
        .flash {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-weight: 700;
        }
        .flash-error { background: #ffefef; color: #a42828; }
        .flash-success { background: #eefaf2; color: #1d6b3d; }
        .auth-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .auth-form input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #d7c89d;
            margin-bottom: 14px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .auth-form button {
            width: 100%;
            background: #d4a75d;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        .auth-links {
            text-align: center;
            margin-top: 14px;
        }
        .auth-links a {
            color: #8a5a18;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <h2>Registreren</h2>
            <p>Maak een account aan bij Aurora Theater</p>
            <?php if ($flash): ?>
                <div class="flash flash-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
            <?php endif; ?>
            <form class="auth-form" method="POST">
                <label for="voornaam">Voornaam</label>
                <input type="text" id="voornaam" name="voornaam" placeholder="Voornaam">
                <label for="achternaam">Achternaam</label>
                <input type="text" id="achternaam" name="achternaam" placeholder="Achternaam">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" placeholder="voorbeeld@mail.nl">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="••••••••">
                <label for="password_confirm">Bevestig wachtwoord</label>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••">
                <button type="submit">Account aanmaken</button>
            </form>
            <div class="auth-links">
                <a href="inloggen.php">Heb je al een account?</a>
            </div>
        </div>
    </div>
</body>
</html>
