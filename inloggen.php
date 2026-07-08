<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/config.php';

$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        flash('error', 'Vul zowel e-mailadres als wachtwoord in.');
    } else {
        $stmt = $pdo->prepare(
            'SELECT g.Id, g.Voornaam, g.Achternaam, g.Wachtwoord, g.IsActief, c.Email, r.Naam AS RolNaam
             FROM Gebruiker g
             LEFT JOIN Contact c ON c.GebruikerId = g.Id
             LEFT JOIN Rol r ON r.GebruikerId = g.Id AND r.IsActief = 1
             WHERE c.Email = :email'
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['Wachtwoord'])) {
            flash('error', 'Onjuiste inloggegevens.');
        } elseif ((int)$user['IsActief'] !== 1) {
            flash('error', 'Dit account is niet actief.');
        } else {
            $_SESSION['user'] = [
                'id' => (int)$user['Id'],
                'name' => trim($user['Voornaam'] . ' ' . $user['Achternaam']),
                'email' => $user['Email'],
                'role' => $user['RolNaam'] ?? 'Bezoeker'
            ];

            $pdo->prepare('UPDATE Gebruiker SET IsIngelogd = 1, Ingelogd = NOW() WHERE Id = :id')
                ->execute([':id' => $user['Id']]);

            flash('success', 'Je bent succesvol ingelogd.');
            header('Location: homepaginamaken.php');
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
    <title>Inloggen - Aurora Theater</title>
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
            max-width: 430px;
            background: #f7f3e7;
            color: #1e1e1e;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 18px 45px rgba(0,0,0,.35);
        }
        .auth-card h2 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #1e1e1e;
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
            <h2>Inloggen</h2>
            <p>Welkom bij Aurora Theater</p>
            <?php if ($flash): ?>
                <div class="flash flash-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
            <?php endif; ?>
            <form class="auth-form" method="POST">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" placeholder="voorbeeld@mail.nl">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="••••••••">
                <button type="submit">Inloggen</button>
            </form>
            <div class="auth-links">
                <a href="homepaginamaken.php">Terug naar home</a>
                <span> · </span>
                <a href="registreren.php">Registreren</a>
            </div>
        </div>
    </div>
</body>
</html>
