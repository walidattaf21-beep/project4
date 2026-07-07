<?php
require_once __DIR__ . '/../../includes/auth.php';

$root_prefix = '';
if (basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'overzicht voorstellingen') {
    $root_prefix = '../';
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!-- ================= HEADER / NAVBAR ================= -->
<header>

    <!-- Logo van Aurora Theater -->
    <a href="<?php echo $root_prefix; ?>homepaginamaken.php" style="text-decoration: none; color: inherit;">
        <div class="logo">
            <h1>AURORA</h1>
            <span>Theater</span>
        </div>
    </a>

    <!-- Navigatiemenu -->
    <nav id="main-nav">
        <ul>
            <li><a href="<?php echo $root_prefix; ?>homepaginamaken.php">Home</a></li>
            <li><a href="<?php echo $root_prefix; ?>overzicht voorstellingen/overzichtvoorstellingen.php">Voorstellingen</a></li>
            <li><a href="#">Tickets</a></li>
            <li><a href="#">Over Ons</a></li>
            <li><a href="#">Contact</a></li>
            <?php if (canAccessDashboard()): ?>
                <li><a href="<?php echo $root_prefix; ?>beheerdashboard.php" class="beheer-btn">Beheerdashboard</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="buttons" id="nav-actions">
        <?php if (isLoggedIn()):
            $user = currentUser();
        ?>
            <a href="<?php echo $root_prefix; ?>uitloggen.php" class="account-btn" title="Uitloggen">
                <i class="fas fa-user-circle"></i>
                <span>Ingelogd</span>
                <strong><?= h($user['name'] ?? 'Gebruiker') ?></strong>
            </a>
        <?php else: ?>
            <a href="<?php echo $root_prefix; ?>inloggen.php" class="login-btn">Inloggen</a>
            <a href="<?php echo $root_prefix; ?>registreren.php" class="register-btn">Registreren</a>
        <?php endif; ?>
    </div>

    <!-- Hamburger Menu Toggle voor Mobiel -->
    <button class="menu-toggle" id="mobile-menu-toggle" aria-label="Open navigatiemenu">
        <i class="fas fa-bars"></i>
    </button>

</header>
