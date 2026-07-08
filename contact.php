<?php
require_once 'includes/auth.php';
require_once 'database/config.php';

$flash = getFlash();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Buscar shows para o dropdown
$shows = $pdo->query(
    'SELECT Id, Naam, Datum FROM Voorstelling WHERE IsActief = 1 ORDER BY Datum DESC'
)->fetchAll(PDO::FETCH_ASSOC);

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $naam = trim($_POST['feedback_naam'] ?? '');
    $email = trim($_POST['feedback_email'] ?? '');
    $bericht = trim($_POST['feedback_bericht'] ?? '');
    $voorstelling_id = $_POST['feedback_show'] ?? null;
    
    if (empty($naam) || empty($email) || empty($bericht)) {
        flash('error', 'Vul alle verplichte velden in.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Vul een geldig e-mailadres in.');
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO Feedback (Naam, Email, Bericht, VoorstellingId, IsActief) VALUES (:naam, :email, :bericht, :voorstelling_id, 1)'
            );
            $stmt->execute([
                ':naam' => $naam,
                ':email' => $email,
                ':bericht' => $bericht,
                ':voorstelling_id' => !empty($voorstelling_id) ? $voorstelling_id : null
            ]);
            flash('success', 'Bedankt voor je feedback! Wij nemen dit ter harte.');
        } catch (Exception $e) {
            flash('error', 'Er is iets misgegaan bij het opslaan van je feedback.');
        }
    }
    
    header('Location: contact.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Aurora Theater</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .contact-section {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 2rem;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .contact-header h1 {
            font-size: 2.5rem;
            color: #e8b923;
            margin-bottom: 1rem;
        }
        
        .contact-header p {
            font-size: 1.1rem;
            color: #ddd;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .contact-info, .feedback-form {
            background: #3d3d3d;
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid #5a5a5a;
        }
        
        .contact-info h2, .feedback-form h2 {
            color: #e8b923;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .contact-info p {
            color: #ddd;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        
        .contact-info a {
            color: #e8b923;
            text-decoration: none;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e8b923;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            background: #2b2b2b;
            border: 1px solid #5a5a5a;
            border-radius: 4px;
            color: #f0f0f0;
            font-family: inherit;
            font-size: 1rem;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #e8b923;
            box-shadow: 0 0 8px rgba(232, 185, 35, 0.3);
        }
        
        .form-submit {
            width: 100%;
            padding: 1rem;
            background: #e8b923;
            color: #000;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .form-submit:hover {
            background: #d4a818;
        }
        
        .auth-flash {
            padding: 1rem 2rem;
            border-radius: 4px;
            margin: 1rem 0;
            text-align: center;
            font-weight: 600;
        }
        
        .auth-flash-success {
            background: linear-gradient(135deg, #166534 0%, #15803d 100%);
            color: white;
            border: 2px solid #22c55e;
        }
        
        .auth-flash-error {
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
            color: white;
            border: 2px solid #ef4444;
        }
        
        @media (max-width: 768px) {
            .contact-content {
                grid-template-columns: 1fr;
            }
            
            .contact-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <header>
        <!-- Logo van Aurora Theater -->
        <div class="logo">
            <h1>AURORA</h1>
            <span>Theater</span>
        </div>

        <!-- Navigatiemenu -->
        <nav>
            <ul>
                <li><a href="homepaginamaken.php">Home</a></li>
                <li><a href="overzicht voorstellingen/overzichtvoorstellingen.php">Voorstellingen</a></li>
                <li><a href="#">Tickets</a></li>
                <li><a href="#">Over Ons</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>
                <?php if (canAccessDashboard()): ?>
                    <li><a href="beheerdashboard.php" class="beheer-btn">Beheerdashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <!-- Rechterkant: login/account acties -->
        <div class="buttons">
            <?php if (isLoggedIn()):
                $user = currentUser();
            ?>
                <a href="uitloggen.php" class="account-btn" title="Uitloggen">
                    <i class="fas fa-user-circle"></i>
                    <span>Ingelogd</span>
                    <strong><?= h($user['name'] ?? 'Gebruiker') ?></strong>
                </a>
            <?php else: ?>
                <a href="inloggen.php" class="login-btn">Inloggen</a>
                <a href="registreren.php" class="register-btn">Registreren</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger Menu Toggle voor Mobiel -->
        <button class="menu-toggle" id="mobile-menu-toggle" aria-label="Open navigatiemenu">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Flashmelding bovenaan de pagina -->
    <?php if ($flash): ?>
        <div class="auth-flash auth-flash-<?= h($flash['type']) ?>" id="auth-flash">
            <?= h($flash['message']) ?>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const flash = document.getElementById('auth-flash');
                if (!flash) return;

                setTimeout(function () {
                    flash.style.opacity = '0';
                    setTimeout(function () {
                        flash.style.display = 'none';
                    }, 500);
                }, 4000);
            });
        </script>
    <?php endif; ?>

    <!-- ================= CONTACT SECTION ================= -->
    <section class="contact-section">
        <div class="contact-header">
            <h1>Contact & Feedback</h1>
            <p>Heb je vragen of wil je feedback geven over onze voorstellingen? Laat het ons weten!</p>
        </div>

        <div class="contact-content">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2><i class="fas fa-info-circle"></i> Aurora Theater</h2>
                
                <p>
                    <strong>Adres:</strong><br>
                    Theaterplein 1<br>
                    Amsterdam, Nederland
                </p>
                
                <p>
                    <strong>E-mail:</strong><br>
                    <a href="mailto:info@aurora.nl">info@aurora.nl</a>
                </p>
                
                <p>
                    <strong>Telefoon:</strong><br>
                    <a href="tel:+31201234567">+31 (0)20 123 4567</a>
                </p>
                
                <p>
                    <strong>Openingstijden:</strong><br>
                    Maandag - Vrijdag: 10:00 - 18:00<br>
                    Zaterdag - Zondag: 12:00 - 20:00
                </p>

                <hr style="border: none; border-top: 1px solid #5a5a5a; margin: 1.5rem 0;">

                <h2 style="margin-top: 1.5rem;"><i class="fas fa-envelope"></i> Social Media</h2>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="#" style="color: #e8b923; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#ddd'" onmouseout="this.style.color='#e8b923'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" style="color: #e8b923; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#ddd'" onmouseout="this.style.color='#e8b923'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="color: #e8b923; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#ddd'" onmouseout="this.style.color='#e8b923'">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" style="color: #e8b923; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#ddd'" onmouseout="this.style.color='#e8b923'">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="feedback-form">
                <h2><i class="fas fa-comments"></i> Stuur ons je feedback</h2>
                
                <form method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="feedback_naam">Naam *</label>
                        <input 
                            type="text" 
                            id="feedback_naam" 
                            name="feedback_naam" 
                            required 
                            placeholder="Je volledige naam"
                        >
                    </div>

                    <div class="form-group">
                        <label for="feedback_email">E-mailadres *</label>
                        <input 
                            type="email" 
                            id="feedback_email" 
                            name="feedback_email" 
                            required 
                            placeholder="je@example.com"
                        >
                    </div>

                    <div class="form-group">
                        <label for="feedback_show">Feedback over een voorstelling (optioneel)</label>
                        <select id="feedback_show" name="feedback_show">
                            <option value="">-- Selecteer een voorstelling --</option>
                            <?php foreach ($shows as $show): ?>
                                <option value="<?= $show['Id'] ?>">
                                    <?= h($show['Naam']) ?> (<?= date('d-m-Y', strtotime($show['Datum'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="feedback_bericht">Je boodschap *</label>
                        <textarea 
                            id="feedback_bericht" 
                            name="feedback_bericht" 
                            required 
                            placeholder="Vertel ons wat je denkt..."
                        ></textarea>
                    </div>

                    <button type="submit" name="submit_feedback" class="form-submit">
                        <i class="fas fa-paper-plane"></i> Verstuur Feedback
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <?php include 'views/includes/footer.php'; ?>

    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const nav = document.querySelector('nav ul');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
            });
        }
    </script>

</body>

</html>
